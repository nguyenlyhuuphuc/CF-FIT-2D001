@extends('admin.layout.master')

@section('content')
    
<div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div>
            @if(Session::has('message'))
              <p class="alert alert-success">{{ Session::get('message') }}</p>
            @endif
          </div>
          <h3 class="card-title">Product Category</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table class="table table-bordered">
            <thead>                  
              <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($datas as $data)
                <tr>
                  <td>{{ $data->id }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->slug }}</td>
                  <td>{{ $data->status ? 'Show' : 'Hide' }}</td>
                  <td>{{ date_format(date_create($data->created_at), 'd-m-Y H:i:s') }}</td>
                  <td></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $datas->links() }}
        </div>
      </div>
      <!-- /.card -->

      <!-- /.card -->
    </div>
  </div>

@endsection