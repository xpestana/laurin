<template>
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
          <img :src="user.profile_photo_url" :alt="profile.firstname" class="img-circle elevation-2" >
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ profile.firstname }} {{ profile.lastname }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->
           <li class="nav-item">
          <Link :href="route('home')" :class="(route().current('home') === true)? 'active nav-link' : 'nav-link'" @click.prevent="profil">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Profil
              </p>
            </Link>
          </li>
           <li class="nav-item">
            <Link :href="route('profile.edit')" :class="(route().current('profile.edit') === true)? 'active nav-link' : 'nav-link'" @click.prevent="edit">
              <i class="nav-icon fas fa-user-edit"></i>
              <p>
                Editer le profil
              </p>
            </Link>
          </li>
           <li class="nav-item">
            <Link :href="route('profile.edit')" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i> 
              <p>
                Ajouter un pilote
              </p>
            </Link>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Créer facture
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon far fa-clock"></i>
              <p>
                Historique des achats
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link" @click.prevent="logout">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Fermer Session
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>
<script>
  import { Head, Link } from '@inertiajs/inertia-vue3'
  export default {
    components: {
            Head,
            Link,
        },
   data() {
    return {
      user: this.$page.props.auth.user,
      profile: this.$page.props.auth.profile,
    }
  },
  created(){
    console.log(route().current('home'));
  },
  methods: {
            logout(){
                this.$inertia.post(route('logout'), {
                _token: this.$page.props.csrf_token,
                })
            },
            profil(){
                this.$inertia.get(route('home'),{}, {
                    preserveScroll: true
                })
          },
          edit(){
                this.$inertia.get(route('profile.edit'),{}, {
                    preserveScroll: true
                })
          },
        }
}
</script>
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