<div class="menu">
    <div class="menu-item dropdown dropdown-mobile-full">
        <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
            <div class="menu-icon"><i class="bi bi-grid-3x3-gap nav-icon"></i></div>
        </a>
        <div class="dropdown-menu fade dropdown-menu-end w-300px text-center p-0 mt-1 overflow-hidden">
            <div class="row gx-0 p-1 pb-0">
                <div class="col-4">
                    <div class="h-100 p-1">
                        <a href="{{route('dashboard')}}" class="dropdown-item p-2 rounded-2">
                            <div class="position-relative pt-1">
                                <i class="bi bi-globe-americas fs-2 d-block text-body text-opacity-50"></i>
                            </div>
                            <div class="small">Map</div>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h-100 p-1">
                        <a href="{{route('drivers.index')}}" class="dropdown-item p-2 rounded-2">
                            <div class="position-relative pt-1">
                                <i class="bi bi-truck fs-2 d-block text-body text-opacity-50"></i>
                            </div>
                            <div class="small">Drivers</div>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h-100 p-1">
                        <a href="{{route('owners.index')}}" class="dropdown-item p-2 rounded-2">
                            <div class="position-relative pt-1">
                                <i class="bi bi-gem fs-2 d-block text-body text-opacity-50"></i>
                            </div>
                            <div class="small">Owners</div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row gx-0 p-1 pt-0">
                <div class="col-4">
                    <div class="h-100 p-1">
                        <a href="{{route('vehicletypes.index')}}" class="dropdown-item p-2 rounded-2">
                            <div class="position-relative pt-1">
                                <i class="bi bi-nut fs-2 d-block text-body text-opacity-50"></i>
                            </div>
                            <div class="small">Vehicle types</div>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h-100 p-1">
                        <a href="{{route('equipment.index')}}" class="dropdown-item p-2 rounded-2">
                            <div class="position-relative pt-1">
                                <i class="bi bi-ui-checks-grid fs-2 d-block text-body text-opacity-50"></i>
                            </div>
                            <div class="small">Equipment</div>
                        </a>
                    </div>
                </div>
                <div class="col-4">
                    <div class="h-100 p-1">
                        <a href="{{route('users.index')}}" class="dropdown-item p-2 rounded-2">
                            <div class="position-relative pt-1">
                                <i class="bi bi-people fs-2 d-block text-body text-opacity-50"></i>
                            </div>
                            <div class="small">Users</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="menu-item dropdown dropdown-mobile-full">
        <a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link">
            <div class="menu-img online">
                <span class="menu-img-bg" style="background-image: url({{asset('assets/img/user/user.jpg')}})"></span>
            </div>
            <div class="menu-text d d-none"><span>test@test.com</span>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end me-lg-3 mt-1 w-200px">
            <a class="dropdown-item d-flex align-items-center" href="{{route('profile.change-password')}}">
                <i class="bi bi-shield-lock fa-fw fa-lg me-3"></i> Change password</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item d-flex align-items-center" href="{{ url('logout') }}">
                <i class="bi bi-box-arrow-right fa-fw fa-lg me-3"></i>
                Logout
            </a>
        </div>
    </div>
</div>


<?php
