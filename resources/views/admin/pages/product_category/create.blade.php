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
              <input name="name" value="{{ old('name') }}" type="text" class="form-control" id="name" placeholder="Enter name">
            </div>
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
              <label for="slug">Slug</label>
              <input name="slug" value="{{ old('slug') }}" type="text" class="form-control" id="slug" placeholder="Enter slug">
            </div>
            @error('slug')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
              <label>Status</label>
              <select name="status" class="form-control">
                <option value="">--- Please Select ---</option>
                <option {{ old('status') == '1' ? 'selected' : '' }} value="1">Show</option>
                <option {{ old('status') == '0' ? 'selected' : '' }} value="0">Hide</option>
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
    $(document).ready(function(){
        $('#name').on('keyup', function(){
            var name = $(this).val();
            // var url = '{{ route("admin.product_category.make_slug") }}'+ '?slug=' + name; 
            // $.ajax({
            //   url: url, //Action of form
            //   method: 'GET', //method of form
            //   success:function(response){
            //       $('#slug').val(response.slug);
            //   },
            //   error: function(respsonse){
                
            //   }
            // });
            $.ajax({
                url: '{{ route("admin.product_category.make_slug_post") }}', //Action of form
                method: 'POST', //method of form
                data: {
                  'slug': name,
                  '_token': '{{ csrf_token() }}'
                },
                success:function(response){
                    $('#slug').val(response.slug);
                },
                error: function(respsonse){
                  
                }
            });
        });
    });
</script>
@endsection