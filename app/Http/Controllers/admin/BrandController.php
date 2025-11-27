<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest('id');

        if ($request->filled('keyword')) {
            $brands->where('name', 'like', '%'.$request->keyword.'%');
        }
        $brands = $brands->paginate(10);

        return view('admin.brand.list', compact('brands'));

    }

    public function create()
    {

        return view('admin.brand.create');

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $brand = new Brand;
            $brand->name = $request->input('name');
            $brand->slug = $request->input('slug');
            $brand->status = $request->input('status');
            $brand->save();

            return redirect()->route('brands.index')->with('success', 'Brand added Successfully');

        } else {

            return redirect()->back()->withErrors($validator)->withInput();

        }

    }

    public function edit($id)
    {

        $brand = Brand::find($id);

        return view('admin.brand.edit', compact('brand'));

    }

    public function update($id, Request $request)
    {
        $brand = Brand::find($id);

        if (empty($brand)) {
            return redirect()->route('brands.index')->with('error', 'Brand not Found!');
        }

       $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id,
            'status' => 'required',
       ]);


            $brand->name = $request->input('name');
            $brand->slug = $request->input('slug');
            $brand->status = $request->input('status');
            $brand->update();

            return redirect()->route('brands.index')->with('success', 'Brand updated successfully');

    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if($brand){

            $brand->delete();
            return redirect()->back()->with('success', "Brand deleted successfully");

        }else{
            
            return redirect()->back()->with('error', "Brand not found!");
        }

    }
}
