@extends('layouts.admin-cabang')

@section('content')
    <div class="page-header d-print-none mb-3">

        <div class="row align-items-center">

            <div class="col">

                <h2 class="page-title">

                    Manajemen Meja

                </h2>

                <div class="text-secondary">

                    Kelola meja pada cabang Anda

                </div>

            </div>

            <div class="col-auto">

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMejaModal">

                    <i class="bi bi-plus-lg me-1"></i>

                    Tambah Meja

                </button>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="table-responsive">

            <table class="table table-vcenter card-table">

                <thead>

                    <tr>

                        <th>Nomor Meja</th>

                        <th>Kapasitas</th>

                        <th>Status</th>

                        <th width="220">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($mejas as $meja)
                        <tr>

                            <td>

                                {{ $meja->nomor_meja }}

                            </td>

                            <td>

                                {{ $meja->kapasitas }} Orang

                            </td>

                            <td>

                                @if ($meja->status === 'active')
                                    <span class="badge bg-success text-white">

                                        Aktif

                                    </span>
                                @else
                                    <span class="badge bg-danger text-white">

                                        Nonaktif

                                    </span>
                                @endif

                            </td>

                            <td>

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editMejaModal{{ $meja->_id }}">

                                    Edit

                                </button>

                                @if ($meja->status === 'active')
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#nonaktifkanMeja{{ $meja->_id }}">

                                        Nonaktifkan

                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#aktifkanMeja{{ $meja->_id }}">

                                        Aktifkan

                                    </button>

                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#hapusMeja{{ $meja->_id }}">

                                        Hapus

                                    </button>
                                @endif

                            </td>

                        </tr>

                        <div class="modal fade" id="editMejaModal{{ $meja->_id }}" tabindex="-1">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <form method="POST" action="/admin-cabang/meja/{{ $meja->_id }}">

                                        @csrf

                                        @method('PUT')

                                        <div class="modal-header">

                                            <h5 class="modal-title">

                                                Edit Meja

                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal">

                                            </button>

                                        </div>

                                        <div class="modal-body">

                                            <div class="mb-3">

                                                <label class="form-label">

                                                    Nomor Meja

                                                </label>

                                                <input type="text" name="nomor_meja" class="form-control"
                                                    value="{{ $meja->nomor_meja }}" required>

                                            </div>

                                            <div class="mb-3">

                                                <label class="form-label">

                                                    Kapasitas

                                                </label>

                                                <input type="number" name="kapasitas" class="form-control"
                                                    value="{{ $meja->kapasitas }}" min="1" required>

                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <button type="button" class="btn" data-bs-dismiss="modal">

                                                Batal

                                            </button>

                                            <button type="submit" class="btn btn-primary">

                                                Simpan

                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>
                        <x-modal id="nonaktifkanMeja{{ $meja->_id }}" title="Konfirmasi Nonaktifkan Meja">

                            <p>

                                Yakin ingin menonaktifkan meja
                                <strong>{{ $meja->nomor_meja }}</strong> ?

                            </p>

                            <div class="d-flex justify-content-end gap-2">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <form method="POST" action="/admin-cabang/meja/{{ $meja->_id }}/nonaktifkan">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn btn-danger">

                                        Ya, Nonaktifkan

                                    </button>

                                </form>

                            </div>

                        </x-modal>

                        <x-modal id="aktifkanMeja{{ $meja->_id }}" title="Konfirmasi Aktifkan Meja">

                            <p>

                                Aktifkan kembali meja
                                <strong>{{ $meja->nomor_meja }}</strong> ?

                            </p>

                            <div class="d-flex justify-content-end gap-2">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <form method="POST" action="/admin-cabang/meja/{{ $meja->_id }}/aktifkan">

                                    @csrf
                                    @method('PATCH')

                                    <button type="submit" class="btn btn-success">

                                        Ya, Aktifkan

                                    </button>

                                </form>

                            </div>

                        </x-modal>

                        <x-modal id="hapusMeja{{ $meja->_id }}" title="Hapus Meja">

                            <div class="alert alert-danger">

                                Tindakan ini tidak dapat dipulihkan.

                            </div>

                            <p>

                                Hapus meja
                                <strong>{{ $meja->nomor_meja }}</strong>
                                secara permanen?

                            </p>

                            <div class="d-flex justify-content-end gap-2">

                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                    Batal

                                </button>

                                <form method="POST" action="/admin-cabang/meja/{{ $meja->_id }}">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger">

                                        Ya, Hapus

                                    </button>

                                </form>

                            </div>

                        </x-modal>

                    @empty

                        <tr>

                            <td colspan="4" class="text-center text-secondary">

                                Belum ada meja

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="modal fade" id="createMejaModal" tabindex="-1">

        <div class="modal-dialog">

            <div class="modal-content">

                <form method="POST" action="/admin-cabang/meja">

                    @csrf

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Tambah Meja

                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">

                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="mb-3">

                            <label class="form-label">

                                Nomor Meja

                            </label>

                            <input type="text" name="nomor_meja" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Kapasitas

                            </label>

                            <input type="number" name="kapasitas" class="form-control" min="1" required>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn" data-bs-dismiss="modal">

                            Batal

                        </button>

                        <button type="submit" class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection
