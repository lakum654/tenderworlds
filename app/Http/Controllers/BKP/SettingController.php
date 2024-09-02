<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Helper;
use App\Models\Setting;
use App\Models\User;
class SettingController extends Controller
{
    public $moduleName = "Settings";
    public $view = 'admin/settings.';
    public $route = 'setting';

    public function index()
    {
        $moduleName = $this->moduleName;
        $setting = Setting::first();
        return view($this->view.'form',compact('setting','moduleName'));
    }

    public function update(Request $request){
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);
        Auth::user()->update(['password' => bcrypt($request->cpassword)]);
        Helper::successMsg('update',$this->moduleName);
        return back();
    }

    public function siteSetting(Request $request){

        $request->validate([
            'title' => 'string',
            'logo'  => 'image|mimes:jpg,jpeg,png,JPG,JPEG,PNG',
            'favicon'  => 'image|mimes:jpg,jpeg,png,JPG,JPEG,PNG',
        ]);

        if($request->has('logo')){
            $logoName = time().'logo.'.$request->logo->extension();
            $request->logo->move(public_path('setting'), $logoName);
        } else {
            $logoName = 'logo.png';
        }
        if($request->has('favicon')){
            $faviconName = time().'favicon.'.$request->favicon->extension();
            $request->favicon->move(public_path('setting'), $faviconName);
        } else {
            $faviconName = 'favicon.jpg';
        }

        // return $request->all();
        $setting = Setting::find(1);
        $setting->title = $request->title;
        $setting->logo = $logoName;
        $setting->favicon = $faviconName;
        $setting->save();

        Helper::successMsg('update',$this->moduleName);
        return back();

    }
}
