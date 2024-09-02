<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use DataTables;
use Helper;

class ProductController extends Controller
{
    public $route = 'admin/product';
    public $view  = 'admin/product.';
    public $moduleName = 'Product';
    public $datatableUrl = 'getProductData';

    public function index(Product $product){
        $moduleName = $this->moduleName;
        $datatableUrl = $this->datatableUrl;
        return view($this->view.'index',compact('moduleName','datatableUrl'));

    }

    public function getProductData(Request $request){
        if ($request->ajax()) {
            $data = Product::with('category')->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $editUrl = route('product.edit',encrypt($row->id));
                           $btn = '<a href="'.$editUrl.'" class="edit btn btn-primary btn-xs"><i class="fa fa-pencil"> Edit</a>';
                            return $btn;
                    })

                    ->addColumn('image', function($row){
                        $url= asset('storage/product/'.$row->image);
                       return '<img src="'.$url.'" width="50px"/>';
                    })
                    ->rawColumns(['action','image'])
                    ->make(true);
        }

    }

    public function create(){
        $moduleName = $this->moduleName;
        $categories = Category::all();
        return view($this->view.'form',compact('moduleName','categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'required|mimes:jpg,jpeg',
            'description' => 'max:200'
        ]);

        if($request->has('image')){
            $fileName = time().'.'.$request->image->extension();
            $request->image->move(storage_path('product'), $fileName);
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $fileName,
            'description' => $request->description,
        ]);

        Helper::successMsg('insert',$this->moduleName);
        return redirect($this->route);
    }

    public function edit($id){
        $moduleName = $this->moduleName;
        $product = Product::with('category')->find(decrypt($id));
        $categories = Category::all();
        return view($this->view.'_form',compact('product','moduleName','categories'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'image' => 'mimes:jpg,jpeg',
            'description' => 'max:200'
        ]);

        $fileName = '';
        if($request->has('image')){
            $fileName = time().'.'.$request->image->extension();
            $request->image->move(storage_path('product'), $fileName);
            if($request->old_image != ''){
                if(file_exists(storage_path('product/'.$request->old_image))){
                    unlink(storage_path('product/'.$request->old_image));
                }
            }
        } else {
            $fileName = $request->old_image;
        }

        Product::find($id)->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'image' => $fileName,
            'description' => $request->description,
        ]);

        Helper::successMsg('update',$this->moduleName);
        return redirect($this->route);

    }
}
