<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {

        
        $image = $request->image;
        
        if (! empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $sourcePath = $image->getPathName();
        }
        
        
        $productImage = new ProductImage;
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();
        
        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();
        
        
        // Generate Product Thumbnails

        // Large Image
        $destPath = public_path().'/uploads/products/large/'.$imageName;
        $image = Image::read($sourcePath);
        $image->resize(1080, 1350, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        });
        // $image->resizeCanvas(1080, 1350, 'center', false, 'ffffff');
        $image->save($destPath);
        
        // Small Image
        $destPath = public_path().'/uploads/products/small/'.$imageName;
        $image = Image::read($sourcePath);
        $image->resize(300, 300, function ($c) {
            $c->aspectRatio();
        });
        $image->save($destPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/products/small/'.$productImage->image),
            'message' => 'Product image saved successfully',

        ]);

    }


    function destroy(Request $request){

        $productImage = ProductImage::find($request->id);
        
        if (empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ]);
        }
        
        // Delete Images from folder
        File::delete(public_path('uploads/products/large/'.$productImage->image));
        File::delete(public_path('uploads/products/small/'.$productImage->image));


        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);

    }


}
