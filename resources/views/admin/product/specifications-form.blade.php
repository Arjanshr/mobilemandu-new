@extends('adminlte::page')

@section('title', 'Specifications')

@section('content_header')
    <h1>Specifications</h1>
@stop

@section('content')


    <div class="card-body">
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ isset($product_specification) ? 'Edit Specification' : 'Create New Specification' }}</h3>
                            </div>
                            <form method="POST"
                                action="{{ isset($product_specification) ? route('product.specification.update', $product_specification->id) : route('product.specification.insert', $product->id) }}">
                                @csrf
                                @if (isset($product_specification))
                                    @method('patch')
                                @endif
                                <div class="card-body row">
                                    <!-- Name -->
                                    <div class="form-group col-sm-6">
                                        <label for="name">Name*</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name"
                                            value="{{ isset($product_specification) ? $product_specification->specification->name : old('name') }}" required>
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="name">Value*</label>
                                        <input type="text" class="form-control" id="value" name="value" placeholder="Value"
                                            value="{{ isset($product_specification) ? $product_specification->value : old('value') }}" required>
                                        @error('value')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                        
                                    <div class="form-group col-sm-12">
                                        <input id="submit" type="submit" value="{{ isset($product_specification) ? 'Edit' : 'Create' }}"
                                            class="btn btn-primary" />
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
