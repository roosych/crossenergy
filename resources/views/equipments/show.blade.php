@extends('layouts.app')

@section('title', 'crossenergy | ' .$equipment->title )

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('equipment.index')}}">Equipments</a></li>
                    <li class="breadcrumb-item active">{{$equipment->title}}</li>
                </ul>
                <h3 class="mb-0">{{$equipment->title}}</h3>
            </div>
            <div class="ms-auto">
                <button type="button" class="btn btn-danger fw-semibold delete-item" data-name="{{$equipment->title}}" data-id="{{$equipment->id}}" data-href="{{route('equipment.delete', $equipment->id)}}"><i class="fa fa-trash fa-fw me-1"></i> Delete</button>
                <button id="submitEquipmentForm" type="button" class="btn btn-theme fw-semibold"><i class="fa fa-save fa-fw me-1"></i> Save</button>
            </div>
        </div>
        <form id="editEquipmentForm" action="{{route('equipment.update', $equipment)}}" enctype="multipart/form-data" method="POST">
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
                                        <input type="text" class="form-control" id="title" name="title" value="{{$equipment->title}}" required>
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

        @push('js')
            <script>
                $('#submitEquipmentForm').on('click', function(e){
                    $('#editEquipmentForm').submit();
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
