@extends('adminlte::page')

@section('title', 'Sliders')

@section('content_header')
    <h1>Sliders</h1>
@stop

@section('content')


    <div class="card-body">
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ isset($slider) ? 'Edit slider' : 'Create New Slider' }}</h3>
                            </div>
                            <form method="POST"
                                action="{{ isset($slider) ? route('slider.update', $slider->id) : route('slider.insert') }}" enctype="multipart/form-data">
                                @csrf
                                @if (isset($slider))
                                    @method('patch')
                                @endif
                                <div class="card-body row">
                                    <!-- URL -->
                                    <div class="form-group col-sm-6">
                                        <label for="link_url">Link URL</label>
                                        <input type="url" class="form-control" id="link_url" name="link_url" placeholder="Link URL"
                                            value="{{ isset($slider) ? $slider->link_url : old('link_url') }}">
                                        @error('link_url')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- URL -->
                                    <div class="form-group col-sm-6">
                                        <label for="display_order">Display Order</label>
                                        <input type="number" class="form-control" id="display_order" name="display_order" placeholder="Display Order"
                                            value="{{ isset($slider) ? $slider->display_order : old('display_order') }}">
                                        @error('display_order')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!--  Image -->
                                    <div class="form-group col-sm-6">
                                        <label for="image">Image*</label>
                                        <input type="file" class="form-control" name="image" {{isset($slider)?'':'required'}}/>
                                        @if (isset($slider) && $slider->image)
                                            <img src="{{ asset('storage/sliders/' . $slider->image) }}"class="img-fluid img-thumbnail"
                                                style="height:100px" />
                                        @endif
                                        @error('image')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <input id="submit" type="submit" value="{{ isset($slider) ? 'Edit' : 'Create' }}"
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