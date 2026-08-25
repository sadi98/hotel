<?php
namespace App\Services;
use App\Models\{Order,Payment};
use Midtrans\{Config,Snap};
use RuntimeException;
class MidtransService {
 public function __construct(){Config::$serverKey=(string)config('midtrans.server_key');Config::$isProduction=(bool)config('midtrans.is_production');Config::$isSanitized=true;Config::$is3ds=true;}
 public function createSnapToken(Order $order,Payment $payment): string {if(!config('midtrans.server_key'))throw new RuntimeException('MIDTRANS_SERVER_KEY is not configured.');return Snap::getSnapToken(['transaction_details'=>['order_id'=>$payment->payment_number,'gross_amount'=>(int)round((float)$payment->amount)],'customer_details'=>['first_name'=>$order->customer_name,'email'=>$order->customer_email?:'guest@example.com','phone'=>$order->customer_phone],'item_details'=>[['id'=>$order->order_number,'price'=>(int)round((float)$payment->amount),'quantity'=>1,'name'=>substr('Restaurant '.$order->order_number,0,50)]],'callbacks'=>['finish'=>route('payments.finish',['payment'=>$payment,'token'=>$order->access_token])],'expiry'=>['unit'=>'minutes','duration'=>60]]);}
}
