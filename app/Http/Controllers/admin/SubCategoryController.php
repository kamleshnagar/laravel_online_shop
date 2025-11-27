<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {

        $subCategories = SubCategory::latest('sub_categories.id')->select('sub_categories.*', 'categories.name as categoryName')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id');

        if (! empty($request->get('keyword'))) {
            $categories = $subCategories->where('sub_categories.name', 'like', '%'.$request->get('keyword').'%');
            $categories = $subCategories->orWhere('categories.name', 'like', '%'.$request->get('keyword').'%');

        }

        $subCategories = $subCategories->paginate(10);

        return view('admin.sub-category.list', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;

        return view('admin.sub-category.create', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $subCategory = new SubCategory;
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            Session::flash('success', 'Sub Category added successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category added successfully.',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function edit($id, Request $request)
    {

        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            Session::flash('error', 'Record Not Found');

            return redirect()->route('sub-categories.index');
        }

        $categories = Category::orderBy('name', 'ASC')->get();

        $data['categories'] = $categories;
        $data['subCategory'] = $subCategory;

        return view('admin.sub-category.edit', $data);

    }

    public function update($id, Request $request)
    {

        $subCategory = SubCategory::find($id);

        if (empty($subCategory)) {
            return response()->json([
                'status' => false,
                'notFound' => 'Sub Category not found',

            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->update();

            Session::flash('success', 'Sub Category added successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Sub Category added successfully.',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destroy($id, Request $request)
    {

        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {

            Session::flash('error', 'Category not found');

            return response()->json([
                'status' => true,
                'message' => 'Category not found',
            ]);
        }

        $subCategory->delete();

        Session::flash('success', 'Sub Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfully',
        ]);

    }
}
