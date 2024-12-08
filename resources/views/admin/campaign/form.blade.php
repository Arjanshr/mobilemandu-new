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
                                    <div class="form-group col-sm-6">
                                        <label for="start_date">Start Date*</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            placeholder="Name"
                                            value="{{ isset($campaign) ? $campaign->start_date : old('start_date') }}"
                                            required>
                                        @error('start_date')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="end_date">End Date*</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            placeholder="Name"
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
                                                {{ (isset($campaign) && $campaign->status == 'active') || old('status') == '1' ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="inactive"
                                                {{ (isset($campaign) && $campaign->status == 'inactive') || old('status') == '0' ? 'selected' : '' }}>
                                                Inactive
                                            </option>

                                        </select>
                                        @error('status')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <input id="submit" type="submit"
                                            value="{{ isset($campaign) ? 'Edit' : 'Create' }}" class="btn btn-primary" />
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
