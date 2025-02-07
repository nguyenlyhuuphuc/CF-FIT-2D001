@extends('admin.layout.master')

@section('content')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Create Product Category</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" method="post" action="{{ route('admin.product_category.store') }}">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Name</label>
              <input name="name" type="text" class="form-control" id="name" placeholder="Enter name">
            </div>
            <div class="form-group">
              <label for="slug">Slug</label>
              <input name="slug" type="text" class="form-control" id="slug" placeholder="Enter slug">
            </div>
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option>--- Please Select ---</option>
                <option>Show</option>
                <option>Hide</option>
              </select>
            </div>
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
      <!-- /.card -->

    </div>
  </div>
@endsection