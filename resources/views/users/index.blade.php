@extends('layouts.app')

@section('title', 'crossenergy | Users')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ul>
                <h3 class="mb-0">Users</h3>
            </div>

            @can('create', \App\Models\User::class)
                <div class="ms-auto">
                    <a href="{{route('user.add')}}" class="btn btn-theme fs-13px"><i class="fa fa-plus me-1"></i> Add User</a>
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
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Last login</th>
                        @can('update', \App\Models\User::class)
                            <th scope="col">Status</th>
                        @endcan
                        <th></th>
                    </tr>
                    </thead>
                    <tbody style="vertical-align: middle">
                        @foreach($users as $user)
                            <tr>
                                <th scope="row">{{$loop->index + 1}}</th>
                                <td class="text-capitalize">{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->last_login ? date('d-m-Y H:i:s', strtotime($user->last_login)) : 'never'}}</td>
                                @can('update', \App\Models\User::class)
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" data-id="{{$user->id}}" id="{{$user->id}}" {{$user->active ? 'checked' : ''}}>
                                        </div>
                                    </td>
                                @endcan

                                <td class="text-end">
                                    @can('update', \App\Models\User::class)
                                        <a href="{{route('user.show', $user)}}" class="btn btn-theme fw-semibold"><i class="bi bi-eye"></i></a>
                                    @endcan
                                    @can('delete', \App\Models\User::class)
                                        <button type="button" class="btn btn-theme fw-semibold delete-user" data-name="{{$user->name}}" data-id="{{$user->id}}" data-href="{{route('user.delete', $user->id)}}"><i class="bi bi-trash3"></i></button>
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

@endsection

@push('js')
    <script>
        $('.delete-user').click(function(){
            $('#deleteConfirmModal').modal('toggle');
            $('#name').html($(this).data('name'));
            $('.delete-link').prop('href', $(this).data('href'));
        });

        //change user status
        $(".form-check-input").change(function() {
            let token = $('meta[name="csrf-token"]').attr('content');
            let active;
            this.checked ? active = 1 : active = 0;

            $.post('{{route('user.status')}}', {id: this.id, active: active, _token: token});
        });
    </script>
@endpush
