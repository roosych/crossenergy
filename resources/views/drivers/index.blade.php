@extends('layouts.app')

@section('title', 'crossenergy | Drivers')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active">Drivers</li>
                </ul>
                <h3 class="mb-0">Drivers</h3>
            </div>
            @can('create', \App\Models\Driver::class)
                <div class="ms-auto">
                    <a href="{{route('driver.add')}}" class="btn btn-theme fs-13px"><i class="fa fa-plus me-1"></i> Add Driver</a>
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

                <table id="example" class="table table-hover mb-0 w-100">
{{--                    <thead>
                    <tr>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Number</th>
                        <th scope="col" class="input">Fullname</th>
                        <th scope="col" class="input">Location</th>
                        <th scope="col">Vehicle</th>
                        <th scope="col">Dimension</th>
                        <th scope="col">Capacity</th>
                        @can('update', \App\Models\Driver::class)
                        <th scope="col">Note</th>
                        @endcan
                        <th></th>
                    </tr>
                    </thead>--}}
                    <thead>
                    <tr>
                        <th >Status</th>
                        <th scope="col" class="text-center">Number</th>
                        <th scope="col" class="text-center">Fullname</th>
                        <th scope="col" class="text-center">Location</th>
                        <th scope="col">Vehicle</th>
                        <th scope="col">Dimension</th>
                        <th scope="col">Capacity</th>
                        @can('update', \App\Models\Driver::class)
                        <th scope="col">Note</th>
                        @endcan
                        <th></th>
                    </tr>

                    <tr>
                        <th ></th>
                        <th class="input" style=""></th>
                        <th class="input">Fullname</th>
                        <th class="input">Location</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        @can('update', \App\Models\Driver::class)
                        <th></th>
                        @endcan

                    </tr>

                    </thead>
                    <tbody style="vertical-align: middle">

                    @forelse($drivers as $driver)
                        <tr>
                            <td style="width: 50px">
                                <div class="form-check form-switch">
                                    <input type="checkbox" class="form-check-input" data-id="{{$driver->id}}" {{$driver->availability ? 'checked' : ''}}>
                                </div>
                                @if($driver->availability == 0 and $driver->future_datetime > now())
                                    <span class="badge_{{$driver->id}} badge bg-danger-100 text-danger py-5px mt-5px">
                                        {{ \Carbon\Carbon::parse($driver->future_datetime)->format('M d,')}}<br>
                                        {{ \Carbon\Carbon::parse($driver->future_datetime)->format('g:i A')}}
                                    </span>
                                @endif
                            </td>
                            <td class="fw-bold" style="width: 50px">
                                {{$driver->number}}
                                @if($driver->dnu)
                                    <span class="badge_{{$driver->id}} badge bg-warning-100 text-danger py-5px">
                                        DNU
                                    </span>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                {{$driver->fullname}}
                                @if($driver->owner)
                                    <br><b>Owner:</b> {{$driver->owner->id}} - <a href="{{route('owner.show', $driver->owner->id)}}" target="_blank">{{$driver->owner->name}}</a>
                                @endif
                            </td>
                            <td class="text-capitalize">
                                @if($driver->location)
                                    {{$driver->location}}<br>{{$driver->zipcode}}
                                @else
                                    <span class="fst-italic text-lowercase" style="color: #c2bcbc">not selected</span>
                                @endif

                            </td>
                            <td class="text-capitalize">
                                @if($driver->vehicle_type)
                                    {{$driver->vehicle_type->title}}
                                @else
                                    <span class="fst-italic text-lowercase" style="color: #c2bcbc">not selected</span>
                                @endif
                            </td>
                            <td>{{$driver->dimension}}</td>
                            <td>{{$driver->capacity}}</td>
                            @can('update', \App\Models\Driver::class)
                            <td style="width: 200px" class="note_{{$driver->id}}">
                                <a id="driver_{{$driver->id}}" href="javascript:void(0);" class="text-decoration-none note_edit" data-title="{{$driver->fullname}}" data-id="{{$driver->id}}" data-value="{{$driver->note}}">
                                    @if(!$driver->note)
                                        <span class="fst-italic" style="border-bottom: 1px dashed #c2bcbc;color: #c2bcbc">write a note</span>
                                    @else
                                        {{$driver->note}}
                                    @endif
                                </a>
                            </td>
                            @endcan
                            <td class="text-end" style="width: 110px">
                                @can('update', \App\Models\Driver::class)
                                    <a href="{{route('driver.show', $driver->id)}}" class="btn btn-theme fw-semibold"><i class="bi bi-eye"></i></a>
                                @endcan
                                <a href="{{route('driver.images', $driver)}}" class="btn btn-theme fw-semibold"><i class="bi bi-image"></i></a>
                                @can('delete', \App\Models\Driver::class)
                                    <a href="javascript:void(0);" class="btn btn-theme fw-semibold delete-item" data-name="{{$driver->fullname}}" data-id="{{$driver->id}}" data-href="{{route('driver.delete', $driver->id)}}"><i class="bi bi-trash3"></i></a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                    <p>Drivers not found</p>
                    @endforelse

                    </tbody>
                </table>
            </div>
    </div>

@include('parts.note_modal')
@include('parts.future_available_modal')
<!-- Delete confirm modal -->
@include('parts.delete_modal')
@endsection

        @push('css')
            <link rel="stylesheet" href="{{asset('assets/css/dataTables.bootstrap5.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/css/jquery.datetimepicker.css')}}">
            <style>
                .dataTables_filter, .dataTables_length {
                    display: none;
                }
            </style>
        @endpush

@push('js')
            <script type="text/javascript" src="{{asset('assets/js/jquery.datetimepicker.js')}}"></script>
            <script type="text/javascript" src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
            <script type="text/javascript" src="{{asset('assets/js/dataTables.bootstrap5.min.js')}}"></script>

            <script>
                $(document).ready(function() {
                    $('#example thead th.input').each( function () {
                        let title = $('#example thead th.input').eq( $(this).index()-1 ).text();
                        $(this).html( '<input type="text" class="form-control form-control-sm" placeholder=" '+title+'" />' );
                    } );

                    let table = $('#example').DataTable({
                        "iDisplayLength": 500,
                        "ordering": false,
                        "paging": false,
                    });

                    // Apply the search
                    table.columns().every( function () {
                        let that = this;

                        $( 'input', this.header() ).on( 'keyup change', function () {
                            that
                                .search( this.value )
                                .draw();
                        } );
                    } );
                } );
            </script>
            <script>
                $('.delete-item').click(function(){
                    $('#deleteConfirmModal').modal('toggle');
                    $('#name').html($(this).data('name'));
                    $('.delete-link').prop('href', $(this).data('href'));
                });

                $('#checkBtn').on('click', function (){
                    checkZip();
                });

                $("#future_date").datetimepicker({
                    minDate: 0,
                    inline: true,
                    format: 'Y-m-d H:i:s',
                    defaultDate: new Date(),
                });

                function checkZip() {

                    const api_key = '{{config('app.zipcode_key')}}';
                    const zip_code = $('#zipcode').val();

                    $('#checkBtn')
                        .attr('disabled', true)
                        .html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>');

                    $.ajax({
                        method: "GET",
                        url: "https://www.zipcodeapi.com/rest/"+api_key+"/info.json/"+zip_code+"/degrees",
                        success: (result) => {
                            console.log(result)
                            $('#errorMsg').attr('hidden', 'hidden');
                            $('#location').val(result['city'] + ', ' + result['state']);
                            $('#longitude').val(result['lng']);
                            $('#latitude').val(result['lat']);
                            $('#checkBtn')
                                .attr('disabled', false)
                                .html('Fill coords');
                            $('#errorMsg')
                                .attr('hidden', 'hidden')
                            $('#setFutureAvaBtn').removeAttr('hidden',);
                        },
                        error: (error) => {
                            $('#location').val('');
                            $('#longitude').val('');
                            $('#latitude').val('');
                            $('#errorMsg')
                                .removeAttr('hidden')
                                .html('Something went wrong...');
                            $('#checkBtn')
                                .attr('disabled', false)
                                .html('Fill coords');
                        }
                    });
                }

                $('.note_edit').on('click',function(){
                    let id = $(this).attr('data-id');
                    let val = $(this).attr('data-value');
                    $('#noteModal').modal('toggle');

                    $('#note_value').val(val);
                    $('#driver_id').val(id);

                })

                let token = $('meta[name="csrf-token"]').attr('content');
                let url = "{{route('driver.note')}}";

                $('#save_note').on('click', function (e) {
                    e.preventDefault();

                    $.ajax({
                        method: 'POST',
                        url: url,
                        data: {_token: token, id: $('#driver_id').val(), note: $('#note_value').val(),},
                        success: (response) => {
                            if (response.msg === 'success'){

                                if(response.data.note != null){
                                    $('#driver_'+response.data.id).html(response.data.note);
                                } else {
                                    $('#driver_'+response.data.id).html('<a id="driver_'+response.data.id+'" href="javascript:void(0);" class="note_edit" data-title="'+response.data.fullname+'" data-id="'+response.data.id+'" data-value="'+response.data.note+'"> <span class="fst-italic" style="border-bottom: 1px dashed #c2bcbc;color: #c2bcbc">write a note</span> </a>');
                                }


                                $('#noteModal').modal('toggle');

                                $("#noteModal").on("hidden.bs.modal", function () {
                                    $('#note_value').val(response.data.note);
                                    $('#driver_id').val(response.data.id);
                                });

                            }
                        }
                    });
                })

            </script>
            <script>
                $(".form-check-input").change(function() {

                    let token = $('meta[name="csrf-token"]').attr('content');
                    let id = $(this).data("id");
                    let url = "{{route('driver.status')}}";

                    console.log(id)

                    if (this.checked) {
                        //set status true if switch on
                        $.ajax({
                            method: 'POST',
                            url: url,
                            data: {_token: token, id: id, availability: 1,},
                            success: (response) => {
                                if (response.msg === 'success'){
                                    $('.badge_' + id).html('');
                                }
                            }
                        });
                    } else {
                        let modal = $('#future_ava_modal');
                        modal.modal('show');

                        // let data = new FormData();
                        // console.log(data);

                        $('#setFutureAvaBtn').click(function () {
                            let form = $('#futureAvaForm');
                            $(this)
                                .attr('disabled', true)
                                .html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>');

                            // convert serializeArray to object
                            let data = {'_token': token};
                            form.serializeArray().map(function(x){data[x.name] = x.value;});

                            //console.log(data);

                            $.ajax({
                                method: 'POST',
                                url: '{{route('driver.availability', '')}}' + "/" + id,
                                data: data,
                                success: (response) => {
                                    console.log(response);
                                    modal.modal('hide');
                                    location.reload();

                                    $(this)
                                        .attr('disabled', false)
                                        .html('Save future availability data');
                                },
                                error: (response) => {
                                    console.log(response);
                                    $('#setFutureAvaBtn')
                                        .attr('disabled', false)
                                        .html('Save future availability data');
                                    $('#avaFormError').removeAttr('hidden');
                                }
                            });
                        });

                        // set status false when modal closed
                        modal.on('hidden.bs.modal', function () {
                            $.ajax({
                                method: 'POST',
                                url: url,
                                data: {_token: token, id: id, availability: 0,},
                                success: (response) => {
                                    //console.log(response);
                                    if (response.msg === 'success'){
                                        console.log('modal close and status = 0')
                                    }
                                }
                            });
                        })
                    }


                });
            </script>
@endpush
