<aside class="navbar navbar-vertical navbar-expand-lg d-none d-lg-flex">

    <div class="container-fluid">


        <div class="navbar-brand d-none d-lg-flex align-items-center gap-3 py-3 w-100"> <img
                src="{{ asset('logo_reserva_seat.png') }}" alt="ReservaSeat Logo" width="42">
            <div>
                <div style=" font-size: 1rem; font-weight: 700; line-height: 1.2; "> ReservaSeat </div>
                <div class="text-secondary" style=" font-size: .75rem; "> Admin Panel </div>
            </div>
        </div>
        <div class="border-bottom mb-3 d-none d-lg-block"></div>

        <div class="collapse navbar-collapse" id="sidebar-menu">

            <ul class="navbar-nav">

                @include('partials.sidebar-menu')

            </ul>

        </div>

    </div>

</aside>

<div class="offcanvas offcanvas-start d-lg-none" style="width: 280px;" tabindex="-1" id="mobile-sidebar" data-bs-backdrop="false">

    <div class="offcanvas-header border-bottom">

        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('logo_reserva_seat.png') }}" width="32">

            <span class="fw-bold">
                ReservaSeat
            </span>
        </div>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
        </button>

    </div>

    <div class="offcanvas-body">

        <ul class="navbar-nav">

            @include('partials.sidebar-menu')

        </ul>

    </div>

</div>
