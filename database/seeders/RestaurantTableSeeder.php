<?php
namespace Database\Seeders;
use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class RestaurantTableSeeder extends Seeder {public function run():void{foreach([['T01','Indoor Table 01','indoor',2],['T02','Indoor Table 02','indoor',2],['T03','Indoor Table 03','indoor',4],['T04','Indoor Table 04','indoor',4],['T05','Indoor Table 05','indoor',6],['OUT-01','Garden Table 01','outdoor',4],['OUT-02','Garden Table 02','outdoor',4],['POOL-01','Poolside Table 01','poolside',4],['VIP-01','VIP Private Table 01','vip',8],['VIP-02','VIP Private Table 02','vip',12]] as $v){$table=RestaurantTable::firstOrNew(['table_number'=>$v[0]]);if(!$table->exists)$table->qr_token=(string)Str::uuid();$table->fill(['name'=>$v[1],'area'=>$v[2],'capacity'=>$v[3],'is_active'=>true,'description'=>"Table in {$v[2]} area."])->save();}}}
