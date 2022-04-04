  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a align="center" href="/" class="brand-link">
      <img src="/img/logo.jpeg" alt="Logo" class="brand-image elevation-3" style="opacity: .8; float: none;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->profile->firstname }}" class="img-circle elevation-2" >
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->profile->firstname }} {{ auth()->user()->profile->lastname }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
           <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
              Profil
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('profile.edit') }}" class="nav-link">
          <i class="nav-icon fas fa-user-edit"></i>
          <p>
            Editer le profil
          </p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ route('conducteurs') }}" class="nav-link">
        <i class="nav-icon fas fa-tachometer-alt"></i> 
        <p>
          Ajouter un pilote
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a href="/factures" class="active nav-link">
        <i class="nav-icon fas fa-edit"></i>
        <p>
          Créer facture
        </p>
      </a>
    </li>
    @role('Admin')
    <li class="nav-item">
      <a href="{{ route('history.invoices') }}" class="nav-link">
        <i class="nav-icon far fa-clock"></i>
        <p>
          Historique des achats
        </p>
      </a>
    </li>
    @endrole
    <li class="nav-item">
      <a href="{{ route('logout') }}" class="nav-link"onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>
          Fermer Session
        </p>
      </a>
    </li>
  </ul>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
   	@csrf
  </form>
</nav>
<!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>

<style scope>
  .sidebar-dark-primary{
    background-color: rgb(88 120 174 / 90%);
  }
  [class*=sidebar-dark-] .sidebar a{
    color: rgb(255 255 255 / 80%) !important;
  }
  .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active{
    background-color: rgb(255 255 255 / 20%);
  }
</style>