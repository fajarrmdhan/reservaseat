<aside class="navbar navbar-vertical navbar-expand-lg">

    <div class="container-fluid">

        <div class="navbar-brand d-flex align-items-center gap-3 py-3 w-100">

            <img src="{{ asset('logo_reserva_seat.png') }}" alt="ReservaSeat Logo" width="42">

            <div>

                <div
                    style="
                        font-size: 1rem;
                        font-weight: 700;
                        line-height: 1.2;
                    ">

                    ReservaSeat

                </div>

                <div class="text-secondary" style="
                        font-size: .75rem;
                    ">

                    Admin Cabang

                </div>

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

                    <a class="nav-link {{ request()->is('admin-cabang/dashboard') ? 'active' : '' }}"
                        href="/admin-cabang/dashboard">

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

                        Operasional

                    </span>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('admin-cabang/reservasi-hari-ini*') ? 'active' : '' }}"
                        href="/admin-cabang/reservasi-hari-ini">

                        <span class="nav-link-icon">

                            <i class="bi bi-calendar-check"></i>

                        </span>

                        <span class="nav-link-title">

                            Reservasi Hari Ini

                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('admin-cabang/histori-reservasi') ? 'active' : '' }}"
                        href="/admin-cabang/histori-reservasi">

                        <span class="nav-link-icon">

                            <i class="ti ti-history"></i>

                        </span>

                        <span class="nav-link-title">

                            Histori Reservasi

                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('admin-cabang/mejas*') ? 'active' : '' }}"
                        href="/admin-cabang/meja">

                        <span class="nav-link-icon">

                            <i class="bi bi-grid-3x3-gap"></i>

                        </span>

                        <span class="nav-link-title">

                            Manajemen Meja

                        </span>

                    </a>

                </li>

                <li class="nav-item mt-3">

                    <span class="nav-link text-uppercase text-secondary">

                        Account

                    </span>

                </li>

                <li class="nav-item">

                    <a class="nav-link {{ request()->is('admin-cabang/profile*') ? 'active' : '' }}" href="#">

                        <span class="nav-link-icon">

                            <i class="bi bi-person-circle"></i>

                        </span>

                        <span class="nav-link-title">

                            Profil Saya

                        </span>

                    </a>

                </li>

                <li class="nav-item">

                    <form method="POST" action="/admin/logout">

                        @csrf

                        <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">

                            <span class="nav-link-icon">

                                <i class="bi bi-box-arrow-right"></i>

                            </span>

                            <span class="nav-link-title">

                                Logout

                            </span>

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

</aside>
