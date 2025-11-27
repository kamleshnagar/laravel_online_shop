<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class CategoryController extends Controller
{
    public function index(Request $request)
    {

        $categories = Category::latest('id');

        if (! empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%'.$request->get('keyword').'%');

        }

        $categories = $categories->paginate(10);

        return view('admin.category.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',

        ]);

        if ($validator->passes()) {

            $category = new Category;
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            // save image here
            if (! empty($request->image_id)) {

                $temp_image = TempImage::find($request->image_id);
                $ext_array = explode('.', $temp_image->name);
                $ext = last($ext_array);

                $new_image_name = $category->id.'.'.$ext;
                $s_path = public_path().'/temp/'.$temp_image->name;
                $d_path = public_path().'/uploads/category/'.$new_image_name;

                File::copy($s_path, $d_path);

                // ---------- Create Thumbnail via intervention ----------

                $d_path = public_path().'/uploads/category/thumb/'.$new_image_name;

                $img = Image::read($s_path);
                $img->resize(250, 250, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img->save($d_path);
                $img->save($d_path);

                $category->image = $new_image_name;
                $category->save();

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
    public function edit($category_id, Request $request)
    {

        $category = Category::find($category_id);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.category.edit', compact('category'));

    }

    public function update($category_id, Request $request)
    {

        $category = Category::find($category_id);

        if (empty($category)) {

            Session::flash('error', 'Category not found');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not Found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $category->save();

            $old_image = $category->image;

            // save image here
            if (! empty($request->image_id)) {

                $temp_image = TempImage::find($request->image_id);
                $ext_array = explode('.', $temp_image->name);
                $ext = last($ext_array);

                $new_image_name = $category->id.'-'.time().'.'.$ext;
                $s_path = public_path().'/temp/'.$temp_image->name;
                $d_path = public_path().'/uploads/category/'.$new_image_name;

                File::copy($s_path, $d_path);

                // ---------- Create Thumbnail via intervention ----------

                $d_path = public_path().'/uploads/category/thumb/'.$new_image_name;

                $img = Image::read($s_path);
                $img->resize(250, 250, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });
                $img->save($d_path);
                $img->save($d_path);

                $category->image = $new_image_name;
                $category->save();

                // delete old image
                File::delete(public_path().'/uploads/category/thumb'.$old_image);
                File::delete(public_path().'/uploads/category'.$old_image);

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

    public function destroy($category_id, Request $request)
    {
        $category = Category::find($category_id);
        if (empty($category)) {

            Session::flash('error', 'Category not found');

            return response()->json([
                'status' => true,
                'message' => 'Category not found',
            ]);
        }

        File::delete(public_path().'/uploads/category/thumb/'.$category->image);
        File::delete(public_path().'/uploads/category/'.$category->image);

        $category->delete();

        Session::flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}
