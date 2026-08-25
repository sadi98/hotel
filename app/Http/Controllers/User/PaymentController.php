<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\{RedirectResponse,Request};
use Illuminate\Support\Str;
use Illuminate\View\View;
class PaymentController extends Controller {
 public function __construct(private readonly MidtransService $midtrans){}
 private function guard(Request $request,Payment $payment):void{$order=$payment->order;$allowed=hash_equals($order->access_token,(string)$request->query('token'))||(auth()->check()&&($order->user_id===auth()->id()||auth()->user()->isStaffOrAdmin()));abort_unless($allowed,403);}
 public function show(Request $request,Payment $payment):View{$payment->load('order');$this->guard($request,$payment);return view('users.payments.show',['payment'=>$payment,'order'=>$payment->order,'clientKey'=>config('midtrans.client_key'),'snapUrl'=>config('midtrans.snap_url')]);}
 public function retry(Request $request,Payment $payment):RedirectResponse{$payment->load('order');$this->guard($request,$payment);abort_unless(in_array($payment->status,[Payment::STATUS_FAILED,Payment::STATUS_EXPIRED,Payment::STATUS_CANCELLED],true),422);$newPayment=Payment::create(['order_id'=>$payment->order_id,'payment_number'=>'PAY-'.now()->format('YmdHis').'-'.Str::upper(Str::random(6)),'payment_channel'=>Payment::CHANNEL_GATEWAY,'provider'=>'midtrans','amount'=>$payment->order->grand_total,'status'=>Payment::STATUS_PENDING,'expired_at'=>now()->addHour()]);$newPayment->update(['snap_token'=>$this->midtrans->createSnapToken($payment->order,$newPayment)]);return redirect()->route('payments.show',['payment'=>$newPayment,'token'=>$payment->order->access_token]);}
 public function finish(Request $request,Payment $payment):View{$payment->load('order');$this->guard($request,$payment);return view('users.payments.finish',['payment'=>$payment,'order'=>$payment->order]);}
}
