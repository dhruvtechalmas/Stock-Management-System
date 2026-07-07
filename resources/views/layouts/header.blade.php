<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Dashboard | Stock Manage</title>

  <link rel="stylesheet" href="{{ url('/assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ url('/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ url('/assets/css/style.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="{{ route('stocks.index') }}" aria-label="adminHMD dashboard">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">Stock Management</span>
            <span class="brand-subtitle">Dashboard</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">

        <!-- Dashboard -->
        <a class="nav-link active" href="{{ route('stocks.index') }}" aria-current="page">
          <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>

        <!-- Masters Heading -->
        <div class="sidebar-heading">MASTERS</div>

        <a class="nav-link" href="{{ url('material-category') }}">
          <span class="nav-icon"><i class="bi bi-tags" aria-hidden="true"></i></span>
          <span class="nav-text">Material Category</span>
        </a>

        <a class="nav-link" href="{{ url('materials') }}">
          <span class="nav-icon"><i class="bi bi-box-seam" aria-hidden="true"></i></span>
          <span class="nav-text">Material Master</span>
        </a>

        <a class="nav-link" href="{{ url('suppliers') }}">
          <span class="nav-icon"><i class="bi bi-truck" aria-hidden="true"></i></span>
          <span class="nav-text">Supplier Master</span>
        </a>

        <!-- Transactions Heading -->
        <div class="sidebar-heading">TRANSACTIONS</div>

        <a class="nav-link" href="{{ url('purchases') }}">
          <span class="nav-icon"><i class="bi bi-cart-plus" aria-hidden="true"></i></span>
          <span class="nav-text">Purchase</span>
        </a>

        <a class="nav-link" href="{{ url('material-requests') }}">
          <span class="nav-icon"><i class="bi bi-clipboard-check" aria-hidden="true"></i></span>
          <span class="nav-text">Material Request</span>
        </a>

        <a class="nav-link" href="{{ url('dispatches') }}">
          <span class="nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
          <span class="nav-text">Material Dispatch</span>
        </a>

        <a class="nav-link" href="{{ url('consumptions') }}">
          <span class="nav-icon"><i class="bi bi-basket" aria-hidden="true"></i></span>
          <span class="nav-text">Material Consumption</span>
        </a>

        <a class="nav-link" href="{{ url('wastages') }}">
          <span class="nav-icon"><i class="bi bi-trash3" aria-hidden="true"></i></span>
          <span class="nav-text">Wastage</span>
        </a>

        <!-- Reports Heading -->
        <div class="sidebar-heading">REPORTS</div>

        <a class="nav-link" href="{{ url('current-stock') }}">
          <span class="nav-icon"><i class="bi bi-boxes" aria-hidden="true"></i></span>
          <span class="nav-text">Current Stock</span>
        </a>

        <a class="nav-link" href="{{ url('stock-ledger') }}">
          <span class="nav-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
          <span class="nav-text">Stock Ledger</span>
        </a>

        <a class="nav-link" href="{{ url('stock-report') }}">
          <span class="nav-icon"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i></span>
          <span class="nav-text">Stock Report</span>
        </a>

        <!-- Settings Heading -->
        <div class="sidebar-heading">SETTINGS</div>

        <a class="nav-link" href="{{ url('users') }}">
          <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
          <span class="nav-text">Users</span>
        </a>

      </nav>

    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
            aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme"
              title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>
            <div class="dropdown">
              <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                aria-label="Notifications">
                <span class="notification-dot"></span>
                <i class="bi bi-bell" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end notification-menu">
                <div class="dropdown-header fw-bold text-body">Notifications</div>
                <a class="dropdown-item" href="users">
                  <span class="notification-title">New user registered</span>
                  <span class="notification-time">4 minutes ago</span>
                </a>
                <a class="dropdown-item" href="charts">
                  <span class="notification-title">Revenue target reached</span>
                  <span class="notification-time">32 minutes ago</span>
                </a>
                <a class="dropdown-item" href="settings">
                  <span class="notification-title">Security review completed</span>
                  <span class="notification-time">1 hour ago</span>
                </a>
              </div>
            </div>

            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false">

                <img class="avatar-img avatar-sm" src="{{ asset('assets/images/avatar/avatar-2.jpg') }}"
                  alt="{{ auth()->user()->name }}">

                <div class="d-none d-sm-flex flex-column text-start ms-2">
                  <span class="profile-name fw-semibold">
                    {{ auth()->user()->name }}
                  </span>

                  <small class="text-muted">
                    ({{ auth()->user()->roles->first()?->name ?? 'Kitchen Staff' }})
                  </small>
                </div>

              </button>
              <ul class="dropdown-menu dropdown-menu-end">

                {{-- <li>
                  <h6 class="dropdown-header">
                    {{ auth()->user()->name }}
                  </h6>
                </li>

                <li>
                  <small class="dropdown-item-text text-muted">
                    {{ auth()->user()->email }}
                  </small>
                </li> --}}

                <li>
                  <hr class="dropdown-divider">
                </li>

                <li>
                  <a class="dropdown-item" href="/profile">
                    <i class="bi bi-person me-2"></i>
                    Profile
                  </a>
                </li>

                <li>
                  <hr class="dropdown-divider">
                </li>

                <li>
                  <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                  </a>
                </li>

              </ul>
            </div>
          </div>
        </div>
      </nav>


