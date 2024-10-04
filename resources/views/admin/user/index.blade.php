@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1>Users</h1>
@stop
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @can('add-users')
                            <div class="card-header">
                                <a href="{{ route('user.create') }}" class="btn btn-success">Create User</a>
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
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Roles</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    @php
                                                        if (
                                                            $user->hasRole('super-admin') &&
                                                            !auth()
                                                                ->user()
                                                                ->hasRole('super-admin')
                                                        ) {
                                                            continue;
                                                        }
                                                        if (
                                                            $user->hasRole('admin') &&
                                                            auth()
                                                                ->user()
                                                                ->cannot('read-admin')
                                                        ) {
                                                            continue;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td width="20px">{{ $loop->iteration }}</td>
                                                        <td>
                                                            @can('read-users')
                                                                <a href="{{ route('user.show', $user->id) }}"
                                                                    class="btn btn-sm btn-primary" title="View Details">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            @endcan
                                                            @can('edit-users')
                                                                @if (
                                                                    !$user->hasRole('admin') ||
                                                                        auth()->user()->can('edit-admin'))
                                                                    <a href="{{ route('user.edit', $user->id) }}"
                                                                        class="btn btn-sm btn-success" title="Edit">
                                                                        <i class="fa fa-pen"></i>
                                                                    </a>
                                                                @endif
                                                            @endcan
                                                            @can('delete-users')
                                                                @if (
                                                                    !$user->hasRole('admin') ||
                                                                        auth()->user()->can('delete-admin'))
                                                                    <form method="post"
                                                                        action="{{ route('user.delete', $user->id) }}"
                                                                        style="display: initial;">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <button class="delete btn btn-danger btn-sm"
                                                                            type="submit" title="Delete" onclick="">
                                                                            <i class="fas fa-trash-alt"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endcan
                                                            @can('read-activities')
                                                                <a href="{{ route('user.activity', $user->id) }}"
                                                                    class="btn btn-sm btn-warning" title="View Activities">
                                                                    <i class="fa fa-list"></i>
                                                                </a>
                                                            @endcan
                                                        </td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->phone }}</td>
                                                        <td>
                                                            @foreach ($user->getRoleNames() as $roles)
                                                                {{ $roles }}{{ $loop->last ? '' : ', ' }}
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Actions</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Roles</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                {{ $users->links() }}
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
