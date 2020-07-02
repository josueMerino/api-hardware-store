<?php

use App\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Laptop', 'Mouse', 'Keyboard', 'Processor', 'Screen'];
        $arrayCategory = [];
        foreach ($categories as $category)
        {
            array_push($arrayCategory, [
                'category' => $category,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
                ]);
        }
        Category::insert($arrayCategory);
    }
}
