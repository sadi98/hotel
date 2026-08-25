<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RestaurantOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders=Order::with('restaurantTable')->when($request->status,fn($q)=>$q->where('status',$request->status))->latest()->paginate(20)->withQueryString();
        return view('admin.restaurant.orders.index',compact('orders'));
    }
    public function show(Order $order): View { return view('admin.restaurant.orders.show',['order'=>$order->load(['restaurantTable','items.components','payments.processedBy'])]); }
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data=$request->validate(['status'=>['required',Rule::in([Order::STATUS_CONFIRMED,Order::STATUS_PREPARING,Order::STATUS_READY,Order::STATUS_SERVED,Order::STATUS_COMPLETED,Order::STATUS_CANCELLED])],'cancellation_reason'=>'nullable|string|max:500']);
        if($data['status']===Order::STATUS_CANCELLED && empty($data['cancellation_reason'])) throw ValidationException::withMessages(['cancellation_reason'=>'Cancellation reason is required.']);
        $order->update(['status'=>$data['status'],'cancellation_reason'=>$data['cancellation_reason']??$order->cancellation_reason,'completed_at'=>$data['status']===Order::STATUS_COMPLETED?now():$order->completed_at,'cancelled_at'=>$data['status']===Order::STATUS_CANCELLED?now():$order->cancelled_at]);
        return back()->with('success','Order status updated.');
    }
    public function pay(Request $request, Order $order): RedirectResponse
    {
        abort_if($order->payment_status===Order::PAYMENT_PAID,422,'Order is already paid.');
        $data=$request->validate(['payment_method'=>['required',Rule::in([Payment::METHOD_CASH,Payment::METHOD_CARD,Payment::METHOD_QRIS,Payment::METHOD_TRANSFER])],'paid_amount'=>'required|numeric|min:0']);
        if((float)$data['paid_amount']<(float)$order->grand_total) throw ValidationException::withMessages(['paid_amount'=>'Paid amount is less than the order total.']);
        DB::transaction(function()use($data,$order){Payment::create(['order_id'=>$order->id,'processed_by'=>auth()->id(),'payment_number'=>'CSH-'.now()->format('YmdHis').'-'.Str::upper(Str::random(5)),'payment_channel'=>Payment::CHANNEL_CASHIER,'payment_method'=>$data['payment_method'],'provider'=>'manual','amount'=>$order->grand_total,'paid_amount'=>$data['paid_amount'],'change_amount'=>(float)$data['paid_amount']-(float)$order->grand_total,'status'=>Payment::STATUS_PAID,'paid_at'=>now()]);$order->update(['payment_status'=>Order::PAYMENT_PAID]);});
        return back()->with('success','Cashier payment recorded.');
    }
}
