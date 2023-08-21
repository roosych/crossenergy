
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>crossenergy | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content>
    <meta name="author" content>

    <link href="{{asset('assets/css/vendor.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet">

</head>
<body class="pace-top">

<div id="app" class="app app-full-height app-without-header">

    <div class="login">

        <div class="login-content">


            <form method="POST" action="{{ route('login') }}">
                @csrf
                <h1 class="text-center">Sign In</h1>

                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input id="email" type="email" class="form-control form-control-lg fs-body @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-4">
                    <div class="d-flex">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
{{--                        <a href="{{ route('password.request') }}" class="ms-auto text-body text-decoration-none text-opacity-50">Forgot password?</a>--}}
                    </div>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-theme btn-lg d-block w-100 fw-semibold mb-3">Sign In</button>
            </form>
            @if(session()->has('error'))
                <div class="alert alert-danger py-2 mb-3 text-center">
                    {{ session()->get('error') }}
                </div>
            @endif
        </div>

    </div>


    <a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade"><i class="fa fa-arrow-up"></i></a>


    @include('parts.theme-panel')

</div>


<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>

</body>
</html>

