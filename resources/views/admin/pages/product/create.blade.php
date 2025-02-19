@extends('admin.layout.master')

@section('content')

<div class="row">

    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Create Product</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form role="form" method="post" action="{{ route('admin.product.store') }}" enctype="multipart/form-data">
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
              <label for="price">Price</label>
              <input name="price" value="{{ old('price') }}" type="number" class="form-control" id="price" placeholder="Enter price">
            </div>
            @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <div class="form-group">
              <label for="short_description">Short Description</label>
              <div id="short_description_html"></div>
              <input type="hidden" name="short_description" id="short_description">
              <input type="hidden" name="old_short_description" id="old_short_description" value="{{ old('short_description') }}">
            </div>
            @error('short_description')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            
            <div class="form-group">
              <label for="qty">Qty</label>
              <input name="qty" value="{{ old('qty') }}" type="number" class="form-control" id="qty" placeholder="Enter qty">
            </div>
            @error('qty')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            
          
            <div class="form-group">
              <label for="image">Image</label>
              <input name="image[]" type="file" multiple id="image">
            </div>
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <div class="form-group">
              <label for="product_category_id">Product Category</label>
              <select id="product_category_id" name="product_category_id" class="form-control">
                <option value="">--- Please Select ---</option>
                @foreach ($productCategories as $productCategory)
                  <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>  
                @endforeach

              </select>
            </div>
            @error('product_category_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
          </div>
          <!-- /.card-body -->

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create</button>
          </div>
        </form>
      </div>
      <!-- /.card -->

    </div>
  </div>

@endsection


@section('my-js')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
<script>
  const quill = new Quill('#short_description_html', {
    theme: 'snow'
  });

  quill.on('text-change', function(delta, oldDelta, source) {
      document.getElementById("short_description").value = quill.root.innerHTML;
  });
</script>
<script type="text/javascript">
    $(document).ready(function(){
        
    });
</script>
@endsection