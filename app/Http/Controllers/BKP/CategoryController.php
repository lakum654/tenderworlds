<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Helper;

class CategoryController extends Controller
{
    public $route = 'admin/category';
    public $view  = 'admin/category.';
    public $moduleName = 'Categoory';
    public $datatableUrl = 'getCategoryData';

    public function index(Category $category){
        $moduleName = $this->moduleName;
        $datatableUrl = $this->datatableUrl;
        return view($this->view.'index',compact('moduleName','datatableUrl'));

    }

    public function getCategoryData(Request $request){

            $data = Category::get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        $editUrl = route('category.edit',encrypt($row->id));
                           $btn = '<a href="'.$editUrl.'" class="edit btn btn-primary btn-xs"><i class="fa fa-pencil"> Edit</a>';
                            return $btn;
                    })

                    ->addColumn('image', function($row){
                        $url= asset('storage/category/'.$row->image);
                       return '<img src="'.$url.'" width="50px"/>';
                    })
                    ->rawColumns(['action','image'])
                    ->make(true);


    }

    public function create(){
        $moduleName = $this->moduleName;
        return view($this->view.'form',compact('moduleName'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:jpg,jpeg'
        ]);

        if($request->has('image')){
            $fileName = time().'.'.$request->image->extension();
            $request->image->move(storage_path('category'), $fileName);
        }

        Category::create([
            'name' => $request->name,
            'image' => $fileName
        ]);

        Helper::successMsg('insert',$this->moduleName);
        return redirect($this->route);
    }

    public function edit($id){
        $moduleName = $this->moduleName;
        $category = Category::find(decrypt($id));
        return view($this->view.'_form',compact('category','moduleName'));
    }

    public function update(Request $request,$id){
        $fileName = '';
        if($request->has('image')){
            $fileName = time().'.'.$request->image->extension();
            $request->image->move(storage_path('category'), $fileName);
            if($request->old_image != ''){
                if(file_exists(storage_path('category/'.$request->old_image))){
                    unlink(storage_path('category/'.$request->old_image));
                }
            }
        } else {
            $fileName = $request->old_image;
        }

        Category::find($id)->update(['name' => $request->name,'image' => $fileName]);
        Helper::successMsg('update',$this->moduleName);
        return redirect($this->route);
    }
}
