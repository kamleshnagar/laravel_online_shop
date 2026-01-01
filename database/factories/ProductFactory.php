<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        
        $title = fake()->unique()->name();
        $slug = Str::slug($title);
        $subCategories = [4,5,6];
        $subCatRandKey = array_rand($subCategories);
        $brands = [3,4];
        $brand = array_rand($brands);
       
        $price = intval((rand(199, 19999)) / 100) * 100 + 99;
        $comparePrice =  intval($price + (rand(5,20))*100);
?>
   
        <?php

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 1,
            'sub_category_id' => $subCategories[$subCatRandKey],
            'brand_id' =>$brands[$brand],
            'price' => $price,
            'compare_price' => $comparePrice,
            'sku' => 'SKU-'.rand(1000, 100000),
            'track_qty' => 'Yes',
            'qty' => 10,
            'is_featured' => 'Yes',
            'status' => 1,
            'description' => fake()->text(200),
            'barcode' => fake()->numberBetween(1000000000, 999999999999),

        ];
    }
}
