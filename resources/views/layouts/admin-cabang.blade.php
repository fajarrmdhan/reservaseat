<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>

        <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <style>
            :root {

                --rs-sage: #6A7A54;

            }

            html,
            body {
                font-family: 'Inter', sans-serif;
                margin: 0;
                padding: 0;
                width: 100%;
                overflow-x: hidden;
            }

            .page {
                min-height: 100vh;
            }

            .page-wrapper {
                min-width: 0;
            }

            .card {
                border-radius: 16px;
            }

            .navbar-vertical {
                box-shadow: 0 0 20px rgba(0, 0, 0, .05);
            }

            .btn-primary {
                background: var(--rs-sage);
                border-color: var(--rs-sage);
            }

            .btn-primary:hover {
                background: #5c6a49;
                border-color: #5c6a49;
            }

            .nav-link.active {

                background: var(--rs-sage);

                color: white !important;

                border-radius: 10px;
            }

            .offcanvas .navbar-nav {
                padding: 0;
            }

            .offcanvas .nav-link {
                padding: .75rem 1rem;
            }
        </style>
    </head>

    <body>

        <div class="page">

            @include('partials.sidebarcabang')

            <div class="page-wrapper">

                @include('partials.navbarcabang')

                <div class="page-body">
                    <div class="container-xl">

                        @if (session('success'))
                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">

                                {{ session('error') }}

                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif

                        @yield('content')

                    </div>
                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>

        @stack('scripts')

    </body>

</html>
