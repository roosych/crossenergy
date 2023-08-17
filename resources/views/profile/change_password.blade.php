@extends('layouts.app')
@section('title', 'crossenergy | Change password')
@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Change password</li>
                </ul>
                <h3 class="mb-0">Change password</h3>
            </div>
        </div>
        <form id="addUserForm" action="{{route('profile.update-password')}}" method="POST">
            @csrf
            <div class="row gx-4">
                <div class="col-xl-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Set new password
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
                                        <label class="form-label" for="old_password">Old password</label>
                                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="new_password">New password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="new_password_confirmation">Confirm new password</label>
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-theme"><i class="fa fa-save me-1"></i> Save</button>

                        </div>
                    </div>
                </div>
            </div>
        </form>
@endsection
