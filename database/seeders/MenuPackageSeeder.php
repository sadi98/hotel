<?php
namespace Database\Seeders;
use App\Models\MenuItem;
use App\Models\MenuPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class MenuPackageSeeder extends Seeder { public function run(): void { $ids=MenuItem::pluck('id','sku'); $sets=[['PKG-ROMANTIC','Romantic Dinner Package',900000,799000,2,[['FOD-001',1],['FOD-004',2],['FOD-008',1],['BEV-011',1]]],['PKG-FAMILY','Family Dinner Package',920000,799000,4,[['FOD-001',2],['FOD-005',2],['FOD-006',2],['BEV-006',4]]],['PKG-LUNCH','Indonesian Lunch Package',200000,175000,1,[['FOD-006',1],['BEV-006',1]]],['PKG-COFFEE','Coffee and Dessert Package',160000,139000,1,[['BEV-002',1],['FOD-008',1]]]]; DB::transaction(function()use($sets,$ids){foreach($sets as $i=>$s){$p=MenuPackage::updateOrCreate(['sku'=>$s[0]],['name'=>$s[1],'slug'=>str($s[1])->slug()->toString(),'description'=>"Curated {$s[1]} for a memorable hotel dining experience.",'normal_price'=>$s[2],'package_price'=>$s[3],'serving_count'=>$s[4],'minimum_order'=>1,'is_available'=>true,'is_featured'=>true,'sort_order'=>$i+1]);$p->items()->delete();foreach($s[5] as $j=>$it)$p->items()->create(['menu_item_id'=>$ids[$it[0]],'quantity'=>$it[1],'sort_order'=>$j+1]);}}); } }
