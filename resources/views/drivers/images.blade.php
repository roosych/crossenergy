@extends('layouts.app')

@section('title', 'crossenergy | Driver images')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('drivers.index')}}">Drivers</a></li>
                    <li class="breadcrumb-item"><a href="{{route('driver.show', $driver->id)}}">{{$driver->fullname}}</a></li>
                    <li class="breadcrumb-item active">{{$driver->fullname}}`s car photos</li>
                </ul>
                <h3 class="mb-0">{{$driver->fullname}}</h3>
            </div>
        </div>

            <div class="row gx-4">
                @can('upload', \App\Models\Image::class)
                <div class="col-xl-3">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Upload file
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

                        <form action="{{route('image.store', $driver)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-2">

                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <input type="file" name="images[]" class="form-control" multiple>
                                        <p class="mt-2">max: 2 MB (jpg, jpeg, png)</p>
                                    </div>
                                </div>

                                <div class="col-5">
                                    <button id="" type="submit" class="btn btn-theme"><i class="fa fa-upload me-1"></i> Upload</button>
                                </div>

                            </div>
                        </form>

                        </div>
                    </div>
                </div>
                @endcan

                <div class="col-xl-9">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            {{$driver->fullname}}`s car photos
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @forelse($driver->images as $image)
                                    <div class="col-lg-3">
                                        <div class="card rounded-1">
                                            <img src="/storage/{{$image->filename}}" alt="" class="img-fluid">

                                            @can('delete', \App\Models\Image::class)
                                                <div class="text-center my-3">
                                                    <a href="{{route('image.delete', [$driver, $image])}}" onclick="confirm('Are you sure?')" class="btn btn-danger delete_image" data-id="{{$image->id}}">
                                                        <i class="fa fa-trash-alt me-1"></i> Delete
                                                    </a>
                                                </div>
                                            @endcan

                                        </div>
                                    </div>
                                @empty
                                    <p>No photos</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection
