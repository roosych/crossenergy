@extends('layouts.app')

@section('title', 'crossenergy | Add User')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('users.index')}}">Users</a></li>
                    <li class="breadcrumb-item active">Add User</li>
                </ul>
                <h3 class="mb-0">Add User</h3>
            </div>
            <div class="ms-auto">
                <button id="submitUserForm" type="button" class="btn btn-theme fs-13px"><i class="fa fa-save me-1"></i> Create</button>
            </div>
        </div>
        <form id="addUserForm" action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row gx-4">
                <div class="col-xl-6">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Personal Information
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger py-2 mb-3">
                                    @foreach ($errors->all() as $error)
                                        <p class="mb-0">{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <div class="alert alert-info py-2">
                                Default password <strong>"p@ssw0rd"</strong>. It is recommended to change after login
                            </div>
                                <div class="row mb-2">

                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="fullname">Fullname</label>
                                            <input type="text" class="form-control" id="fullname" name="name" value="{{@old('name')}}" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">Email address</label>
                                            <input type="email" class="form-control" id="email" placeholder="name@example.com" value="{{@old('email')}}" name="email" required>
                                        </div>
                                    </div>

                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-4">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="active" value="0">
                                            <input type="checkbox" class="form-check-input" id="active" name="active" value="1">
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
                            User Permissions
                        </div>
                        <div class="card-body">
                            <div class="p-3 bg-body rounded">
                                <div class="form-group mb-0">
                                    @foreach($permissions as $permission)
                                        <div class="row align-items-center">
                                            <div class="col-6 pt-1 pb-1">{{$permission->title}}</div>
                                            <div class="col-6 d-flex align-items-center">
                                                <div class="form-check form-switch ms-auto">
                                                    <input type="checkbox" class="form-check-input" id="{{$permission->id}}" name="permissions[]" value="{{$permission->id}}">
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
@endsection

@push('js')
    <script>
        $('#submitUserForm').on('click', function(e){
            $('#addUserForm').submit();
            e.preventDefault();
        });



    </script>
@endpush
