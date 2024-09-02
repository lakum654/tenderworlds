<?php

namespace App\Http\Controllers\admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Sitemap;
use DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public $route = 'admin/sitemap';
    public $view  = 'admin/sitemap.';
    public $moduleName = 'Sitemap';

    public function index()
    {
        $moduleName = $this->moduleName;
        return view($this->view.'index',compact('moduleName'));
    }

    public function getData() {
        $data = Sitemap::get();
        return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $statusUrl = route('sitemap.changeStatus',encrypt($row->id));
                    $btn = '';

                      if($row->view == 1) {
                        $btn .= '<a href="'.$statusUrl.'" class="edit btn btn-danger btn-sm" style="margin-left:5px;"><i class="fa fa-check"> </i> Unread</a>';
                      } else {
                        $btn .= '<a href="'.$statusUrl.'" class="edit btn btn-success btn-sm" style="margin-left:5px;"><i class="fa fa-check" > </i> Read</a>';
                      }

                      return $btn;
                })

                ->addColumn('filename',function($row) {
                    $path = url()->to($row->filename);
                    return '<a href="'.$path.'" target="_blank">'.explode('/',$row->filename)[2].'</a>';
                })

                ->editColumn('is_read',function($row) {
                    return $row->view == 1 ? 'Read' : 'Unread';
                })

                ->rawColumns(['action','filename'])
                ->make(true);
    }

    public function view($id) {
        $sitemap = Sitemap::find($id);
    }

    public function changeStatus($id) {
        $status = Sitemap::find(decrypt($id))->view;

        if($status == 1) {
            Sitemap::find(decrypt($id))->update(['view' => 0]);
            Helper::successMsg('custom','File Unread  Successfully.');
        } else {
            Sitemap::find(decrypt($id))->update(['view' => 1]);
            Helper::successMsg('custom','File Read  Successfully.');
        }

        return redirect($this->route);
    }

}
