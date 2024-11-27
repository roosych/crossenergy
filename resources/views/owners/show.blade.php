@extends('layouts.app')

@section('title', 'crossenergy | ' .$owner->name )

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('owners.index')}}">Owners</a></li>
                    <li class="breadcrumb-item active">{{$owner->name}}</li>
                </ul>
                <h3 class="mb-0">{{$owner->name}}</h3>
            </div>
            <div class="ms-auto">
{{--                при удалении овнера - очистить поле owner_id у водителя--}}
                <button type="button" class="btn btn-danger delete-owner" data-name="{{$owner->name}}" data-id="{{$owner->id}}" data-href="{{route('owner.delete', $owner->id)}}"><i class="fa fa-trash me-1"></i> Delete owner</button>
                <button id="submitUserForm" type="button" class="btn btn-theme"><i class="fa fa-save me-1"></i> Save</button>
            </div>
        </div>
        <form id="editOwnerForm" action="{{route('owner.update', $owner)}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row gx-4">
                <div class="col-xl-9">
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

{{--                                <div class="col-lg-1">--}}
{{--                                    <div class="form-group mb-3">--}}
{{--                                        <label class="form-label" for="number">Number</label>--}}
{{--                                        <input type="text" class="form-control" id="number" name="number" value="{{$owner->number}}" required>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="fullname">Fullname</label>
                                        <input type="text" class="form-control" id="fullname" name="name" value="{{$owner->name}}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{$owner->phone}}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="text" class="form-control" id="email" name="email" value="{{$owner->email}}" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="company">Company</label>
                                        <input type="text" class="form-control" id="company" name="company" value="{{$owner->company}}" required>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Drivers
                        </div>
                        <div class="card-body">
                            @if(session()->has('success_add'))
                                <div class="alert alert-success py-2 mb-3">
                                    {{ session()->get('success_add') }}
                                </div>
                            @endif
                            <div class="p-3 bg-body rounded">
                                <div class="form-group mb-0">
                                    @forelse($owner->drivers as $driver)
                                        <div class="row align-items-center">
                                            <div class="col-6 pt-1 pb-1">
                                                <a href="{{route('driver.show', $driver)}}" target="_blank">
                                                    {{$driver->fullname}}
                                                </a>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <div class="ms-auto">
                                                    <a href="{{route('owner.unassign-drivers', $driver)}}" class="btn btn-danger btn-sm "><i class="fa fa-minus fa-fw me-1"></i> Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-2 opacity-1">
                                    @empty
                                    <p class="mb-0">This owner has no drivers</p>
                                    @endforelse
                                </div>
                            </div>
                            @if($ownerless_drivers->count() > 0)
                                <button id="addDriver" type="button" class="btn btn-theme btn-sm mt-3"><i class="fa fa-plus me-1"></i> Add</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </form>

        @include('parts.delete_modal')
        @include('parts.add_drivers_modal')

        @endsection

        @push('css')
            <link rel="stylesheet" href="{{asset('assets/css/bootstrap-duallistbox.css')}}">
        @endpush

        @push('js')
            <script src="{{asset('assets/js/bootstrap-duallistbox.js')}}"></script>
            <script>

                $('#submitUserForm').on('click', function(e){
                    $('#editOwnerForm').submit();
                    e.preventDefault();
                });

                //delete user
                $('.delete-owner').click(function(){
                    $('#deleteConfirmModal').modal('toggle');
                    $('#name').html($(this).data('name'));
                    $('.delete-link').prop('href', $(this).data('href'));
                });

                let element = $('#drivers').bootstrapDualListbox({
                    nonSelectedListLabel: 'Ownerless drivers',
                    selectedListLabel: 'Selected',
                    preserveSelectionOnMove: 'moved',
                    moveOnSelect: true,
                    moveOnDoubleClick: true,
                    filterPlaceHolder: 'Search...',
                    infoText: false,
                });

                //add driver to owner
                let form = $('#driverToOwner');
                let modal = $('#addDriverToOwnerModal');

                $('#addDriver').click(function (){
                    modal.modal('toggle');
                });

                $('#driverToOwnerSubmit').click(function(e){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                        }
                    });

                    $.ajax({
                        url: "{{route('owner.assign-drivers')}}",
                        method: 'post',
                        data: form.serialize(),
                        success: function(data)
                        {
                            console.log(data)
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

                            $('#errorMessages').html(errorsHtml);
                        },
                    });
                });


            </script>


    @endpush
