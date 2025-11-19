<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

class TempImagesController extends Controller
{
   public function create (Request $request){
    $image = $request->image;

    if(!empty($image)){
        $ext = $image->getClientOriginalExtension();
        $new_name = time() . '.' .$ext;

        $temp_image = new TempImage();
        $temp_image->name = $new_name;
        $temp_image->save();

        $image->move(public_path().'/temp',$new_name);
        
        return response()->json([
            'status' => true,
            'image_id' => $temp_image->id,
            'message' => 'Image uploaded successfully',

        ]);
        
    }


   }
}
