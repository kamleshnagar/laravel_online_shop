<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Catagory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class CatagoryController extends Controller
{
    public function index(Request $request)
    {

        $catagories = Catagory::latest('id');

        if (! empty($request->get('keyword'))) {
            $catagories = $catagories->where('name', 'like', '%'.$request->get('keyword').'%');

        }
         
        $catagories = $catagories->paginate(10);

        return view('admin.catagory.list', compact('catagories'));
    }

    public function create()
    {
        return view('admin.catagory.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:catagories',

        ]);

        if ($validator->passes()) {

            $catagory = new Catagory;
            $catagory->name = $request->name;
            $catagory->slug = $request->slug;
            $catagory->status = $request->status;
            $catagory->save();

            // save image here
            if (! empty($request->image_id)) {

                $temp_image = TempImage::find($request->image_id);
                $ext_array = explode('.', $temp_image->name);
                $ext = last($ext_array);

                $new_image_name = $catagory->id.'.'.$ext;
                $s_path = public_path().'/temp/'.$temp_image->name;
                $d_path = public_path().'/uploads/catagory/'.$new_image_name;

                File::copy($s_path, $d_path);

                // ---------- Create Thumbnail via intervention ----------

                $d_path = public_path().'/uploads/catagory/thumb/'.$new_image_name;

                $img = Image::read($s_path);
                $img->resize(250, 250, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img->save($d_path);
                $img->save($d_path);

                $catagory->image = $new_image_name;
                $catagory->save();

            }

            Session::flash('success', 'Category added successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category added successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    // --------------------edit-------------------------
    public function edit($catagory_id, Request $request)
    {

        $catagory = Catagory::find($catagory_id);
        if (empty($catagory)) {
            return redirect()->route('catagories.index');
        }

        return view('admin.catagory.edit', compact('catagory'));

    }

    public function update($catagory_id, Request $request)
    {

        $catagory = Catagory::find($catagory_id);

        if (empty($catagory)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Catagory not Found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:catagories,slug,'.$catagory->id.',id',
        ]);

        if ($validator->passes()) {

            $catagory->name = $request->name;
            $catagory->slug = $request->slug;
            $catagory->status = $request->status;
            $catagory->save();

            $old_image = $catagory->image;

            // save image here
            if (! empty($request->image_id)) {

                $temp_image = TempImage::find($request->image_id);
                $ext_array = explode('.', $temp_image->name);
                $ext = last($ext_array);

                $new_image_name = $catagory->id.'-'.time().'.'.$ext;
                $s_path = public_path().'/temp/'.$temp_image->name;
                $d_path = public_path().'/uploads/catagory/'.$new_image_name;

                File::copy($s_path, $d_path);

                // ---------- Create Thumbnail via intervention ----------

                $d_path = public_path().'/uploads/catagory/thumb/'.$new_image_name;

                $img = Image::read($s_path);
                $img->resize(250, 250, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img->save($d_path);
                $img->save($d_path);

                $catagory->image = $new_image_name;
                $catagory->save();

                // delete old image
                File::delete(public_path().'/uploads/catagory/thumb'.$old_image);
                File::delete(public_path().'/uploads/catagory'.$old_image);

            }

            Session::flash('success', 'Category updated successfully');

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destroy($catagory_id, Request $request)
    {
        $catagory = Catagory::find($catagory_id);
        if (empty($catagory)) {
             Session::flash('error', 'Catagory not found');
            return response()->json([
                'status' => true,
                'message' => 'Catagory not found',
            ]);
        }

        File::delete(public_path().'/uploads/catagory/thumb/'.$catagory->image);
        File::delete(public_path().'/uploads/catagory/'.$catagory->image);

        $catagory->delete();

        Session::flash('success', 'Catagory deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Catagory deleted successfully',
        ]);
    }
}
