<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;
// use App\Http\Controllers\admin\ProductImageController;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if (! empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $new_name = time().'.'.$ext;

            $temp_image = new TempImage;
            $temp_image->name = $new_name;
            $temp_image->save();

            $image->move(public_path().'/temp', $new_name);

            // Generate Thumbnail
            $s_path = public_path().'/temp/'.$new_name;
            $d_path = public_path().'/temp/thumb/'.$new_name;

            $img = Image::read($s_path);
            $img->resize(300, 300, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
            $img->save($d_path);

            return response()->json([
                'status' => true,
                'image_id' => $temp_image->id,
                'imagePath' => asset('/temp/thumb/'.$new_name),
                'message' => 'Image uploaded successfully',

            ]);

        }

    }
}
