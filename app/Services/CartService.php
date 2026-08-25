<?php
namespace App\Services;
use App\Models\{Cart,CartItem,MenuItem,MenuPackage};
use Illuminate\Support\Facades\{Auth,DB};
use Illuminate\Validation\ValidationException;
class CartService {
 public function getCart(): Cart { if(!Auth::check()) return Cart::firstOrCreate(['user_id'=>null,'session_id'=>session()->getId()]); return DB::transaction(function(){ $u=Cart::firstOrCreate(['user_id'=>Auth::id()],['session_id'=>session()->getId()]); $g=Cart::whereNull('user_id')->where('session_id',session()->getId())->where('id','!=',$u->id)->first(); if($g){foreach($g->items as $i){$e=$u->items()->where('item_type',$i->item_type)->where('menu_item_id',$i->menu_item_id)->where('menu_package_id',$i->menu_package_id)->where('note',$i->note)->first();$e?$e->increment('quantity',$i->quantity):$i->update(['cart_id'=>$u->id]);}$g->delete();}return $u;}); }
 public function add(string $type,int $id,int $quantity,?string $note): void { $cart=$this->getCart(); if($type===CartItem::TYPE_SINGLE){$m=MenuItem::available()->findOrFail($id);$keys=['item_type'=>$type,'menu_item_id'=>$m->id,'menu_package_id'=>null,'note'=>$note];}elseif($type===CartItem::TYPE_PACKAGE){$m=MenuPackage::available()->findOrFail($id);if($quantity<$m->minimum_order)throw ValidationException::withMessages(['quantity'=>"Minimum order is {$m->minimum_order}."]);$keys=['item_type'=>$type,'menu_item_id'=>null,'menu_package_id'=>$m->id,'note'=>$note];}else throw ValidationException::withMessages(['item_type'=>'Invalid item type.']);$item=$cart->items()->where($keys)->first();$item?$item->increment('quantity',$quantity):$cart->items()->create($keys+['quantity'=>$quantity]); }
 public function total(Cart $cart): float { $cart->loadMissing(['items.menuItem','items.menuPackage']);return(float)$cart->items->sum(fn(CartItem $i)=>$i->subtotal); }
}
