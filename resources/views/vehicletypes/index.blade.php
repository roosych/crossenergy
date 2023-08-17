@extends('layouts.app')

@section('title', 'crossenergy | Vehicle types')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Vehicle types</li>
                </ul>
                <h3 class="mb-0">Vehicle types</h3>
            </div>

            @can('create', \App\Models\VehicleType::class)
                <div class="ms-auto">
                    <a href="javascript:void(0);" class="btn btn-theme add-equipment"><i class="fa fa-plus me-1"></i> Add Type</a>
                </div>
            @endcan

        </div>

        <div class="card">
            <div class="card-body">

                @if(session()->has('success'))
                    <div class="alert alert-success py-2 mb-3">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Color on map</th>
                        <th scope="col">Drivers</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                    @foreach($types as $type)
                        <tr>
                            <th scope="row">{{$loop->index + 1}}</th>
                            <td class="text-capitalize">{{$type->title}}</td>
                            <td class="fw-bold">
                                <div class="d-flex align-items-center text-uppercase mb-1"><div class="w-30px h-30px rounded me-2" style="background-color: {{$type->color}}"></div> {{$type->color}}</div>
                            </td>
                            <td>
                                {{$type->drivers->count()}}
                            </td>

                            <td class="text-end">
                                @can('update', \App\Models\VehicleType::class)
                                    <a href="{{route('vehicletype.show', $type->id)}}" class="btn btn-theme fw-semibold"><i class="bi bi-eye"></i></a>
                                @endcan
                                @can('delete', \App\Models\VehicleType::class)
                                    <button type="button" class="btn btn-theme fw-semibold delete-item" data-name="{{$type->title}}" data-id="{{$type->id}}" data-href="{{route('vehicletype.delete', $type->id)}}"><i class="bi bi-trash3"></i></button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete confirm modal -->
        @include('parts.delete_modal')

        <!-- Add Modal -->
        <div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addTypeModalLabel">Add type</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addTypeForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="color">Color on map</label>
                                <input type="text" class="form-control colorpicker" id="color" name="color" value="#007aff">
                            </div>
                        </form>

                        <div id="addTypeFormErrors"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="typeFormSubmit" type="button" class="btn btn-primary delete-link">Save</button>
                    </div>
                </div>
            </div>
        </div>

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

                //add
                let form = $('#addTypeForm');
                let modal = $('#addTypeModal');

                $('.add-equipment').click(function (){
                    modal.modal('toggle');
                });

                modal.on('hidden.bs.modal', function () {
                    form.trigger('reset');
                    $('#addTypeFormErrors').html('');
                })

                $('#typeFormSubmit').click(function(e){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                        }
                    });

                    $.ajax({
                        url: "{{route('vehicletype.store')}}",
                        method: 'post',
                        data: form.serialize(),
                        success: function(data)
                        {
                            console.log(data.data.title)
                            form.trigger('reset');
                            modal.modal('toggle');
                            location.reload(); //TODO Убрать перезагрузку
                        },
                        error: function (data)
                        {
                            let errors = data.responseJSON;

                            let errorsHtml = '<div class="alert alert-danger">';

                            $.each( errors.errors, function( key, value ) {
                                errorsHtml += '<p class="mb-0">'+ value + '</p>';
                            });
                            errorsHtml += '</div>';

                            $('#addTypeFormErrors').html(errorsHtml);
                        },
                    });
                });

                //delete
                $('.delete-item').click(function(){
                    $('#deleteConfirmModal').modal('toggle');
                    $('#name').html($(this).data('name'));
                    $('.delete-link').prop('href', $(this).data('href'));
                });
            </script>
    @endpush
