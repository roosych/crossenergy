@extends('layouts.app')

@section('title', 'crossenergy | Equipments')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Equipments</li>
                </ul>
                <h3 class="mb-0">Equipments</h3>
            </div>

            @can('create', \App\Models\Equipment::class)
                <div class="ms-auto">
                    <a href="javascript:void(0);" class="btn btn-theme fw-semibold add-equipment"><i class="fa fa-plus fa-fw me-1"></i> Add Equipment</a>
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
                        <th scope="col">Drivers</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                    @foreach($equipments as $equipment)
                        <tr>
                            <th scope="row">{{$loop->index + 1}}</th>
                            <td class="text-capitalize">{{$equipment->title}}</td>
                            <td>{{$equipment->drivers->count()}}</td>

                            <td class="text-end">
                                @can('update', \App\Models\Equipment::class)
                                    <a href="{{route('equipment.show', $equipment->id)}}" class="btn btn-theme fw-semibold"><i class="bi bi-eye"></i></a>
                                @endcan
                                @can('delete', \App\Models\Equipment::class)
                                    <button type="button" class="btn btn-theme fw-semibold delete-item" data-name="{{$equipment->title}}" data-id="{{$equipment->id}}" data-href="{{route('equipment.delete', $equipment->id)}}"><i class="bi bi-trash3"></i></button>
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
        <div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addEquipmentModalLabel">Add equipment</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addEquipmentForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label" for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                        </form>

                        <div id="addEquipmentFormErrors"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="equipmentFormSubmit" type="button" class="btn btn-primary delete-link">Save</button>
                    </div>
                </div>
            </div>
        </div>


        @endsection

        @push('js')
            <script>
                $('.delete-item').click(function(){
                    $('#deleteConfirmModal').modal('toggle');
                    $('#name').html($(this).data('name'));
                    $('.delete-link').prop('href', $(this).data('href'));
                });
            </script>

            <script>
                let form = $('#addEquipmentForm');
                let modal = $('#addEquipmentModal');

                $('.add-equipment').click(function (){
                    modal.modal('toggle');
                });

                modal.on('hidden.bs.modal', function () {
                    form.trigger('reset');
                    $('#addEquipmentFormErrors').html('');
                })

                $('#equipmentFormSubmit').click(function(e){
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                            }
                        });

                        $.ajax({
                            url: "{{route('equipment.store')}}",
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

                                $('#addEquipmentFormErrors').html(errorsHtml);
                            },
                        });
                    });
            </script>
    @endpush
