<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;


class CountriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->input();

        $countries = Country::where(function ($query) use ($input) {
            if (isset($input['is_defined']) && $input['is_defined'] == true) {
                $query->where('currency_code', '!=', null)
                    ->where('price', '!=', null)
                    ->where('price_weekend', '!=', null)
                    ->where('duration', '!=', null);
            }

            if (isset($input['currency_code'])) {
                $query->where('currency_code', $currencyCode);
            }

        })->orderBy('currency_code', 'DESC')->orderBy('name', 'ASC')->get();

        return response()->json($countries->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'data.price' => 'required|numeric',
            'data.price_weekend' => 'required|numeric',
            'data.currency_code' => 'required|string|max:3',
            'data.duration' => 'required|numeric'
        ]);
        
        $country = Country::findOrFail($id);
        
        $country->price = $request->input('data.price');
        $country->price_weekend = $request->input('data.price_weekend');
        $country->currency_code = $request->input('data.currency_code');
        $country->duration = $request->input('data.duration');

        $country->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
