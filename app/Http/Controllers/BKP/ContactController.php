<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Helper;
use App\Models\Contact;

class ContactController extends Controller
{
    public $moduleName = "Contact";
    public $view = 'admin/contact.';
    public $route = 'contact';

    public function index()
    {
        $moduleName = $this->moduleName;
        $contact = Contact::first();
        return view($this->view.'form',compact('contact','moduleName'));
    }

    public function update(Request $request){
        $request->validate([
            'phone_1' => 'required|numeric|min:10',
            'email_1' => 'required|email',
            'address_1' => 'required'
        ]);

        $contact = Contact::find(1);
        // $contact = new Contact();
        $contact->phone_1 = $request->phone_1;
        $contact->phone_2 = $request->phone_2;
        $contact->email_1 = $request->email_1;
        $contact->email_2 = $request->email_2;
        $contact->address_1 = $request->address_1;
        $contact->address_2 = $request->address_2;
        $contact->save();

        Helper::successMsg('update',$this->moduleName);
        return back();
    }
}
