<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;
class CategorySeeder extends Seeder { public function run(): void { foreach([
 ['Appetizer','appetizer','food'],['Soup','soup','food'],['Main Course','main-course','food'],['Indonesian Cuisine','indonesian-cuisine','food'],['Dessert','dessert','food'],['Coffee','coffee','beverage'],['Tea','tea','beverage'],['Juice','juice','beverage'],['Mocktail','mocktail','beverage'],['Cocktail','cocktail','beverage'],['Beer','beer','beverage'],['Wine','wine','beverage']
] as $i=>$v) Category::updateOrCreate(['slug'=>$v[1]],['name'=>$v[0],'menu_type'=>$v[2],'description'=>"Premium {$v[0]} selection.",'sort_order'=>$i+1,'is_active'=>true]); } }
