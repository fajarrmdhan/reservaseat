@extends('layouts.app')

@section('title')
    Admin Cabang
@endsection

@section('content')
    <div class="page-header mb-4">

        <div class="row align-items-center">

            <div class="col">

                <div class="page-pretitle">
                    Management
                </div>

                <h2 class="page-title">
                    Admin Cabang
                </h2>

            </div>

            <div class="col-auto">

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">

                    Tambah Admin

                </button>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="table-responsive">

            <table class="table table-vcenter">

                <thead>

                    <tr>

                        <th>Nama</th>

                        <th>Email</th>

                        <th>Telepon</th>

                        <th>Status</th>

                        <th width="180">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($admins as $admin)
                        <tr>

                            <td>
                                {{ $admin->name }}
                            </td>

                            <td>
                                {{ $admin->email }}
                            </td>

                            <td>
                                {{ $admin->phone }}
                            </td>

                            <td>

                                @if ($admin->status === 'active')
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
                                    data-bs-target="#editAdmin{{ $admin->_id }}">

                                    Edit

                                </button>

                                @if ($admin->status === 'active')
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deactivateAdmin{{ $admin->_id }}">

                                        Nonaktifkan

                                    </button>
                                @endif

                                @if ($admin->status !== 'active')
                                    <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#activateAdmin{{ $admin->_id }}">

                                        Aktifkan

                                    </button>
                                @endif

                            </td>

                        </tr>

                        <x-modal id="editAdmin{{ $admin->_id }}" title="Edit Admin Cabang">

                            <form method="POST" action="/admin-cabangs/{{ $admin->_id }}">

                                @csrf
                                @method('PATCH')

                                <div class="mb-3">

                                    <label class="form-label">
                                        Nama
                                    </label>

                                    <input type="text" name="name" value="{{ $admin->name }}" class="form-control">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Email
                                    </label>

                                    <input type="email" name="email" value="{{ $admin->email }}" class="form-control">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Telepon
                                    </label>

                                    <input type="text" name="phone" value="{{ $admin->phone }}" class="form-control">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label">
                                        Cabang
                                    </label>

                                    <select name="cabang_id" class="form-select">

                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->_id }}"
                                                {{ $admin->cabang_id == $cabang->_id ? 'selected' : '' }}>

                                                {{ $cabang->nama_cabang }}

                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                                <div class="text-end">

                                    <button type="submit" class="btn btn-primary">

                                        Update

                                    </button>
                                    <hr>
                                </div>

                                <div class="mt-3">

                                    <label class="form-label">

                                        Keamanan Akun

                                    </label>

                                    <p class="text-secondary mb-3">

                                        Reset password admin ke password default: password123

                                    </p>

                                    <form method="POST" action="/admin-cabangs/{{ $admin->_id }}/reset-password">

                                        @csrf
                                        @method('PATCH')

                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#resetPassword{{ $admin->_id }}">

                                            Reset Password ke Default?

                                        </button>

                                    </form>

                                </div>

                            </form>

                        </x-modal>

                        @if ($admin->status === 'active')
                            <x-modal id="deactivateAdmin{{ $admin->_id }}" title="Nonaktifkan Admin">

                                <p>

                                    Yakin ingin menonaktifkan

                                    <strong>
                                        {{ $admin->name }}
                                    </strong>?

                                </p>

                                <form method="POST" action="/admin-cabangs/{{ $admin->_id }}/deactivate">

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

                        @if ($admin->status !== 'active')
                            <x-modal id="activateAdmin{{ $admin->_id }}" title="Aktifkan Admin">

                                <p>

                                    Yakin ingin mengaktifkan

                                    <strong>
                                        {{ $admin->name }}
                                    </strong>?

                                </p>

                                <form method="POST" action="/admin-cabangs/{{ $admin->_id }}/activate">

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

                        <x-modal id="resetPassword{{ $admin->_id }}" title="Konfirmasi Reset Password">

                            <div class="alert alert-danger">

                                Password admin ini akan direset menjadi:

                                <strong>
                                    password123
                                </strong>

                            </div>

                            <p>

                                Admin cabang harus menggunakan password baru tersebut
                                saat login berikutnya.

                            </p>

                            <form method="POST" action="/admin-cabangs/{{ $admin->_id }}/reset-password">

                                @csrf
                                @method('PATCH')

                                <div class="text-end">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                                        Batal

                                    </button>

                                    <button type="submit" class="btn btn-danger">

                                        Ya, Reset Password

                                    </button>

                                </div>

                            </form>

                        </x-modal>
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    <x-modal id="modalTambahAdmin" title="Tambah Admin Cabang">

        <form method="POST" action="/admin-cabangs">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Nama
                </label>

                <input type="text" name="name" class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Email
                </label>

                <input type="email" name="email" class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Telepon
                </label>

                <input type="text" name="phone" class="form-control">

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Cabang
                </label>

                <select name="cabang_id" class="form-select">

                    @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->_id }}">

                            {{ $cabang->nama_cabang }}

                        </option>
                    @endforeach

                </select>

            </div>

            <div class="alert alert-info">

                Password awal:
                <strong>password123</strong>

            </div>

            <div class="text-end">

                <button type="submit" class="btn btn-primary">

                    Simpan

                </button>
                <hr>

            </div>

        </form>

    </x-modal>
@endsection
