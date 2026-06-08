@extends('layouts.admin-cabang')

@section('content')

<div class="page-header mb-3">

    <h2 class="page-title">

        Histori Reservasi

    </h2>

</div>

<div class="card mb-3">

    <div class="card-body">

        <form method="GET">

            <div class="row">

                <div class="col-md-4">

                    <label class="form-label">

                        Tanggal

                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="{{ request('tanggal') }}">

                </div>

                <div class="col-md-3 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Filter

                    </button>

                    <a
                        href="{{ route('admin-cabang.histori') }}"
                        class="btn btn-secondary ms-2">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="card">

    <div class="table-responsive">

        <table class="table table-vcenter">

            <thead>

                <tr>

                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Meja</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Catatan</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($reservasis as $reservasi)

                    <tr>

                        <td>

                            {{ $reservasi->kode_reservasi }}

                        </td>

                        <td>

                            {{ $reservasi->user->name ?? '-' }}

                        </td>

                        <td>

                            {{ $reservasi->meja->nomor_meja ?? '-' }}

                        </td>

                        <td>

                            {{ \Carbon\Carbon::parse($reservasi->tanggal_booking)->translatedFormat('d F Y') }}

                        </td>

                        <td>

                            {{ $reservasi->jam_mulai }}

                        </td>

                        <td>

                            {{ $reservasi->catatan ?? '-' }}

                        </td>

                        <td>

                            @if($reservasi->status === 'completed')

                                <span class="badge bg-success text-white">

                                    Completed

                                </span>

                            @elseif($reservasi->status === 'cancelled')

                                <span class="badge bg-danger text-white">

                                    Cancelled

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="text-center">

                            Belum ada histori reservasi

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
