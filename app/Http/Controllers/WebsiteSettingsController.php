<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\WebsiteSetting;

use Arr;
class WebsiteSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $webSettings = WebsiteSetting::where('key', 'LIKE', 'CLASSROOM.%')
            ->orWhere('key', 'LIKE', 'STUDENT.%')
            ->orWHere('key', 'LIKE', 'TEACHER.%')
            ->get();

        $assocWebSettings = parseWebSettings($webSettings);

        return view('website_settings.index', [
            'webSettings' => $assocWebSettings
        ]);
    }

    public function register(Request $request)
    {
        $settings = WebsiteSetting::orderBy('key')->get();

        return response()->json($settings);
    }

    public function modify(Request $request)
    {
        $settings = WebsiteSetting::findOrFail($request->id);
        $settings->value = $request->new_value;
        $settings->save();

        return response()->json([
            'success' => true
        ]);
    }

    public function delete(Request $request)
    {
        $settings = WebsiteSetting::findOrFail($request->id);
        $settings->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function add(Request $request)
    {
        $settings = new WebsiteSetting();
        $settings->key = $request->key;
        $settings->value = $request->value;

        $response = ['success' => false];

        if ($settings->save()) {
            $response = ['success' => true];
        }

        return response()->json($response);
    }
}
