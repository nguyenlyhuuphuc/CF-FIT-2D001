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
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
              <label for="slug">Slug</label>
              <input name="slug" type="text" class="form-control" id="slug" placeholder="Enter slug">
            </div>
            @error('slug')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="">--- Please Select ---</option>
                <option value="1">Show</option>
                <option value="0">Hide</option>
              </select>
            </div>
            @error('status')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
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

@section('my-js')
<script type="text/javascript">
   $(document).ready(function() {
      $('#name').on('keypress', function(e){
          var name = $(this).val();
          console.log(name);

          $.ajax({
            method: 'GET', //method of form
            url: '{{ route("admin.product_category.make_slug") }}', //action of form
            success: function(data) {
              console.log('data', data);
              $('#slug').val(data.slug);
            },
            error: function (error){

            }
          });
      });
  });
</script>
@endsection