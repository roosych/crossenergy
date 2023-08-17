@extends('layouts.app')

@section('title', 'crossenergy | ' .$vehicle->title )

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('vehicletypes.index')}}">Vehicle Types</a></li>
                    <li class="breadcrumb-item active">{{$vehicle->title}}</li>
                </ul>
                <h3 class="mb-0">{{$vehicle->title}}</h3>
            </div>
            <div class="ms-auto">
                <button type="button" class="btn btn-danger delete-item" data-name="{{$vehicle->title}}" data-id="{{$vehicle->id}}" data-href="{{route('vehicletype.delete', $vehicle)}}"><i class="fa fa-trash me-1"></i> Delete</button>
                <button id="submitTypeForm" type="button" class="btn btn-theme"><i class="fa fa-save me-1"></i> Save</button>
            </div>
        </div>
        <form id="editTypeForm" action="{{route('vehicletype.update', $vehicle)}}" method="POST">
            @csrf
            <div class="row gx-4">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Information
                        </div>
                        <div class="card-body">

                            @if(session()->has('success'))
                                <div class="alert alert-success py-2 mb-3">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger py-2 mb-3">
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <div class="row mb-2">

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{$vehicle->title}}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="color">Color on map</label>
                                        <input type="text" class="form-control colorpicker" id="color" name="color" value="{{$vehicle->color}}">
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </form>

        @include('parts.delete_modal')

        @endsection
        @push('css')
            <link rel="stylesheet" href="{{asset('assets/css/spectrum.min.css')}}">
        @endpush

        @push('js')
            <script src="{{asset('assets/js/spectrum.min.js')}}"></script>

            <script>
                //color
                $('.colorpicker').spectrum({
                    "showInput": true
                });

                $('#submitTypeForm').on('click', function(e){
                    $('#editTypeForm').submit();
                    e.preventDefault();
                });

                //delete
                $('.delete-item').click(function(){
                    $('#deleteConfirmModal').modal('toggle');
                    $('#name').html($(this).data('name'));
                    $('.delete-link').prop('href', $(this).data('href'));
                });

            </script>


    @endpush
