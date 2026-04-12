@extends('layouts.layout')

@section('title', 'Doctor Dashboard')

@section('content')
        <!-- Page Header -->
        <div class="d-flex align-items-sm-center justify-content-between flex-wrap gap-2 mb-4">
            <div>
                <h4 class="fw-bold mb-0">Doctor Dashboard</h4>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-2">
               <a href="javascript:void(0);" class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#new_appointment"><i class="ti ti-plus me-1"></i>New Appointment</a>
               <a href="javascript:void(0);" class="btn btn-outline-white bg-white d-inline-flex align-items-center"><i class="ti ti-calendar-time me-1"></i>Schedule Availability</a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- row start -->
        <div class="row">
            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                   <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-1">Total Appointments</p>
                                <div class="d-flex align-items-center gap-1">
                                    <h3 class="fw-bold mb-0">{{ $stats['total_appointments'] ?? 0 }}</h3>
                                    <span class="badge fw-medium bg-success flex-shrink-0">+95%</span>
                                </div>
                            </div>
                            <span class="avatar border border-primary text-primary rounded-2 flex-shrink-0"><i class="ti ti-calendar-heart fs-20"></i></span>
                        </div>
                        <div class="d-flex align-items-end">
                            <div id="s-col-5" class="chart-set"></div>
                            <span class="badge fw-medium badge-soft-success flex-shrink-0 ms-2">+21% <i class="ti ti-arrow-up ms-1"></i></span>
                            <p class="ms-1 fs-13 text-truncate">in last 7 Days </p>
                        </div>
                   </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                   <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-1">Online Consultations</p>
                                <div class="d-flex align-items-center gap-1">
                                    <h3 class="fw-bold mb-0">{{ $stats['online_consultations'] ?? 0 }}</h3>
                                    <span class="badge fw-medium bg-danger flex-shrink-0">-15%</span>
                                </div>
                            </div>
                            <span class="avatar border border-danger text-danger rounded-2 flex-shrink-0"><i class="ti ti-users fs-20"></i></span>
                        </div>
                        <div class="d-flex align-items-end">
                            <div id="s-col-6" class="chart-set"></div>
                            <span class="badge fw-medium badge-soft-danger flex-shrink-0 ms-2">+21% <i class="ti ti-arrow-down ms-1"></i></span>
                            <p class="ms-1 fs-13 text-truncate">in last 7 Days </p>
                        </div>
                   </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                   <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="mb-1">Cancelled Appointments</p>
                                <div class="d-flex align-items-center gap-1">
                                    <h3 class="fw-bold mb-0">{{ $stats['cancelled_appointments'] ?? 0 }}</h3>
                                    <span class="badge fw-medium bg-success flex-shrink-0">+45%</span>
                                </div>
                            </div>
                            <span class="avatar border border-success text-success rounded-2 flex-shrink-0"><i class="ti ti-versions fs-20"></i></span>
                        </div>
                        <div class="d-flex align-items-end">
                            <div id="s-col-7" class="chart-set"></div>
                            <span class="badge fw-medium badge-soft-success flex-shrink-0 ms-2">+31% <i class="ti ti-arrow-up ms-1"></i></span>
                            <p class="ms-1 fs-13 text-truncate">in last 7 Days </p>
                        </div>
                   </div>
                </div>
            </div>
            <!-- col end -->
         </div>
        <!-- row end -->

        <!-- row start -->
        <div class="row">
            <!-- col start -->
            <div class="col-xl-4 d-flex">
                 <!-- card start -->
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-truncate">Upcoming Appointments</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Today <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Today</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">This Week</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">This Month</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    {{-- <div class="card-body">
                        @if(isset($stats['upcoming_appointments']) && count($stats['upcoming_appointments']) > 0)
                            @foreach($stats['upcoming_appointments'] as $appointment)
                            <div class="d-flex align-items-center mb-3">
                                <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                    <img src="{{ asset('assets/img/profiles/avatar-06.jpg') }}" alt="img" class="rounded-circle">
                                </a>
                                <div>
                                  <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);" class="fw-semibold">{{ $appointment->patient->name ?? 'Patient' }}</a></h6>
                                  <p class="mb-0 fs-13 text-truncate">#{{ $appointment->id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <h6 class="fs-14 fw-semibold mb-1">{{ $appointment->appointment_type ?? 'General Visit' }}</h6>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-3">
                                <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-calendar-time text-dark me-1"></i>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('D, d M Y') : 'N/A' }}</p>
                                <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock text-dark me-1"></i>{{ $appointment->appointment_time ?? 'N/A' }}</p>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h6 class="fs-13 fw-semibold mb-1">Department</h6>
                                    <p>{{ $appointment->department ?? 'General' }}</p>
                                </div>
                                <div class="col">
                                    <h6 class="fs-13 fw-semibold mb-1">Type</h6>
                                    <p class="text-truncate">{{ $appointment->consultation_type ?? 'Online Consultation' }}</p>
                                </div>
                            </div>
                            <div class="my-3 border-bottom pb-3">
                                <a href="javascript:void(0);" class="btn btn-primary w-100">Start Appointment</a>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <a href="javascript:void(0);" class="btn btn-dark w-100"><i class="ti ti-brand-hipchat me-1"></i>Chat Now</a>
                                <a href="javascript:void(0);" class="btn btn-outline-white w-100"><i class="ti ti-video me-1"></i>Video Consultation</a>
                            </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">No upcoming appointments</p>
                        @endif
                    </div> --}}
                </div>
                <!-- card end -->
            </div>
            <!-- col end -->

             <!-- col start -->
            <div class="col-xl-8 d-flex">
                <!-- card start -->
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Appointments</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Monthly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-center justify-content-end gap-2 mb-1 flex-wrap mb-3">
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-point-filled me-1 fs-18 text-primary"></i>Total Appointments</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-point-filled me-1 fs-18 text-success"></i>Completed Appointments</p>
                        </div>
                        <div class="chart-set" id="s-col-20"></div>
                    </div>
                </div>
                <!-- card end -->
            </div>
            <!-- col end -->
        </div>
        <!-- row end -->

        <!-- row start -->
        <div class="row row-cols-1 row-cols-xl-6 row-cols-md-3 row-cols-sm-2">
            <!-- col start -->
             <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-primary rounded-2 fs-20 d-inline-flex mb-2"><i class="ti ti-user"></i></span>
                        <p class="mb-1 text-truncate">Total Patient</p>
                        <h3 class="fw-bold mb-2">{{ $stats['total_patients'] ?? 0 }}</h3>
                        <p class="mb-0 text-success text-truncate">+31% Last Week</p>
                    </div>
                </div>
             </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-secondary rounded-2 fs-20 d-inline-flex mb-2"><i class="ti ti-video"></i></span>
                        <p class="mb-1 text-truncate">Video Consultation</p>
                        <h3 class="fw-bold mb-2">{{ $stats['video_consultations'] ?? 0 }}</h3>
                        <p class="mb-0 text-danger text-truncate">-21% Last Week</p>
                    </div>
                </div>
             </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-success rounded-2 fs-20 d-inline-flex mb-2"><i class="ti ti-calendar-up"></i></span>
                        <p class="mb-1 text-truncate">Rescheduled</p>
                        <h3 class="fw-bold mb-2">{{ $stats['rescheduled'] ?? 0 }}</h3>
                        <p class="mb-0 text-success text-truncate">+64% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-danger rounded-2 fs-20 d-inline-flex mb-2"><i class="ti ti-checklist"></i></span>
                        <p class="mb-1 text-truncate">Pre Visit Bookings</p>
                        <h3 class="fw-bold mb-2">{{ $stats['pre_visit_bookings'] ?? 0 }}</h3>
                        <p class="mb-0 text-success text-truncate">+38% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-info rounded-2 fs-20 d-inline-flex mb-2"><i class="ti ti-calendar-share"></i></span>
                        <p class="mb-1 text-truncate">Walkin Bookings</p>
                        <h3 class="fw-bold mb-2">{{ $stats['walkin_bookings'] ?? 0 }}</h3>
                        <p class="mb-0 text-success text-truncate">+95% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <span class="avatar bg-soft-success text-success rounded-2 fs-20 d-inline-flex mb-2"><i class="ti ti-carousel-vertical"></i></span>
                        <p class="mb-1 text-truncate">Follow Ups</p>
                        <h3 class="fw-bold mb-2">{{ $stats['follow_ups'] ?? 0 }}</h3>
                        <p class="mb-0 text-success text-truncate">+76% Last Week</p>
                    </div>
                </div>
            </div>
            <!-- col end -->
        </div>
        <!-- row start -->

        <!-- row start -->
        <div class="row">
            <div class="col-12 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Recent Appointments</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Weekly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Table start -->
                        <div class="table-responsive table-nowrap">
                            <table class="table border">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date & Time</th>
                                        <th>Mode</th>
                                        <th>Status</th>
                                        <th>Consultation Fees</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($stats['recent_appointments']) && count($stats['recent_appointments']) > 0)
                                        @foreach($stats['recent_appointments'] as $appointment)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);" class="avatar me-2">
                                                        <img src="{{ asset('assets/img/profiles/avatar-06.jpg') }}" alt="img" class="rounded-circle">
                                                    </a>
                                                    <div>
                                                      <h6 class="fs-14 mb-1"><a href="javascript:void(0);" class="fw-medium">{{ $appointment->patient->name ?? 'Patient' }}</a></h6>
                                                      <p class="mb-0 fs-13">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') : 'N/A' }} - {{ $appointment->appointment_time ?? 'N/A' }}</td>
                                            <td>{{ $appointment->consultation_type ?? 'Online' }}</td>
                                            <td><span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : ($appointment->status == 'cancelled' ? 'danger' : 'warning') }} fw-medium">{{ ucfirst($appointment->status ?? 'scheduled') }}</span></td>
                                            <td class="fw-semibold text-dark">${{ $appointment->consultation_fee ?? 0 }}</td>
                                            <td>
                                                <a href="javascript:void(0);" class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1 me-1">
                                                    <i class="ti ti-calendar-plus"></i>
                                                </a>
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown" class="shadow-sm fs-14 d-inline-flex border rounded-2 p-1">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2">
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="offcanvas" data-bs-target="#edit_appointment"><i class="ti ti-edit me-2"></i>Edit</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#delete_modal"><i class="ti ti-trash me-2"></i>Delete</a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No recent appointments</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Table end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- row end -->

        <!-- row start -->
        <div class="row">
            <!-- col start -->
            <div class="col-xl-4 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Availability</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Trustcare Clinic <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">CureWell Medical Hub</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Trustcare Clinic</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">NovaCare Medical</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">Greeny Medical Clinic</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Mon</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Tue</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Wed</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Thu</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Fri</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 border-bottom pb-2">
                            <p class="text-dark fw-semibold mb-0">Sat</p>
                            <p class="mb-0 d-inline-flex align-items-center"><i class="ti ti-clock me-1"></i>11:00 PM - 12:30 PM</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2 pb-2">
                            <p class="text-dark fw-semibold mb-0">Sun</p>
                            <p class="mb-0 d-inline-flex align-items-center text-danger"><i class="ti ti-clock me-1"></i>Closed</p>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-light w-100 mt-2 fs-13">Edit Availability</a>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-truncate">Appointment Statistics</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Monthly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="circle-chart-2" class="chart-set"></div>
                        <div class="d-flex align-items-center justify-content-center gap-2 mt-3">
                            <div class="text-center">
                                <p class="d-flex align-items-center mb-1 fs-13"><i class="ti ti-circle-filled text-success fs-10 me-1"></i>Completed</p>
                                <h5 class="fw-bold mb-0">{{ $stats['completed_appointments'] ?? 0 }}</h5>
                            </div>
                            <div class="text-center">
                                <p class="d-flex align-items-center mb-1 fs-13"><i class="ti ti-circle-filled text-warning fs-10 me-1"></i>Pending</p>
                                <h5 class="fw-bold mb-0">{{ $stats['pending_appointments'] ?? 0 }}</h5>
                            </div>
                            <div class="text-center">
                                <p class="d-flex align-items-center mb-1 fs-13"><i class="ti ti-circle-filled text-danger fs-10 me-1"></i>Cancelled</p>
                                <h5 class="fw-bold mb-0">{{ $stats['cancelled_appointments'] ?? 0 }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- col end -->

            <!-- col start -->
            <div class="col-xl-4 col-lg-6 d-flex">
                <div class="card shadow-sm flex-fill w-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0">Top Patients</h5>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-sm px-2 border shadow-sm btn-outline-white d-inline-flex align-items-center" data-bs-toggle="dropdown">
                                Weekly <i class="ti ti-chevron-down ms-1"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#">Monthly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Weekly</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">Yearly</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(isset($stats['top_patients']) && count($stats['top_patients']) > 0)
                            @foreach($stats['top_patients'] as $patient)
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="avatar me-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/profiles/avatar-06.jpg') }}" alt="img" class="rounded-circle">
                                    </a>
                                    <div>
                                      <h6 class="fs-14 mb-1 text-truncate"><a href="javascript:void(0);" class="fw-medium">{{ $patient->name ?? 'Patient' }}</a></h6>
                                      <p class="mb-0 fs-13 text-truncate">{{ $patient->phone ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="badge fw-medium badge-soft-primary border border-primary flex-shrink-0">{{ $patient->appointment_count ?? 0 }} Appointments</span>
                            </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">No top patients data</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- col end -->
        </div>
        <!-- row end -->
@endsection