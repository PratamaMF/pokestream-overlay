<div id="layoutSidenav">
  <div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
      <div class="sb-sidenav-menu">
        <ul class="nav-container">
            <li class="sb-sidenav-menu-heading">Main</li>
            <li>
                <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                    Dashboard
                </a>
            </li>

            <li class="sb-sidenav-menu-heading">Operational</li>
            <li>
                <a class="nav-link {{ Request::is('pos*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-shopping-cart"></i></div>
                    Order
                </a>
            </li>
            <li>
                <a class="nav-link {{ Request::is('orders*') ? 'active' : '' }}" href="{{ route('orders.history') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-history"></i></div>
                    History Transaction
                </a>
            </li>

            <li class="sb-sidenav-menu-heading">Management</li>
            <li>
                <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                    Manage Categories
                </a>
            </li>
            <li>
                <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                    Manage Products
                </a>
            </li>
            <li>
                <a class="nav-link {{ Request::is('notes*') ? 'active' : '' }}" href="{{ route('notes.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-sticky-note"></i></div>
                    Manage Notes
                </a>
            </li>
        </ul>
      </div>
    </nav>
  </div>