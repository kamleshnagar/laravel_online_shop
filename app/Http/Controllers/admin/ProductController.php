<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with(['product_images', 'brand']);

        if ($request->get('keyword') !== '') {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }

        $products = $products->paginate(10);

        return view('admin.products.list', compact('products'));
    }

    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['brands'] = $brands;
        $data['categories'] = $categories;

        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {

        $validation = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required',

        ];

        if (! empty($request->track_qty) && $request->track_qty == 'Yes') {
            $validation['qty'] = 'required|numeric';
        }

        $request->validate($validation);
        $product = new Product;
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->shipping_returns = $request->shipping_returns;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->track_qty = $request->track_qty;
        $product->qty = $request->qty;
        $product->status = $request->status;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->brand_id = $request->brand;
        $product->is_featured = $request->is_featured;
        $product->related_products = (!empty($request->related_products) ? implode(',', $request->related_products) : '');

        $product->save();

        // save Gallery pics
        if (! empty($request->image_array)) {
            foreach ($request->image_array as $key => $temp_image_id) {

                $tempImageInfo = TempImage::find($temp_image_id);
                $extArray = explode('.', $tempImageInfo->name);
                $ext = last($extArray);

                $productImage = new ProductImage;
                $productImage->product_id = $product->id;
                $productImage->image = 'NULL';
                $productImage->save();
                $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                $productImage->image = $imageName;
                $productImage->save();

                // Generate Product Thumbnails

                // Large Image

                $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                $destPath = public_path() . '/uploads/products/large/' . $imageName;

                $image = Image::read($sourcePath);
                $image->resize(1080, 1350, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $image->save($destPath);

                // Small Image

                $destPath = public_path() . '/uploads/products/small/' . $imageName;
                $image = Image::read($sourcePath);
                $image->resize(300, 300, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $image->save($destPath);
            }
        }

        return redirect()->back()->with('success', 'Product added successfully');
    }

    public function edit($id)
    {

        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $productImage = ProductImage::where('product_id', $product->id)->get();
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
       
        //fetch related products
        $relatedProducts = null;
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->get();
        }
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['productImage'] = $productImage;
        $data['relatedProducts'] = $relatedProducts;

        return view('admin.products.edit', $data);
    }

    public function update($id, Request $request)
    {

        $product = Product::find($id);

        $validation = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required',

        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $validation['qty'] = 'required|numeric';
        }

        $request->validate($validation);
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->shipping_returns = $request->shipping_returns;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->track_qty = $request->track_qty;
        $product->qty = $request->qty;
        $product->status = $request->status;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category;
        $product->brand_id = $request->brand;
        $product->is_featured = $request->is_featured;
        $product->related_products = (!empty($request->related_products) ? implode(',', $request->related_products) : '');
        $product->save();

        return redirect()->route('index.products')->with('success', 'Product Updated successfully');
    }

    public function destroy($id, Request $request)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $productImages = ProductImage::where('product_id', $product->id)->get();
        if (! empty($productImages)) {

            foreach ($productImages as $productImages) {
                File::delete(public_path('uploads/products/large/' . $productImages->image));
                File::delete(public_path('uploads/products/small/' . $productImages->image));
            }
            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();

        return redirect()->back()->with('success', 'Successfully deleted Product');
    }


    public function getProducts(Request $request)
    {

        $tempProduct = [];
        if ($request->q != '') {
            $products = Product::where('title', 'like', '%' . $request->q . '%')
                ->select('id', 'title')
                ->limit(10)
                ->get();

            if ($products != null) {
                foreach ($products as $key => $product) {
                    $tempProduct[] = array('id' => $product->id, 'title' => $product->title);
                }
            }
        }

        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }
}
