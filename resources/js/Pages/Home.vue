<template>
    <Sidebar />
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
             <div class="card  card-dark mt-4">
              <div class="card-header">
                <h3 class="card-title">Profil</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                    <div class="row justify-content-center">
                        <div align="center" class="col-md-3 text-md-left text-center image">
                            <img :src="user.profile_photo_url" :alt="profile.firstname"  class="img-circle elevation-2" :key="$page.props.flash.id"  width="200" height="200">
                    </div>
                    <div class="col-12"></div>
                    <div align="center" class="col-md-12">
                        <h5 class="text-bold text-dark mt-5" id="default-card-markup">{{ profile.firstname }} {{ profile.lastname }}</h5>
                        <p class="text-muted">
                  <i class="far fa-calendar icons"></i> {{ moment(profile.birthdate).format('DD/MMM/YYYY') }}
                </p>
                <p class="text-muted">
                  <i class="fas fa-mobile icons"></i>  {{ profile.phone }}
                </p>
                    </div>
                    <div align="center" class="col-md-3 text-md-left text-center">
                        <a href="/factures" class="btn btn-block btn-outline-primary btn-flat">Créer facture</a>
                    </div>
                </div>
            </div>
        </div>
          </div>
      </div>
  </div>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout.vue'  
    import Sidebar from '@/Layouts/Components/Sidebar.vue'
    import Moment from 'moment'

    export default {
        layout:AppLayout,
        components:{
            Sidebar,
        },
        data() {
            return {
            moment:null,
            user: this.$page.props.auth.user,
            profile: this.$page.props.auth.profile,
         }
     },
     created(){
            this.moment=Moment;
        },
    }
</script>