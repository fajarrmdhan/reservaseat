<!DOCTYPE html>

<html>

    <head>

        <title>Login - ReservaSeat</title>

        <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <style>
            :root {
                --rs-primary: #6A7A54;
                --rs-primary-hover: #586648;
            }

            .btn-reserva {
                background: var(--rs-primary);
                border-color: var(--rs-primary);
                color: white;
            }

            .btn-reserva:hover {
                background: var(--rs-primary-hover);
                border-color: var(--rs-primary-hover);
                color: white;
            }

        </style>

    </head>

    <body class="d-flex flex-column bg-light">

        <div class="page page-center">

            <div class="container container-tight py-4">

                <div class="card card-md shadow-sm border-0">

                    <div class="card-body">

                        <div class="text-center mb-4">

                            <div class="text-center mb-3">

                                <img src="{{ asset('logo_reserva_seat.png') }}" alt="ReservaSeat Logo" width="90">

                            </div>

                            <h2 class="mb-1">

                                ReservaSeat

                            </h2>

                            <p class="text-secondary">

                                Login Dashboard

                            </p>

                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">

                                {{ $errors->first() }}

                            </div>
                        @endif

                        <form method="POST" action="/admin/login">

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">

                                    Email

                                </label>

                                <input type="email" name="email" class="form-control">

                            </div>

                            <div class="mb-3">

                                <label class="form-label">

                                    Password

                                </label>

                                <input type="password" name="password" class="form-control">

                            </div>

                            <div class="form-footer">

                                <button type="submit" class="btn btn-reserva w-100">

                                    Login

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </body>

</html>
