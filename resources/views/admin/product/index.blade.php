@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1>Products</h1>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @can('add-products')
                            <div class="card-header">
                                <div class="row">

                                    <div class="col-md-6">
                                        <a href="{{ route('product.create') }}" class="btn btn-success">Create Product</a>
                                    </div>
                                    <div class="col-md-6">
                                        <form action="{{ route('product.import') }}" method="post"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" class="" name="import_file">
                                            <button type="submit" class="btn btn-primary">Import</button>
                                        </form>
                                    </div>
                                    <div class="col-md-12">
                                        <form>
                                            <div class="row">
                                                <!-- Brand-->
                                                <div class="form-group col-sm-5">
                                                    <label for="brand_id">Brand</label>
                                                    <select id='brand_id' name="brand_id" class="form-control">
                                                        <option value="">Select a Brand</option>
                                                        @foreach ($brands as $brand)
                                                            <option value="{{ $brand->id }}"
                                                                {{ (isset($selected_brand) && $selected_brand == $brand->id) || old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                {{ $brand->name }}

                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('brand_id')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Categories-->
                                                <div class="form-group col-sm-6">
                                                    <label for="category_id">Categories</label><br />
                                                    <select name="category_id[]" id="categories" class="form-control" multiple>
                                                        <option value="">--select--</option>
                                                        @if (isset($categories))
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    @if (isset($selected_categories) && in_array($category->id, $selected_categories)) {{ 'selected' }} @endif>
                                                                    {{ $category->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('category_id')
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-1">
                                                    <input id="submit" type="submit" value="Filter"
                                                        class="btn btn-warning" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        <div class="card-body">
                            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                            aria-describedby="example2_info">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actions</th>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th>Brand</th>
                                                    <th>Image</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td width="20px">{{ $loop->iteration }}</td>
                                                        <td style="line-height: 35px">
                                                            @can('read-products')
                                                                <a href="{{ route('product.show', $product->id) }}"
                                                                    class="btn btn-sm btn-primary" title="View Details">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            @endcan
                                                            @can('edit-products')
                                                                <a href="{{ route('product.edit', $product->id) }}"
                                                                    class="btn btn-sm btn-success" title="Edit">
                                                                    <i class="fa fa-pen"></i>
                                                                </a>
                                                            @endcan
                                                            @can('edit-products')
                                                                <a href="{{ route('product.specifications', $product->id) }}"
                                                                    class="btn btn-sm btn-warning"
                                                                    title="Manage Specifications">
                                                                    <i class="fa fa-list"></i>
                                                                </a>
                                                            @endcan
                                                            @can('edit-products')
                                                                <a href="{{ route('product.features', $product->id) }}"
                                                                    class="btn btn-sm btn-primary" title="Manage Features">
                                                                    <i class="fa fa-list"></i>
                                                                </a>
                                                            @endcan
                                                            @can('edit-products')
                                                                <a href="{{ route('product.images', $product->id) }}"
                                                                    class="btn btn-sm btn-secondary" title="Manage Images">
                                                                    <i class="fa fa-image"></i>
                                                                </a>
                                                            @endcan
                                                            @can('delete-products')
                                                                <form method="post"
                                                                    action="{{ route('product.delete', $product->id) }}"
                                                                    style="display: initial;">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button class="delete btn btn-danger btn-sm" type="submit"
                                                                        title="Delete" onclick="">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                        <td>{{ $product->name }}</td>
                                                        <td>
                                                            @foreach ($product->categories as $category)
                                                                {{ $category->name }}{{ !$loop->last ? ',' : '' }}
                                                            @endforeach
                                                        </td>
                                                        <td>{{ $product->brand ? $product->brand->name : '' }}</td>
                                                        <td>
                                                            @if ($product->getFirstMedia())
                                                                <img src="{{ $product->getFirstMedia()->getUrl() }}"
                                                                    width="100" />
                                                            @endif
                                                        </td>
                                                        <td>{{ ucfirst($product->status) }}ed</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actions</th>
                                                    <th>Name</th>
                                                    <th>Category</th>
                                                    <th>Brand</th>
                                                    <th>Image</th>
                                                    <th>Status</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                {{ $products->appends($_GET)->links() }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
@stop

@section('css')
    <style>
        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#categories').select2();
        });
    </script>
    <script>
        $(document.body).on('click', '.delete', function(event) {
            event.preventDefault();
            var form = $(this).closest("form");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit()
                }
            })
        });
    </script>
@stop
