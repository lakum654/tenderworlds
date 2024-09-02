@extends('master')


@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $moduleName }}
    {{-- <small>it all starts here</small> --}}
  </h1>

  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#" active>{{ $moduleName }}</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">{{ $moduleName }} List</h3>
      <div class="box-tools">
        <!-- <a href="{{ route('keywords.create') }}" class="btn btn-theme btn-sm">+ New</a> -->
      </div>

    </div>
    <div class="box-body">
      <table class="table table-bordered datatable">
        <thead>
          <tr>
            <th>Sr No.</th>
            <th>Name</th>
            <th>Answer</th>
            <th>Sitemap</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <th>Sr No.</th>
            <th>Name</th>
            <th>Answer</th>
            <th>Sitemap</th>
            <th>Action</th>
          </tr>
        </tfoot>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
    <!-- /.box-footer-->
  </div>
</section>
<!-- /.content -->
@endsection

@section('script')
<script>
  var table = $('.datatable').DataTable({
    processing: true,
    serverSide: true,
    ajax: "{{ route('getQuestionData') }}",
    columns: [{
        data: 'DT_RowIndex',
        name: 'DT_RowIndex'
      },
      {
        data: 'title',
        name: 'title'
      },
      {
        data: 'answer',
        name: 'answer'
      },
      {
        data: 'sitemap',
        name: 'sitemap'
      },
      {
        data: 'action',
        name: 'action',
        orderable: false,
        searchable: false
      },
    ]
  });
</script>
@endsection