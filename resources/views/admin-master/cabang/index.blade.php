@extends('layouts.app')

@section('title')
    Cabang
@endsection

@section('content')
    <div class="page-header mb-4">

        <div class="row align-items-center">

            <div class="col">

                <div class="page-pretitle">
                    Management
                </div>

                <h2 class="page-title">
                    Cabang
                </h2>

            </div>

            <div class="col-auto">

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahCabang">

                    Tambah Cabang


                </button>


            </div>

        </div>

    </div>

    <div class="card">

        <div class="table-responsive">

            <table class="table table-vcenter">

                <thead>

                    <tr>

                        <th>Nama Cabang</th>

                        <th>Alamat</th>

                        <th>Jam Operasional</th>

                        <th>Status</th>

                        <th width="180">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($cabangs as $cabang)
                        <tr>

                            <td>
                                {{ $cabang->nama_cabang }}
                            </td>

                            <td>
                                {{ $cabang->alamat }}
                            </td>

                            <td>
                                {{ $cabang->jam_buka }}
                                -
                                {{ $cabang->jam_tutup }}
                            </td>

                            <td>

                                @if ($cabang->status === 'active')
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
                                    data-bs-target="#editCabang{{ $cabang->_id }}">

                                    Edit

                                </button>

                                @if ($cabang->status === 'active')
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deactivateCabang{{ $cabang->_id }}">

                                        Nonaktifkan

                                    </button>
                                @else
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#activateCabang{{ $cabang->_id }}">

                                        Aktifkan

                                    </button>
                                @endif

                            </td>

                        </tr>

                        <x-modal id="modalTambahCabang" title="Tambah Cabang">

                            <form method="POST" action="/cabangs">

                                @csrf

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama Cabang
                                    </label>

                                    <input type="text" name="nama_cabang" class="form-control">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Alamat
                                    </label>

                                    <textarea name="alamat" class="form-control"></textarea>

                                </div>

                                <div class="row">

                                    <div class="col">

                                        <label class="form-label">
                                            Jam Buka
                                        </label>

                                        <input type="time" name="jam_buka" class="form-control">

                                    </div>

                                    <div class="col">

                                        <label class="form-label">
                                            Jam Tutup
                                        </label>

                                        <input type="time" name="jam_tutup" class="form-control">

                                    </div>

                                </div>

                                <div class="mt-4 text-end">

                                    <button type="submit" class="btn btn-primary">

                                        Simpan

                                    </button>

                                </div>

                            </form>

                        </x-modal>

                        <x-modal id="editCabang{{ $cabang->_id }}" title="Edit Cabang">

                            <form method="POST" action="/cabangs/{{ $cabang->_id }}">

                                @csrf
                                @method('PATCH')

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama Cabang
                                    </label>

                                    <input type="text" name="nama_cabang" value="{{ $cabang->nama_cabang }}"
                                        class="form-control">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Alamat
                                    </label>

                                    <textarea name="alamat" class="form-control">{{ $cabang->alamat }}</textarea>

                                </div>

                                <div class="row">

                                    <div class="col">

                                        <label class="form-label">
                                            Jam Buka
                                        </label>

                                        <input type="time" name="jam_buka" value="{{ $cabang->jam_buka }}"
                                            class="form-control">

                                    </div>

                                    <div class="col">

                                        <label class="form-label">
                                            Jam Tutup
                                        </label>

                                        <input type="time" name="jam_tutup" value="{{ $cabang->jam_tutup }}"
                                            class="form-control">

                                    </div>

                                </div>

                                <div class="mt-4 text-end">

                                    <button type="submit" class="btn btn-primary">

                                        Update

                                    </button>

                                </div>

                            </form>

                        </x-modal>

                        @if ($cabang->status === 'active')
                            <x-modal id="deactivateCabang{{ $cabang->_id }}" title="Nonaktifkan Cabang">

                                <p>

                                    Apakah Anda yakin ingin
                                    menonaktifkan cabang

                                    <strong>
                                        {{ $cabang->nama_cabang }}
                                    </strong>?

                                </p>

                                <form method="POST" action="/cabangs/{{ $cabang->_id }}/deactivate">

                                    @csrf
                                    @method('PATCH')

                                    <div class="text-end">

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                            Batal

                                        </button>

                                        <button type="submit" class="btn btn-danger">

                                            Nonaktifkan

                                        </button>

                                    </div>

                                </form>

                            </x-modal>
                        @endif

                        @if ($cabang->status !== 'active')
                            <x-modal id="activateCabang{{ $cabang->_id }}" title="Aktifkan Cabang">

                                <p>

                                    Apakah Anda yakin ingin
                                    mengaktifkan cabang

                                    <strong>
                                        {{ $cabang->nama_cabang }}
                                    </strong>?

                                </p>

                                <form method="POST" action="/cabangs/{{ $cabang->_id }}/activate">

                                    @csrf
                                    @method('PATCH')

                                    <div class="text-end">

                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                            Batal

                                        </button>

                                        <button type="submit" class="btn btn-success">

                                            Aktifkan

                                        </button>

                                    </div>

                                </form>

                            </x-modal>
                        @endif
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection
