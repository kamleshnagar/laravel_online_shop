<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        
        //FETCH FEATURED PRODUCTS
        $featuredProducts = Product::orderBy('id', 'DESC')
        ->where(['status' => 1 , 'is_featured' => 'Yes'])
        ->get();
        $data['featuredProducts'] = $featuredProducts;

        //FETCH LATEST PRODUCTS
        $latestProducts = Product::orderBy('id', 'DESC')
        ->where(['status' => 1])
        ->take(8)
        ->get();
        $data['latestProducts'] = $latestProducts;

        return view('front.home',$data);
    }
}
