@extends('adminlte::page')

@section('title', $product->name.' Features')

@section('content_header')
    <h1>{{$product->name}} Features</h1>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @can('edit-products')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('product.feature.create',$product->id) }}" class="btn btn-success">Add Feature</a>
                                    </div>
                                    <div class="col-md-6">
                                        <form method="post"
                                            action="{{ route('product.features.delete.all', $product->id) }}"
                                            style="display: initial;">
                                            @csrf
                                            @method('delete')
                                            <button class="deleteAll btn btn-danger btn-sm" type="submit" title="Delete"
                                                onclick="">
                                                Delete All Features
                                            </button>
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
                                                    <th>Feature</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($product_features as $product_feature)
                                                    <tr>
                                                        <td width="20px">{{ $loop->iteration }}</td>
                                                        <td>
                                                            {{ $product_feature->feature }}
                                                        </td>
                                                        <td style="line-height: 35px">
                                                            @can('edit-product-features')
                                                                <a href="{{ route('product.feature.edit', $product_feature->id) }}"
                                                                    class="btn btn-sm btn-success" title="Edit">
                                                                    <i class="fa fa-pen"></i>
                                                                </a>
                                                            @endcan
                                                            @can('delete-products')
                                                                <form method="post"
                                                                    action="{{ route('product.feature.delete', $product_feature->id) }}"
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
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Feature</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
@stop

@section('css')
@stop

@section('js')
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
