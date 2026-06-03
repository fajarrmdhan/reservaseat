@extends('layouts.admin-cabang')

@section('content')

<div class="page-header mb-3">

    <h2 class="page-title">

        Dashboard Admin Cabang

    </h2>

</div>

<div class="row g-3">

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">

                    Reservasi Hari Ini

                </div>

                <div class="h1 mb-0">

                    {{ $reservasiHariIni }}

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">

                    Reservasi Aktif

                </div>

                <div class="h1 mb-0">

                    {{ $reservasiAktif }}

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">

                    Reservasi Selesai

                </div>

                <div class="h1 mb-0">

                    {{ $reservasiSelesai }}

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="text-secondary">

                    Total Meja

                </div>

                <div class="h1 mb-0">

                    {{ $totalMeja }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection