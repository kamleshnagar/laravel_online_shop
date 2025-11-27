<?php

use App\Models\Category;

function getCategories()
{
    return Category::orderBy('name', 'ASC')
        ->where([
            'status' => 1,
            'showHome' => 'Yes',
        ])
        ->with('sub_categories')
        ->get();

}
