
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'PDMS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Dreams Technologies">
	
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/theme-script.js') }}" type="text/javascript"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
    
    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- Fontawesome CSS -->
	<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">

    <!-- Simplebar CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/simplebar/simplebar.min.css') }}">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="app-style">

    @yield('styles')
    @stack('styles')
</head>

<body>
    <a href="https://preclinic.dreamstechnologies.com/cdn-cgi/content?id=BPNxI9u6Y0hUBp2eLLT84wXhAyUA9__uqK.QrLhfxbw-1775226633.2858348-1.0.1.1-x9WZV70IJejipAjoWDePJ8e2KoNxdwGtvx_rN.VPVNg" aria-hidden="true" rel="nofollow noopener" style="display: none !important; visibility: hidden !important"></a>

    <!-- Begin Wrapper -->
    <div class="main-wrapper">

        <!-- Topbar Start -->
        @include('layouts._partials.topbar')
        <!-- Topbar End -->

        <!-- Search Modal -->
        <div class="modal fade" id="searchModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content bg-transparent">
                    <div class="card shadow-none mb-0">
                        <div class="px-3 py-2 d-flex flex-row align-items-center" id="search-top">
                            <i class="ti ti-search fs-22"></i>
                            <input type="search" class="form-control border-0" placeholder="Search">
                            <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x fs-22"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidenav Menu Start -->
        @include('layouts._partials.sidebar')
        <!-- Sidenav Menu End -->

        <!-- ========================
			Start Page Content
		========================= -->
         
        <div class="page-wrapper">

            <!-- Start Content -->
            <div class="content pb-0">
                @if(Auth::check() && Auth::user()->role === 'patient' && !optional(Auth::user()->patient)->is_payment_method_verified)
                    <div class="alert alert-danger rounded-3 mb-4 d-flex align-items-start" role="alert">
                        <i class="ti ti-alert-circle fs-4 me-3"></i>
                        <div>
                            <strong>Payment verification pending.</strong> Your patient account is registered, but payment verification is still off. You can complete it now.
                               <a href="{{ route('patient.payment.page') }}"
                                    class="btn btn-sm btn-outline-light ms-2 py-1 px-2"
                                    style="font-size: 12px;">
                                        Verify Now
                                </a>
                        </div>
                    </div>
                @endif
                @if(Auth::check() && Auth::user()->role === 'doctor' && !optional(Auth::user()->doctor)->is_verified)
                    <div class="alert alert-danger rounded-3 mb-4 d-flex align-items-start" role="alert">
                        <i class="ti ti-alert-circle fs-4 me-3"></i>
                         <div>
                            <strong>Certificate verification pending.</strong> Your doctor account is registered, but certificate verification is still off. You can complete it now.
                               <a href=""
                                    class="btn btn-sm btn-outline-light ms-2 py-1 px-2"
                                    style="font-size: 12px;">
                                        Verify Now
                                </a>
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
            <!-- End Content -->

            <!-- Footer Start -->
            @include('layouts._partials.footer')
            <!-- Footer End -->

        </div>

        <!-- ========================
			End Page Content
		========================= -->

    </div>
    <!-- End Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>    

	<!-- Simplebar JS -->
	<script src="{{ asset('assets/plugins/simplebar/simplebar.min.js') }}" type="text/javascript"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}" type="text/javascript"></script>
    
	<!-- Daterangepikcer JS -->
	<script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>
	<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js" type="text/javascript"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>

    @yield('scripts')
    @stack('scripts')

<!-- Mirrored from preclinic.dreamstechnologies.com/html/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 03 Apr 2026 14:31:44 GMT -->
</html>
