@extends('adminlte::page')

@section('title', 'Coupons')

@section('content_header')
    <h1>Coupons</h1>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @can('add-coupons')
                            <div class="card-header">
                                <a href="{{ route('coupons.create') }}" class="btn btn-success">Create Coupon</a>
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
                                                    <th>Code</th>
                                                    <th>Discount</th>
                                                    <th>Expiration Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($coupons as $coupon)
                                                    <tr>
                                                        <td width="20px">{{ $loop->iteration }}</td>
                                                        <td>
                                                            @can('edit-coupons')
                                                                <a href="{{ route('coupons.edit', $coupon->id) }}"
                                                                    class="btn btn-sm btn-success" title="Edit">
                                                                    <i class="fa fa-pen"></i>
                                                                </a>
                                                            @endcan
                                                            @can('delete-coupons')
                                                                <form method="post"
                                                                    action="{{ route('coupons.delete', $coupon->id) }}"
                                                                    style="display: initial;">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button class="delete btn btn-danger btn-sm" type="submit"
                                                                        title="Delete">
                                                                        <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        </td>
                                                        <td>{{ $coupon->code }}</td>
                                                        <td>{{ ($coupon->type == 'fixed'?'RS : ':'').$coupon->discount.($coupon->type == 'percentage'?'% : ':'') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($coupon->expires_at)->format('d M, Y') }}</td>
                                                        <td>{{ ucfirst($coupon->status?'active':'inactive') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actions</th>
                                                    <th>Code</th>
                                                    <th>Discount</th>
                                                    <th>Expiration Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                {{ $coupons->links() }}
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
