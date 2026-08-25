<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\{Category,MenuItem,MenuPackage};
use Illuminate\Http\Request;
use Illuminate\View\View;
class MenuController extends Controller {
 public function index(Request $r):View{$items=MenuItem::with('category')->available()->when($r->filled('type'),fn($q)=>$q->whereHas('category',fn($c)=>$c->where('menu_type',$r->type)))->when($r->filled('beverage_type'),fn($q)=>$q->where('beverage_type',$r->beverage_type))->when($r->alcohol==='yes',fn($q)=>$q->where('is_alcoholic',true))->when($r->alcohol==='no',fn($q)=>$q->where('is_alcoholic',false))->orderBy('sort_order')->paginate(12)->withQueryString();$packages=MenuPackage::with('items.menuItem')->available()->orderBy('sort_order')->get();$categories=Category::where('is_active',true)->orderBy('sort_order')->get();return view('users.menu.index',compact('items','packages','categories'));}
 public function showMenu(MenuItem $menuItem):View{abort_unless($menuItem->is_available,404);return view('users.menu.show',['menuItem'=>$menuItem->load('category')]);}
 public function showPackage(MenuPackage $menuPackage):View{abort_unless($menuPackage->is_available,404);return view('users.menu.package',['menuPackage'=>$menuPackage->load('items.menuItem.category')]);}
}
