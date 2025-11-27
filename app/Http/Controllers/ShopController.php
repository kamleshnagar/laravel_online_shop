<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {

        $subCategorySelected = '';
        $categorySelected = '';
        $brandsArray = [];
        $priceMin = null;
        $priceMax = null;

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->with('sub_categories')->get();
        $brands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();
        $products = Product::where('status', 1)->with(['product_images', 'brand']);

        // Apply fileres

        if (! empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }

        }

        if (! empty($subCategorySlug)) {

            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();

            if ($subCategory) {
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }

        if (! empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }

        if (($request->get('price_min') != '') && ($request->get('price_max') != '')) {
            $priceMin = intval($request->get('price_min'));
            $priceMax = intval($request->get('price_max'));

            if ($priceMax == 10000) {
                $priceMax = 100000000;
                $products = $products->whereBetween('price', [$priceMin, $priceMax]);
            }

            $products = $products->whereBetween('price', [$priceMin, $priceMax]);

        }

        if ($request->filled('sort')) {
            $sort = $request->get('sort');

            if ($sort === 'latest') {
                $products = $products->orderBy('id', 'DESC');
            } elseif ($sort === 'price_asc') {
                $products = $products->orderBy('price', 'ASC'); // price ASC (fixed)
            } elseif ($sort === 'price_desc') {
                $products = $products->orderBy('price', 'DESC');
            } else {
                $products = $products->orderBy('id', 'DESC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
        }

        $products = $products->paginate(10)->withQueryString(); // keep query string for pagination links

        $data['categories'] = $categories;
        $data['brands'] = $brands;
        $data['products'] = $products;
        $data['categorySelected'] = $categorySelected;
        $data['subCategorySelected'] = $subCategorySelected;
        $data['priceMax'] = $priceMax ?? 5000;
        $data['priceMin'] = $priceMin ?? 0;
        $data['brandsArray'] = $brandsArray;
        $data['sort'] = $request->get('sort');

        return view('front.shop', $data);
    }
}
