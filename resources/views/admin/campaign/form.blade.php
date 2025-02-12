@extends('adminlte::page')

@section('title', 'Campaigns')

@section('content_header')
    <h1>Campaigns</h1>
@stop

@section('content')

    <div class="card-body">
        <section class="content">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">{{ isset($campaign) ? 'Edit Campaign' : 'Create New Campaign' }}</h3>
                            </div>
                            <form method="POST"
                                action="{{ isset($campaign) ? route('campaigns.update', $campaign->id) : route('campaigns.insert') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @if (isset($campaign))
                                    @method('patch')
                                @endif
                                <div class="card-body row">
                                    <!-- Name -->
                                    <div class="form-group col-sm-12">
                                        <label for="name">Name*</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Name"
                                            value="{{ isset($campaign) ? $campaign->name : old('name') }}" required>
                                        @error('name')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Start Date -->
                                    <div class="form-group col-sm-6">
                                        <label for="start_date">Start Date*</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            value="{{ isset($campaign) ? $campaign->start_date : old('start_date') }}"
                                            required>
                                        @error('start_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- End Date -->
                                    <div class="form-group col-sm-6">
                                        <label for="end_date">End Date*</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            value="{{ isset($campaign) ? $campaign->end_date : old('end_date') }}" required>
                                        @error('end_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="form-group col-sm-4">
                                        <label for="status">Status*</label>
                                        <select id='status' name="status" class="form-control" required>
                                            <option value="">Select a status</option>
                                            <option value="active"
                                                {{ (isset($campaign) && $campaign->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive"
                                                {{ (isset($campaign) && $campaign->status == 'inactive') || old('status') == 'inactive' ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                        @error('status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Background Image -->
                                    <div class="form-group col-sm-4">
                                        <label for="background_image">Background Image</label>
                                        <input type="file" class="form-control" id="background_image" name="background_image">
                                        @error('background_image')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Preview existing image -->
                                        @if(isset($campaign) && $campaign->background_image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $campaign->background_image) }}" alt="Background Image" width="150">
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Color Theme -->
                                    <div class="form-group col-sm-4">
                                        <label for="color_theme">Color Theme</label>
                                        <input type="color" class="form-control" id="color_theme" name="color_theme"
                                            value="{{ isset($campaign) ? $campaign->color_theme : old('color_theme', '#ffffff') }}">
                                        @error('color_theme')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group col-sm-12">
                                        <input id="submit" type="submit"
                                            value="{{ isset($campaign) ? 'Edit' : 'Create' }}" class="btn btn-primary" />
                                    </div>
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
