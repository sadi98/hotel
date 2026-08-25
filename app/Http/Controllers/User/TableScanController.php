<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\RedirectResponse;
class TableScanController extends Controller {public function scan(string $qrToken):RedirectResponse{$t=RestaurantTable::active()->where('qr_token',$qrToken)->firstOrFail();session(['scanned_restaurant_table_id'=>$t->id,'scanned_restaurant_table_number'=>$t->table_number]);return redirect()->route('menus.index')->with('success',"Ordering from table {$t->table_number}.");}public function clear():RedirectResponse{session()->forget(['scanned_restaurant_table_id','scanned_restaurant_table_number']);return redirect()->route('menus.index')->with('success','Scanned table cleared.');}}
