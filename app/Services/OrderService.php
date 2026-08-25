<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(private readonly CartService $carts) {}

    public function checkout(array $data, Cart $cart, ?int $tableId, bool $fromQr): array
    {
        $subtotal=$this->carts->total($cart); $discount=0.0;
        $servicePct=(float)config('restaurant.service_charge_percentage',10);
        $taxPct=(float)config('restaurant.tax_percentage',10);
        $service=round(($subtotal-$discount)*$servicePct/100,2);
        $tax=round(($subtotal-$discount+$service)*$taxPct/100,2);
        $grand=$subtotal-$discount+$service+$tax;

        return DB::transaction(function() use($data,$cart,$tableId,$fromQr,$subtotal,$discount,$servicePct,$service,$taxPct,$tax,$grand) {
            $later=$data['payment_timing']===Order::TIMING_PAY_LATER;
            $order=Order::create([
                'access_token'=>(string)Str::uuid(),'order_number'=>$this->number('ORD'),
                'user_id'=>auth()->check() && auth()->user()->isUser() ? auth()->id() : null,
                'created_by'=>auth()->check() && auth()->user()->isStaffOrAdmin() ? auth()->id() : null,
                'restaurant_table_id'=>$tableId,'ordered_from_table_qr'=>$fromQr,'order_type'=>$data['order_type'],
                'customer_name'=>$data['customer_name'],'customer_phone'=>$data['customer_phone'],'customer_email'=>$data['customer_email']??null,
                'room_number'=>$data['room_number']??null,'delivery_address'=>$data['delivery_address']??null,'guest_count'=>$data['guest_count'],
                'status'=>$later?Order::STATUS_CONFIRMED:Order::STATUS_PENDING,'payment_status'=>$later?Order::PAYMENT_UNPAID:Order::PAYMENT_PENDING,
                'payment_timing'=>$data['payment_timing'],'subtotal'=>$subtotal,'discount_amount'=>$discount,
                'service_charge_percentage'=>$servicePct,'service_charge_amount'=>$service,'tax_percentage'=>$taxPct,'tax_amount'=>$tax,'grand_total'=>$grand,
                'customer_note'=>$data['customer_note']??null,'scheduled_at'=>$data['scheduled_at']??null,'ordered_at'=>now(),'confirmed_at'=>$later?now():null,
            ]);
            foreach($cart->items as $cartItem) $this->copy($order,$cartItem);
            $payment=$later?null:Payment::create(['order_id'=>$order->id,'payment_number'=>$this->number('PAY'),'payment_channel'=>Payment::CHANNEL_GATEWAY,'provider'=>'midtrans','amount'=>$grand,'status'=>Payment::STATUS_PENDING,'expired_at'=>now()->addHour()]);
            $cart->items()->delete();
            return compact('order','payment');
        });
    }

    private function copy(Order $order, CartItem $cartItem): void
    {
        if($cartItem->item_type===CartItem::TYPE_SINGLE){
            $menu=$cartItem->menuItem; if(!$menu?->is_available) throw ValidationException::withMessages(['cart'=>'A menu is unavailable.']);
            $order->items()->create(['item_type'=>OrderItem::TYPE_SINGLE,'menu_item_id'=>$menu->id,'item_name'=>$menu->name,'unit_price'=>$menu->price,'quantity'=>$cartItem->quantity,'subtotal'=>(float)$menu->price*$cartItem->quantity,'note'=>$cartItem->note]); return;
        }
        $package=$cartItem->menuPackage; if(!$package?->is_available) throw ValidationException::withMessages(['cart'=>'A package is unavailable.']);
        $package->loadMissing('items.menuItem');
        $item=$order->items()->create(['item_type'=>OrderItem::TYPE_PACKAGE,'menu_package_id'=>$package->id,'item_name'=>$package->name,'unit_price'=>$package->package_price,'quantity'=>$cartItem->quantity,'subtotal'=>(float)$package->package_price*$cartItem->quantity,'note'=>$cartItem->note]);
        foreach($package->items as $component) $item->components()->create(['menu_item_id'=>$component->menu_item_id,'menu_name'=>$component->menuItem?->name??'Deleted menu','quantity_per_package'=>$component->quantity,'total_quantity'=>$component->quantity*$cartItem->quantity,'note'=>$component->note]);
    }
    private function number(string $prefix): string { return $prefix.'-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)); }
}
