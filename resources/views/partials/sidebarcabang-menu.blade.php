<li class="nav-item">

    <span class="nav-link text-uppercase text-secondary">

        Main

    </span>

</li>

<li class="nav-item">

    <a class="nav-link {{ request()->is('admin-cabang/dashboard') ? 'active' : '' }}" href="/admin-cabang/dashboard">

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

    <a class="nav-link {{ request()->is('admin-cabang/reservasi-hari-ini*') ? 'active' : '' }}" href="/admin-cabang/reservasi-hari-ini">

        <span class="nav-link-icon">

            <i class="bi bi-calendar-check"></i>

        </span>

        <span class="nav-link-title">

            Reservasi Hari Ini

        </span>

    </a>

</li>

<li class="nav-item">

    <a class="nav-link {{ request()->is('admin-cabang/scan-reservasi*') ? 'active' : '' }}" href="/admin-cabang/scan-reservasi">

        <span class="nav-link-icon">

            <i class="bi bi-qr-code-scan"></i>

        </span>

        <span class="nav-link-title">

            Scan Reservasi

        </span>

    </a>

</li>

<li class="nav-item">

    <a class="nav-link {{ request()->is('admin-cabang/histori-reservasi') ? 'active' : '' }}" href="/admin-cabang/histori-reservasi">

        <span class="nav-link-icon">

            <i class="ti ti-history"></i>

        </span>

        <span class="nav-link-title">

            Histori Reservasi

        </span>

    </a>

</li>

<li class="nav-item">

    <a class="nav-link {{ request()->is('admin-cabang/mejas*') ? 'active' : '' }}" href="/admin-cabang/meja">

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

    <a class="nav-link {{ request()->is('admin-cabang/ganti-password') ? 'active' : '' }}" href="/admin-cabang/ganti-password">

        <span class="nav-link-icon">

            <i class="bi bi-person-lock"></i>

        </span>

        <span class="nav-link-title">

            Ganti Password

        </span>

    </a>

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
