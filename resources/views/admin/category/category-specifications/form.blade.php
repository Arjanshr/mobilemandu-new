@extends('adminlte::page')

@section('title', 'Categories')

@section('content_header')
    <h1>Categories</h1>
@stop

@section('content')


    <div class="card-body">
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ isset($category_specification) ? 'Edit Category Specification' : 'Create New Category Specification' }}
                                </h3>
                            </div>
                            <form method="POST"
                                action="{{ isset($category_specification) ? route('category-specification.update', [$category->id, $category_specification->id]) : route('category-specification.insert', $category->id) }}"
                                enctype="multipart/form-data">
                                @csrf
                                @if (isset($category_specification))
                                    @method('patch')
                                @endif
                                <div class="card-body row">
                                    <!-- Name -->
                                    <div class="form-group col-sm-6">
                                        <label for="name">Specification Name</label>
                                        <input type="text" class="form-control" id="specification" name="specification"
                                            placeholder="Specification Name"
                                            value="{{ isset($category_specification) ? $category_specification->specification : old('specification') }}">
                                        @error('specification')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-10">
                                        <input id="submit" type="submit"
                                            value="{{ isset($category_specification) ? 'Edit' : 'Create' }}"
                                            class="btn btn-primary" />
                                    </div>
                                    <div class="form-group col-sm-2">
                                        <a href="{{route('category-specifications',$category->id)}}" class="btn btn-danger">Exit</a>
                                    </div>
                            </form>
                        </div>

                    </div>

                </div>

            </div>
        </section>
    </div>


@stop

@section('css')
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#role').select2();
        });
    </script>
@stop
