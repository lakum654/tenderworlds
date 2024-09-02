<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Helper;
use App\Models\About;

class AboutController extends Controller
{
    public $moduleName = "About";
    public $view = 'admin/about.';
    public $route = 'about';

    public function index()
    {
        $moduleName = $this->moduleName;
        $contact = About::first();
        return view($this->view.'form',compact('contact','moduleName'));
    }
}
