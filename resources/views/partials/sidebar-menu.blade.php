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
