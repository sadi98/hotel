<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Request $request, Order $order): View
    {
        $allowed = hash_equals($order->access_token, (string) $request->query('token'))
            || (auth()->check() && ($order->user_id === auth()->id() || auth()->user()->isStaffOrAdmin()));
        abort_unless($allowed,403);
        return view('users.orders.show',['order'=>$order->load(['restaurantTable','items.components','payments'])]);
    }
    public function history(): View
    {
        return view('users.orders.history',['orders'=>Order::where('user_id',auth()->id())->latest()->paginate(10)]);
    }
}
