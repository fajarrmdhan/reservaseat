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

            <button
                class="btn btn-primary">

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

                @foreach($admins as $admin)

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

                        @if(
                            $admin->status === 'active'
                        )

                            <span
                                class="badge bg-success text-white">

                                Aktif

                            </span>

                        @else

                            <span
                                class="badge bg-danger text-white">

                                Nonaktif

                            </span>

                        @endif

                    </td>

                    <td>

                        <button
                            class="btn btn-sm btn-warning">

                            Edit

                        </button>

                        @if(
                            $admin->status === 'active'
                        )

                            <button
                                class="btn btn-sm btn-danger">

                                Nonaktifkan

                            </button>

                        @else

                            <button
                                class="btn btn-sm btn-success">

                                Aktifkan

                            </button>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection