@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <h1>Categories</h1>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @can('add-category-specifications')
                            <div class="card-header">
                                <a href="{{ route('category-specification.create', $category->id) }}"
                                    class="btn btn-success">Create Category Specification</a>
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
                                                    <th>Is Variant</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category_specifications as $category_specification)
                                                    <tr>
                                                        <td width="20px">{{ $loop->iteration }}</td>
                                                        <td>
                                                            @can('edit-category-specifications')
                                                                <a href="{{ route('category-specification.edit', [$category->id,$category_specification->id]) }}"
                                                                    class="btn btn-sm btn-success" title="Edit">
                                                                    <i class="fa fa-pen"></i>
                                                                </a>
                                                            @endcan
                                                            @can('delete-category-specifications')
                                                                <form method="post"
                                                                    action="{{ route('category-specification.delete', [$category->id, $category_specification->id]) }}"
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
                                                        <td>{{ $category_specification->name }}</td>
                                                        <td>{{ $category_specification->pivot->is_variant ? 'Yes' : 'No' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actions</th>
                                                    <th>Name</th>
                                                    <th>Is Variant</th>
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
        $(document).ready(function() {
            $('#example2').DataTable();
            $('.dataTables_length').addClass('bs-select');
        })
    </script>
@stop
