<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\{RedirectResponse,Request};
use Illuminate\View\View;
class CartController extends Controller {
 public function __construct(private readonly CartService $carts){}
 public function index():View{$cart=$this->carts->getCart();$cart->load(['items.menuItem.category','items.menuPackage.items.menuItem']);return view('users.cart.index',['cart'=>$cart,'total'=>$this->carts->total($cart)]);}
 public function store(Request $r):RedirectResponse{$d=$r->validate(['item_type'=>'required|in:single,package','reference_id'=>'required|integer|min:1','quantity'=>'required|integer|min:1|max:50','note'=>'nullable|string|max:500']);$this->carts->add($d['item_type'],$d['reference_id'],$d['quantity'],$d['note']??null);return redirect()->route('cart.index')->with('success','Item added to cart.');}
 public function update(Request $r,CartItem $cartItem):RedirectResponse{abort_unless($cartItem->cart_id===$this->carts->getCart()->id,403);$cartItem->update($r->validate(['quantity'=>'required|integer|min:1|max:50','note'=>'nullable|string|max:500']));return back()->with('success','Cart updated.');}
 public function destroy(CartItem $cartItem):RedirectResponse{abort_unless($cartItem->cart_id===$this->carts->getCart()->id,403);$cartItem->delete();return back()->with('success','Item removed.');}
 public function clear():RedirectResponse{$this->carts->getCart()->items()->delete();return back()->with('success','Cart cleared.');}
}
