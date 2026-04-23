<div class="sidebar" id="sidebar">
    
    <!-- Start Logo -->
    <div class="sidebar-logo">
        <div>
            <!-- Logo Normal -->
            <a href="{{ route('dashboard') }}" class="logo logo-normal">

            <img src="{{ asset('assets/img/logo.svg') }}" alt="Logo">

            </a>

            <!-- Logo Small -->
            {{-- <a href="{{ route('dashboard') }}" class="logo-small">
                <span class="hospital-icon"></span>
            </a> --}}
        </div>
        <button class="sidenav-toggle-btn btn border-0 p-0 active" id="toggle_btn"> 
            <i class="ti ti-arrow-left text-body"></i>
        </button>

        <!-- Sidebar Menu Close -->
        <button class="sidebar-close">
            <i class="ti ti-x align-middle"></i>
        </button>                
    </div>
    <!-- End Logo -->

    <!-- Sidenav Menu -->
    <div class="sidebar-inner" data-simplebar>                
        <div id="sidebar-menu" class="sidebar-menu">
            
            <!-- User Profile Section -->
            <div class="sidebar-top shadow-sm p-2 rounded-1 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="avatar rounded-circle flex-shrink-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; color: white;">
                            <i class="ti ti-user fs-20"></i>
                        </span>
                        <div class="ms-2">
                            <h6 class="fs-14 fw-semibold mb-0">{{ auth()->user()->name ?? 'User' }}</h6>
                            <p class="fs-13 mb-0 text-capitalize">{{ auth()->user()->role ?? 'guest' }}</p>
                        </div>
                    </div>                           
                </div>
            </div>

            @php
                $userRole = auth()->user()->role ?? null;
            @endphp

            {{-- <ul>
                <!-- MAIN MENU -->
                <li class="menu-title"><span></span></span></li>
                <li>
                    <ul>
                        <!-- Dashboard - Admin Only -->
                        @if($userRole === 'admin')
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            </ul> --}}

            <!-- CLINIC SECTION -->
            <ul>
                <li class="menu-title"><span></span></li>
                <li>
                    <ul>
                        <!-- Doctor Menu -->
                        @if($userRole === 'doctor')
                            <ul>
                                <li class="active">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('appointments.index') }}">
                                        <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('patients.index') }}">
                                        <i class="ti ti-user-heart"></i><span>Patients</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('prescriptions.index') }}">
                                        <i class="ti ti-prescription"></i><span>Prescriptions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('invoices.index') }}">
                                        <i class="ti ti-file-invoice"></i><span>Invoices</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('payments.index') }}">
                                        <i class="ti ti-cards"></i><span>Payments</span>
                                    </a>
                                </li>
                            </ul>
                        @endif

                        <!-- Admin Menu -->
                        @if($userRole === 'admin')
                            <ul>
                                <li class="active">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                    </a>
                                </li>
                                
                                <li>
                                    <a href="{{ route('doctor-verifications') }}">
                                        <i class="ti ti-user-check"></i><span>Doctor Verifications</span>
                                    </a>
                                </li>
                                <li>
                                <li>
                                    <a href="{{ route('doctors.index') }}">
                                        <i class="ti ti-user-plus"></i><span>Doctors</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('patients.index') }}">
                                        <i class="ti ti-user-heart"></i><span>Patients</span>
                                    </a>
                                </li>
                                <li>

                                    <a href="{{ route('appointments.index') }}">
                                        <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('prescriptions.index') }}">
                                        <i class="ti ti-prescription"></i><span>Prescriptions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('invoices.index') }}">
                                        <i class="ti ti-file-invoice"></i><span>Invoices</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('payments.index') }}">
                                        <i class="ti ti-cards"></i><span>Payments</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('ratings.index') }}">
                                        <i class="ti ti-star"></i><span>Ratings</span>
                                    </a>
                                </li>
                            </ul>
                        @endif

                        <!-- Patient Menu -->
                        @if($userRole === 'patient')
                            <ul>
                                <li class="active">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('appointments.index') }}">
                                        <i class="ti ti-calendar-check"></i><span>Appointments</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('doctors.index') }}">
                                        <i class="ti ti-stethoscope"></i><span>Doctors</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('prescriptions.index') }}">
                                        <i class="ti ti-prescription"></i><span>Prescriptions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('invoices.index') }}">
                                        <i class="ti ti-star"></i><span>Invoices</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <i class="ti ti-settings"></i><span>Settings</span>
                                    </a>
                                </li>
                            </ul>
                        @endif

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>