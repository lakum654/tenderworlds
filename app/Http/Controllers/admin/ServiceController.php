<?php

namespace App\Http\Controllers\admin;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public $data = [];
    public function __construct()
    {
        $this->data = [
            'moduleName' => "Services",
            'view'       => 'admin.services.',
            'route'      => 'services'
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->data['view'] . 'index', $this->data);
    }

    public function getData()
    {
        $data = Service::get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $editUrl = route('services.edit', encrypt($row->id));
                $deleteUrl = route('services.delete', encrypt($row->id));
                $statusUrl = route('services.changeStatus', encrypt($row->id));
                $btn = '';
                $btn .= '<a href="' . $editUrl . '" class="edit btn btn-primary btn-sm" style="margin-left:5px;"><i class="fa fa-pencil"> </i> Edit</a>';


                if ($row->status == 1) {
                    $btn .= '<a href="' . $statusUrl . '" class="edit btn btn-danger btn-sm" style="margin-left:5px;"><i class="fa fa-times"> </i> Inactive</a>';
                } else {
                    $btn .= '<a href="' . $statusUrl . '" class="edit btn btn-success btn-sm" style="margin-left:5px;"><i class="fa fa-check" > </i> Active</a>';
                }

                $btn .= '<a href="' . $deleteUrl . '" class="edit btn btn-danger btn-sm" style="margin-left:5px;"> <i class="fa fa-trash" /> </i> Delete</a>';
                return $btn;
            })

            ->editColumn('content', function ($row) {
                return strip_tags(Str::limit($row->content, 150));
            })

            ->editColumn('status', function ($row) {
                return $row->status == 1 ? 'Active' : 'Deactive';
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->data['view'] . 'form', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Define validation rules
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:0,1',
            'content' => 'required|string',
        ]);

        // Save the validated data to the database
        $service = new Service();
        $service->title = $request->name;
        $service->slug = Str::slug($request->name);
        $service->status = $request->status;
        $service->content = $request->content;
        $service->save();

        // Redirect to a specific page with a success message
        Helper::successMsg('insert', 'Service');
        return redirect(route($this->data['route'] . '.index'));
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
        $this->data['service'] = Service::find(decrypt($id));
        return view($this->data['view'] . '_form', $this->data);
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
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'content' => 'nullable|string',
        ]);

        // Decrypt the ID if it's encrypted
        $serviceId = $id;

        // Find the service by ID
        $service = Service::findOrFail($serviceId);

        // Update the service with the validated data
        $service->title = $validatedData['name'];
        $service->slug = Str::slug($validatedData['name']);
        $service->status = $validatedData['status'];
        $service->content = $validatedData['content'];

        // Save the updated service back to the database
        $service->save();

        // Redirect back to the service index page with a success message
        Helper::successMsg('update', 'Service');
        return redirect(route($this->data['route'] . '.index'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service::find(decrypt($id))->delete();


        Helper::successMsg('delete', 'Service');
        return redirect(route($this->data['route'] . '.index'));
    }

    public function changeStatus($id)
    {
        $status = Service::find(decrypt($id))->status;

        if ($status == 1) {
            Service::find(decrypt($id))->update(['status' => 0]);
        } else {
            Service::find(decrypt($id))->update(['status' => 1]);
        }

        Helper::successMsg('custom', 'Status Change Successfully.');
        return redirect(route($this->data['route'] . '.index'));
    }
}
