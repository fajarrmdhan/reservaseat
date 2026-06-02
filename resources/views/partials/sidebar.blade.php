<aside class="navbar navbar-vertical navbar-expand-lg">

    <div class="container-fluid">

        <h1 class="navbar-brand">

            ReservaSeat

        </h1>

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

                    <a class="nav-link {{ request()->is('') ? 'active' : '' }}" href="">

                        <span class="nav-link-icon">

                            <i class="bi bi-box-arrow-right"></i>

                        </span>

                        <span class="nav-link-title">

                            Logout

                        </span>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</aside>
