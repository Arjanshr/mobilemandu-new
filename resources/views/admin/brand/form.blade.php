@extends('adminlte::page')

@section('title', 'Brands')

@section('content_header')
    <h1>Brands</h1>
@stop

@section('content')


    <div class="card-body">
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        @if (isset($brand))
                            @livewire('brand-form', ['brand' => $brand])
                        @else
                            @livewire('brand-form');
                        @endif
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