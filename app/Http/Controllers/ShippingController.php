<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Shipping;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $countries = Country::get();
        $shippings = Shipping::with('country:id,name')->get();

        return view('admin.shippings.index', compact('countries', 'shippings'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'country_id' => 'required',
            'shipping_charge' => 'required|numeric|min:0'
        ]);

        if ($request->country_id !== 'rest_of_world') {
            $request->validate([
                'country_id' => 'exists:countries,id'
            ]);
        }

        $exists = Shipping::where('country_id', $request->country_id)->exists();

        if ($exists) {
            return back()->with('error', 'Shipping already added for this country');
        }
        // dd($validated);
        Shipping::create($validated);

        return back()->with('success', 'Shipping Charges Created Successfully');
    }

    public function destroy($id)
    {
        $shipping = Shipping::findOrFail($id);

        if (!$shipping) {
            return back()->withError('Shipping not found.');
        }
        $shipping->delete();
        return back()->with('success', 'Shipping Deleted Successfully');
    }
}
