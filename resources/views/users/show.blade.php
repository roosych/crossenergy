@extends('layouts.app')

@section('title', 'crossenergy | ' .$user->name )

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                    <li class="breadcrumb-item active">{{$user->name}}</li>
                </ul>
                <h3 class="mb-0">{{$user->name}}</h3>
            </div>
            <div class="ms-auto">
                <button type="button" class="btn btn-danger delete-user" data-name="{{$user->name}}" data-id="{{$user->id}}" data-href="{{route('user.delete', $user->id)}}"><i class="fa fa-trash me-1"></i> Delete user</button>
                <button id="submitUserForm" type="button" class="btn btn-theme"><i class="fa fa-save me-1"></i> Save</button>
            </div>
        </div>
        <form id="editUserForm" action="{{route('user.update', $user)}}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row gx-4">
                <div class="col-xl-6">
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

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="fullname">Fullname</label>
                                        <input type="text" class="form-control" id="fullname" name="name" value="{{$user->name}}" required>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="email">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" required>
                                    </div>
                                </div>

                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-4">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="active" value="0">
                                        <input type="checkbox" class="form-check-input" id="active" name="active" value="1" {{$user->active ? 'checked' : ''}}>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Permissions
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-body rounded">
                                <div class="form-group mb-0">
                                    @foreach($permissions as $permission)
                                        <div class="row align-items-center">
                                            <div class="col-6 pt-1 pb-1">{{$permission->title}}</div>
                                            <div class="col-6 d-flex align-items-center">
                                                <div class="form-check form-switch ms-auto">
                                                    <input type="checkbox" class="form-check-input" id="{{$permission->id}}" name="permissions[]" value="{{$permission->id}}"
                                                        {{is_array($user->permissions->pluck('id')->toArray())
                                                             &&
                                                             in_array($permission->id, $user->permissions->pluck('id')->toArray())
                                                              ? 'checked' : ''}}
                                                    >
                                                    <label class="form-check-label" for="{{$permission->id}}">&nbsp;</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="my-2 opacity-1">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

        @include('parts.delete_modal')

        @endsection

        @push('js')
            <script>
                $('#submitUserForm').on('click', function(e){
                    $('#editUserForm').submit();
                    e.preventDefault();
                });


                //delete user
                $('.delete-user').click(function(){
                    $('#deleteConfirmModal').modal('toggle');
                    $('#name').html($(this).data('name'));
                    $('.delete-link').prop('href', $(this).data('href'));
                });

            </script>


    @endpush
