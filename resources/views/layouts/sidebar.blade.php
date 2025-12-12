<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="{{asset('adminlte3/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Nota</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('suratjalan.index') }}" class="nav-link @yield('menuSuratJalan')">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Surat Jalan
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('suratjalan.create') }}" class="nav-link @yield('menuSuratJalanAdd')">
              <i class="nav-icon fas fa-plus"></i>
              <p>
                Add Surat Jalan
              </p>
            </a>
          </li>
          <li class="nav-header">MENU NOTA</li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('nota.index') }}" class="nav-link @yield('menu')">
              <i class="nav-icon fas fa-list"></i>
              <p>
                List Nota
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('nota.create') }}" class="nav-link @yield('menuCreate')">
              <i class="nav-icon fas fa-plus"></i>
              <p>
                Add Nota
              </p>
            </a>
          </li>
          <!-- <li class="nav-header">MENU SUPER ADMIN</li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('superadmin.user.index') }}" class="nav-link @yield('menuSuperadminUser')">
              <i class="nav-icon fas fa-user"></i>
              <p>
                User
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('superadmin.kategori.index') }}" class="nav-link @yield('menuSuperadminKategori')">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Kategori
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a wire:navigate href="{{ route('superadmin.barang.index') }}" class="nav-link @yield('menuSuperadminBarang')">
              <i class="nav-icon fas fa-warehouse"></i>
              <p>
                Barang
              </p>
            </a>
          </li> -->

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>