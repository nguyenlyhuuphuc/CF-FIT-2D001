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
          <h3 class="card-title">Product</h3>
          <div>
            <br>
            {{-- <form action="{{ route('admin.product_category.index') }}" method="get">
              <label for="name">Name</label>
              <input type="text" name="name" id="name" value="{{ request()->name }}" class="form-control">
              <label for="sort">Sort</label>
              <select name="sort" id="sort" class="form-control">
                <option value="">---Please Select---</option>
                <option {{ request()->sort === 'latest' ? 'selected' : '' }} value="latest">Latest</option>
                <option {{ request()->sort === 'oldest' ? 'selected' : '' }} value="oldest">Oldest</option>
              </select>
              <button class="btn btn-primary" type="submit">Search</button>
            </form> --}}
          </div>
          
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table class="table table-bordered">
            <thead>                  
              <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Image</th>
                <th>Product Category Name</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($datas as $data)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ number_format($data->price) }}</td>
                  <td>{{ $data->qty }}</td>
                  <td>
                    <img src="{{ sprintf('%s/%s', asset('images'), $data->image) }}" alt="{{ $data->name }}" width="150" />
                  </td>
                  <td>{{ $data->productCategory?->name }}</td>
                  <td>{{ date_format(date_create($data->created_at), 'd-m-Y H:i:s') }}</td>
                  <td>
                    <a href="{{ route('admin.product.show', ['product' => $data]) }}" class="btn btn-primary">Detail</a>
                    <form action="{{ route('admin.product.destroy', ['product' => $data->id]) }}" method="post">
                      @csrf
                      @method('DELETE')
                      <button onclick="return confirm('Are you sure?')" class="btn btn-danger" type="submit">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          {{ $datas->appends(request()->all())->links() }}
        </div>
      </div>
      <!-- /.card -->

      <!-- /.card -->
    </div>
  </div>

@endsection