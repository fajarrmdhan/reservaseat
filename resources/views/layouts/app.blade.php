<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>@yield('title')</title>

        <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

        <style>
            :root {

                --rs-sage: #6A7A54;

            }

            body {
                font-family: 'Inter', sans-serif;
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
        </style>
    </head>

    <body>

        <div class="page">

            @include('partials.sidebar')

            <div class="page-wrapper">

                @include('partials.navbar')


                <div class="page-body">
                    <div class="container-xl">

                        @if (session('success'))
                            <div class="alert alert-success">

                                {{ session('success') }}

                            </div>
                        @endif

                        @yield('content')

                    </div>
                </div>

            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/js/tabler.min.js"></script>

    </body>

</html>
