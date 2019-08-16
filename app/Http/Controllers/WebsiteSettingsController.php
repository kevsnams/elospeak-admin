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

    public function save(Request $request)
    {
        $input = $request->validate([
            'CLASSROOM.price_per_class' => 'required|numeric',
            'CLASSROOM.duration' => 'required|integer',
            'CLASSROOM.price_per_class_weekend' => 'required|numeric',
            'TEACHER.salary_per_class' => 'required|numeric'
        ]);
        
        foreach ($input as $group => $settings) {
            foreach ($settings as $setting => $value) {
                $key = $group .'.'. $setting;
                WebsiteSetting::updateOrInsert(['key' => $key], ['value' => $value]);
            }
        }

        return redirect(route('settings.index'))->with('saveSuccess', true);
    }
}
