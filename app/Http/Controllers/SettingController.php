<?php

namespace App\Http\Controllers;

use App\WebsiteSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = WebsiteSetting::all();
        $map = $settings->mapWithKeys(function ($item) {
            return [ $item->key => $item->value ];
        })->toArray();

        return view('settings.index', [
            'settings' => $map
        ]);
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
            'data.*.*' => [
                'sometimes',
                'nullable',
                'max:100'
            ]
        ]);

        $data = $request->input('data');

        $settings = [];
        foreach ($data as $setting => $values) {
            foreach ($values as $index => $value) {

                if (!filled($value)) {
                    continue;
                }

                $key = $setting .'.'. $index;

                WebsiteSetting::updateOrInsert(
                    compact('key'),
                    compact('key', 'value')
                );
            }
        }



        return redirect()->back()->with('message', 'Successfully updated settings');
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
