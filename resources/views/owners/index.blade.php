@extends('layouts.app')

@section('title', 'crossenergy | Owners')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Owners</li>
                </ul>
                <h3 class="mb-0">Owners</h3>
            </div>

            @can('create', \App\Models\Owner::class)
                <div class="ms-auto">
                    <a href="javascript:void(0);" class="btn btn-theme add-owner"><i class="fa fa-plus me-1"></i> Add Owner</a>
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
                        <th scope="col">Number</th>
                        <th scope="col">Name</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Drivers</th>
{{--                        @can('update', \App\Models\Owner::class)--}}
{{--                        <th scope="col">Drivers visibility</th>--}}
{{--                        @endcan--}}
                        <th></th>
                    </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                    @forelse($owners as $owner)
                        <tr>
                            <th scope="row">{{$loop->index + 1}}</th>
                            <td class="fw-bold">{{$owner->number}}</td>
                            <td class="text-capitalize">{{$owner->name}}</td>
                            <td>{{$owner->phone}}</td>
                            <td>{{$owner->drivers->count()}}</td>

                            <td class="text-end">
                                @can('update', \App\Models\Owner::class)
                                    <a href="{{route('owner.show', $owner->id)}}" class="btn btn-theme fw-semibold"><i class="bi bi-eye"></i></a>
                                @endcan
                                @can('delete', \App\Models\Owner::class)
                                    <button type="button" class="btn btn-theme fw-semibold delete-item" data-name="{{$owner->name}}" data-id="{{$owner->id}}" data-href="{{route('owner.delete', $owner->id)}}"><i class="bi bi-trash3"></i></button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                    <p>Owners not found!</p>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete confirm modal -->
        @include('parts.delete_modal')

        <!-- Add Modal -->
        <div class="modal fade" id="addOwnerModal" tabindex="-1" aria-labelledby="addOwnerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="addOwnerModalLabel">Add owner</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addOwnerForm">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="number">Number</label>
                                <input type="text" class="form-control" id="number" name="number">
                            </div>
                        </form>

                        <div id="addOwnerFormErrors"></div>
                    </div>
                    <div class="modal-footer">
                        <button id="ownerFormSubmit" type="button" class="btn btn-primary delete-link">Save</button>
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

                //add owner
                let form = $('#addOwnerForm');
                let modal = $('#addOwnerModal');

                $('.add-owner').click(function (){
                    modal.modal('toggle');
                });

                modal.on('hidden.bs.modal', function () {
                    form.trigger('reset');
                    $('#addOwnerFormErrors').html('');
                })

                $('#ownerFormSubmit').click(function(e){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                        }
                    });

                    $.ajax({
                        url: "{{route('owner.store')}}",
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

                            $('#addOwnerFormErrors').html(errorsHtml);
                        },
                    });
                });
            </script>

    @endpush
