  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('dashboard') ? '' : 'collapsed' }} " href="{{ url('/dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <!-- End Dashboard Nav -->
      @if(auth()->user()->role->name == 'SUPER_ADMIN')
      <li class="nav-item">
        <a class="nav-link {{ request()->is('users') ? '' : 'collapsed' }} " href="{{ url('/users') }}">
          <i class="bi bi-person"></i>
          <span>Users</span>
        </a>
      </li>
      @endif

      <!-- <li class="nav-item">
        <a class="nav-link {{ request()->is('catalogs') ? '' : 'collapsed' }} " href="{{ url('/catalogs') }}">
          <i class="bi bi-menu-button-wide"></i>
          <span>Catalogs</span>
        </a>
      </li> -->

      <li class="nav-item">
        <a class="nav-link {{ request()->is('catalogs') ? '' : 'collapsed' }} || {{ request()->is('pending-catalogs') ? '' : 'collapsed' }}" data-bs-target="#catalogs-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Catalogs</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="catalogs-nav" class="nav-content collapse  {{ request()->is('catalogs') || request()->is('pending-catalogs') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
          <li class="nav-item">
              <a class="nav-link {{ request()->is('catalogs') ? 'active' : 'collapsed' }}" href="{{ url('/catalogs') }}">
                <i class="bi bi-circle"></i>
                <span>Catalogs</span>
              </a>
          </li>
          <li>
            <a class="nav-link {{ request()->is('pending-catalogs') ? 'active' : 'collapsed' }}" href="{{ url('/pending-catalogs') }}">
              <i class="bi bi-circle"></i><span>Pending Catalogs</span>
            </a>
          </li>
        </ul>
      </li>

    </ul>

  </aside>
  <!-- End Sidebar-->