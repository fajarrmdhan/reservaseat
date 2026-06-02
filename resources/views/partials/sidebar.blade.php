<aside class="navbar navbar-vertical navbar-expand-lg">

    <div class="container-fluid">

        <div class="navbar-brand d-flex align-items-center gap-3 py-3 w-100"> <img
                src="{{ asset('logo_reserva_seat.png') }}" alt="ReservaSeat Logo" width="42">
            <div>
                <div style=" font-size: 1rem; font-weight: 700; line-height: 1.2; "> ReservaSeat </div>
                <div class="text-secondary" style=" font-size: .75rem; "> Admin Panel </div>
            </div>
        </div>
        <div class="border-bottom mb-3"></div>

        <div class="collapse navbar-collapse show">

            <ul class="navbar-nav">

                <li class="nav-item">

                    <span class="nav-link text-uppercase text-secondary">

                        Main

                    </span>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">

                        <span class="nav-link-icon">

                            <i class="ti ti-layout-dashboard"></i>

                        </span>

                        <span class="nav-link-title">

                            Dashboard

                        </span>

                    </a>

                </li>

                <li class="nav-item mt-3">

                    <span class="nav-link text-uppercase text-secondary">

                        Management

                    </span>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('cabangs*') ? 'active' : '' }}" href="/cabangs">

                        <span class="nav-link-icon">

                            <i class="bi bi-shop"></i>

                        </span>

                        <span class="nav-link-title">

                            Cabang

                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('admin-cabangs*') ? 'active' : '' }}" href="/admin-cabangs">

                        <span class="nav-link-icon">

                            <i class="bi bi-people"></i>

                        </span>

                        <span class="nav-link-title">

                            Admin Cabang

                        </span>

                    </a>

                </li>

                <li class="nav-item mt-3">

                    <span class="nav-link text-uppercase text-secondary">

                        Account

                    </span>

                </li>

                <li class="nav-item">

                    <form method="POST" action="/admin/logout">

                        @csrf

                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</aside>
