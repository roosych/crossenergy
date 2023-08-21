@extends('layouts.app')

@section('title', 'crossenergy | Add driver')

@section('content')
    <div id="content" class="app-content">
        <div class="d-flex align-items-center mb-3">
            <div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('drivers.index')}}">Drivers</a></li>
                    <li class="breadcrumb-item active">Add Driver</li>
                </ul>
                <h3 class="mb-0">Add Driver</h3>
            </div>
            <div class="ms-auto">
                <button id="submitAddDriverForm" type="button" class="btn btn-theme fs-13px"><i class="fa fa-save me-1"></i> Create</button>
            </div>
        </div>
        <form id="addDriverForm" action="{{route('driver.store')}}" method="POST">
            @csrf
            <div class="row gx-4">
                <div class="col-xl-9">
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

                                <div class="row">

                                    <div class="col-12 col-md-1">
                                        <label for="nmb" class="form-label">Number</label>
                                        <input type="text" class="form-control" value="{{ old('number') }}" name="number" id="number">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="fullname" class="form-label">
                                            <span class="text-danger me-1">*</span>Full Name
                                        </label>
                                        <input type="text" class="form-control" value="{{ old('fullname') }}" name="fullname" id="fullname" required="">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="phone" class="form-label">
                                            <span class="text-danger me-1">*</span>Phone
                                        </label>
                                        <input type="text" class="form-control" value="{{ old('phone') }}" name="phone" id="phone" required="">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <label for="owner_id" class="form-label">
                                            Owner
                                        </label>
                                        <select name="owner_id" id="owner_id" class="form-select">
                                            <option value="">Without owner</option>
                                            @foreach($owners as $owner)
                                                <option value="{{$owner->id}}">{{$owner->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-2">
                                        <label for="citizenship" class="form-label">
                                            Citizenship
                                        </label>
                                        <select name="citizenship" id="citizenship" class="form-select">
                                            <option value="">Not chosen</option>
                                            <option value="Resident" {{ old('citizenship') == 'Resident' ? "selected" : "" }}>Resident</option>
                                            <option value="Citizen" {{ old('citizenship') == 'Citizen' ? "selected" : "" }}>Citizen</option>
                                            <option value="NL (illegal)" {{ old('citizenship') == 'NL (illegal)' ? "selected" : "" }}>NL (illegal)</option>
                                            <option value="WA (Legal)" {{ old('citizenship') == 'WA (Legal)' ? "selected" : "" }}>WA (Legal)</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 col-md-4">
                                        <label for="vehicle_type_id" class="form-label"><span class="text-danger me-1">*</span>Vehicle Type</label>
                                        <select name="vehicle_type_id" id="vehicle_type_id" class="form-select">
                                            <option value="">Choose</option>
                                            @foreach($vehicle_types as $type)
                                                <option value="{{$type->id}}" {{ old('vehicle_type_id') == $type->id ? "selected" : "" }}>{{$type->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="capacity" class="form-label">
                                            Capacity
                                        </label>
                                        <input type="text" class="form-control" value="{{ old('capacity') }}" name="capacity" id="capacity" required="">
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label for="dimension" class="form-label">
                                            Dimension
                                        </label>
                                        <input type="text" class="form-control" value="{{ old('dimension') }}" name="dimension" id="dimension" required="">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 col-md-3">
                                        <label for="plate_state" class="form-label">Plate State</label>
                                        <input type="text" class="form-control" value="{{ old('plate_state') }}" name="plate_state" id="plate_state">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="plate_number" class="form-label">Plate Number</label>
                                        <input type="text" class="form-control" value="{{ old('plate_number') }}" name="plate_number" id="plate_number">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="insurance_expdate" class="form-label">Insurance policy exp date</label>
                                        <input type="text" class="form-control datepick" value="{{ old('insurance_expdate') }}" name="insurance_expdate" id="insurance_expdate">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="register_expdate" class="form-label">Registration exp date</label>
                                        <input type="text" class="form-control datepick" value="{{ old('register_expdate') }}" name="register_expdate" id="register_expdate">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="form-check form-switch mb-2">
                                            <input type="hidden" name="availability" value="0">
                                            <input class="form-check-input" name="availability" type="checkbox" value="1" id="availability"{{ old('availability') == 1 ? "checked" : "" }}>
                                            <label class="form-check-label" for="availability">
                                                <span class="ms-12">Availability</span>
                                            </label>
                                        </div>

                                        <div class="form-check form-switch p-10">
                                            <input type="hidden" name="dnu" value="0">
                                            <input class="form-check-input" name="dnu" type="checkbox" value="1" id="dnu" {{ old('dnu') == 1 ? "checked" : "" }}>
                                            <label class="form-check-label" for="dnu">
                                                <span class="ms-12">DNU</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <h6 class="mb-3">Equipment</h6>
                                    <div class="col-12">

                                        @foreach($equipment as $item)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="equipment_{{$item->id}}" name="equipment[]" value="{{$item->id}}">
                                                <label class="form-check-label" for="equipment_{{$item->id}}">{{$item->title}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea class="form-control" name="note" id="note">{{old('note')}}</textarea>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center bg-none fw-bold">
                            Current Location
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-0">
                                <div class="col-12 mb-3">
                                    <label for="zipcode" class="form-label">
                                        Zip Code
                                    </label>
                                    <input type="text" class="form-control" value="" name="zipcode" id="zipcode">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="location" class="form-label">
                                        Location
                                    </label>
                                    <input type="text" class="form-control" value="" name="location" id="location" readonly="">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="latitude" class="form-label">
                                        Latitude
                                    </label>
                                    <input type="text" class="form-control" value="" name="latitude" id="latitude" readonly="">
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="longitude" class="form-label">
                                        Longitude
                                    </label>
                                    <input type="text" class="form-control" value="" name="longitude" id="longitude" readonly="">
                                </div>

                                <div id="errorMsg" class="alert alert-danger" hidden></div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <button id="checkBtn" class="btn btn-theme w-100" onclick="checkZip()">Fill coords</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @endsection

        @push('css')
            <link rel="stylesheet" href="{{asset('assets/css/jquery.datetimepicker.css')}}">
        @endpush

        @push('js')
            <script type="text/javascript" src="{{asset('assets/js/jquery.datetimepicker.js')}}"></script>

            <script>
                $(".datepick").datetimepicker({
                    format: 'd-m-Y',
                    timepicker: false,
                    defaultDate: new Date(),
                });


                $('#submitAddDriverForm').on('click', function(e){
                    $('#addDriverForm').submit();
                    e.preventDefault();
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
            </script>
    @endpush
