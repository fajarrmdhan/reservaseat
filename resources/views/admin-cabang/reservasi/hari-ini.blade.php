@extends('layouts.admin-cabang')

@section('content')
    <div class="page-header mb-3">
        <h2 class="page-title">
            Reservasi Hari Ini
        </h2>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Meja</th>
                        <th>Jam</th>
                        <th>Catatan</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
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
                                {{ $reservasi->jam_mulai }}
                            </td>
                            <td>
                                {{ $reservasi->catatan ?? '-' }}
                            </td>
                            <td>
                                @if ($reservasi->status === 'pending')
                                    <span class="badge bg-warning text-white">
                                        Pending
                                    </span>
                                    @if ($reservasi->isPendingLate())
                                        <div class="mt-1">
                                            <span class="badge bg-danger text-white">
                                                Terlambat {{ $reservasi->lateMinutes() }} menit
                                            </span>
                                        </div>
                                    @endif
                                @elseif($reservasi->status === 'checked_in')
                                    <span class="badge bg-success text-white">
                                        Checked In
                                    </span>
                                @elseif($reservasi->status === 'completed')
                                    <span class="badge bg-primary text-white">
                                        Completed
                                    </span>
                                @elseif($reservasi->status === 'cancelled')
                                    <span class="badge bg-danger text-white">
                                        Cancelled
                                    </span>
                                    @if ($reservasi->cancel_reason === 'auto_timeout')
                                        <div class="mt-1 text-muted small">
                                            Auto-cancel karena lewat 30 menit
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if ($reservasi->status === 'pending')
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#checkInModal{{ $reservasi->_id }}">
                                        Check In
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#cancelModal{{ $reservasi->_id }}">
                                        Cancel
                                    </button>
                                @elseif($reservasi->status === 'checked_in')
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#completeModal{{ $reservasi->_id }}">
                                        Selesai
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- Modal Check In --}}
                        <x-modal id="checkInModal{{ $reservasi->_id }}" title="Check In Reservasi">
                            <p>
                                Tandai reservasi
                                <strong>{{ $reservasi->kode_reservasi }}</strong>
                                sebagai Check In?
                            </p>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <form method="POST" action="/admin-cabang/reservasi/{{ $reservasi->_id }}/check-in">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success">
                                        Ya, Check In
                                    </button>
                                </form>
                            </div>
                        </x-modal>

                        {{-- Modal Cancel --}}
                        <x-modal id="cancelModal{{ $reservasi->_id }}" title="Batalkan Reservasi">
                            <p>
                                Yakin ingin membatalkan reservasi
                                <strong>{{ $reservasi->kode_reservasi }}</strong>?
                            </p>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <form method="POST" action="/admin-cabang/reservasi/{{ $reservasi->_id }}/cancel">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger">
                                        Ya, Batalkan
                                    </button>
                                </form>
                            </div>
                        </x-modal>

                        {{-- Modal Complete --}}
                        <x-modal id="completeModal{{ $reservasi->_id }}" title="Selesaikan Reservasi">
                            <p>
                                Tandai reservasi
                                <strong>{{ $reservasi->kode_reservasi }}</strong>
                                sebagai selesai?
                            </p>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <form method="POST" action="/admin-cabang/reservasi/{{ $reservasi->_id }}/complete">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-primary">
                                        Ya, Selesaikan
                                    </button>
                                </form>
                            </div>
                        </x-modal>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada reservasi hari ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
