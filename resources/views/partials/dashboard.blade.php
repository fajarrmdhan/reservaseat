@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="page-header mb-4">

        <div class="row align-items-center">

            <div class="col">

                <div class="page-pretitle">

                    ReservaSeat

                </div>

                <h2 class="page-title">

                    Dashboard

                </h2>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-3">

            <div class="card">
                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            <div class="text-secondary">
                                Total Cabang
                            </div>

                            <div class="h1 mb-0">
                                {{ $totalCabang }}
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="bi bi-shop fs-1 text-secondary"></i>

                        </div>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card">
                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            <div class="text-secondary">
                                Total Admin Cabang
                            </div>

                            <div class="h1 mb-0">
                                {{ $totalAdminCabang }}
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="bi bi-person-badge fs-1 text-secondary"></i>

                        </div>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card">
                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            <div class="text-secondary">
                                Total Customer
                            </div>

                            <div class="h1 mb-0">
                                {{ $totalCustomer }}
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="bi bi-people-fill fs-1 text-secondary"></i>

                        </div>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            <div class="card">
                <div class="card-body">

                    <div class="row align-items-center">

                        <div class="col">

                            <div class="text-secondary">
                                Total Reservasi
                            </div>

                            <div class="h1 mb-0">
                                {{ $totalReservasi }}
                            </div>

                        </div>

                        <div class="col-auto">

                            <i class="bi bi-calendar-check fs-1 text-secondary"></i>

                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
@endsection
