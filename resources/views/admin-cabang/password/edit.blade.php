@extends('layouts.admin-cabang')

@section('title')
    Ganti Password
@endsection

@section('content')
    <div class="page-header mb-4">

        <div class="row align-items-center">

            <div class="col">

                <div class="page-pretitle">
                    Account
                </div>

                <h2 class="page-title">
                    Ganti Password
                </h2>

            </div>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="card">

                <div class="card-body">

                    <form method="POST" action="{{ route('admin-cabang.password.update') }}">

                        @csrf
                        @method('PATCH')

                        <div class="mb-3">

                            <label class="form-label">
                                Password Lama
                            </label>

                            <input type="password" name="current_password" class="form-control" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Password Baru
                            </label>

                            <input type="password" name="password" class="form-control" minlength="8" required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Konfirmasi Password Baru
                            </label>

                            <input type="password" name="password_confirmation" class="form-control" minlength="8"
                                required>

                        </div>

                        <div class="text-end">

                            <button type="submit" class="btn btn-primary">

                                Simpan Password

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
