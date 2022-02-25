<template>
    <Sidebar />
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="card  card-dark mt-4">
              <div class="card-header">
                <h3 class="card-title">Nouveau pilote</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form  @submit.prevent="submit">
                    <div class="row">
                        <div class="col-md-6 mt-4">
                          <div class="form-group">
                            <label>Prénom</label>
                            <div class="input-group mb-3">
                              <input type="text" class="form-control" placeholder="Prénom" v-model="form.firstname" required>
                              <div class="input-group-append">
                                <div class="input-group-text">
                                  <i class="far fa-user icons"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!--end col-->
              <div class="col-md-6 mt-4">
                  <div class="form-group">
                    <label>Nom de famille</label>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Nom de famille" v-model="form.lastname" required>
                      <div class="input-group-append">
                        <div class="input-group-text">
                          <i class="far fa-user icons"></i>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!--end col-->
      <div class="col-md-6 mt-4">
          <div class="form-group">
            <label>E-mail</label>
            <div class="input-group mb-3">
              <input type="email" class="form-control" placeholder="E-mail" v-model="form.email" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <i class="far fa-envelope icons"></i>
              </div>
          </div>
      </div>
  </div>
</div>
<!--end col-->
<div class="col-md-6 mt-4">
  <div class="form-group">
    <label>Date de naissance</label>
    <div class="input-group mb-3">
      <input type="date" class="form-control" placeholder="Date de naissance" v-model="form.birthdate" required>
      <div class="input-group-append">
        <div class="input-group-text">
          <i class="far fa-calendar icons"></i>
      </div>
  </div>
</div>
</div>
</div>
<!--end col-->
<div class="col-md-6 mt-4">
  <div class="form-group">
    <label>Téléphone</label>
    <div class="input-group mb-3">
      <input type="text" class="form-control" placeholder="Téléphone" v-model="form.phone" required>
      <div class="input-group-append">
        <div class="input-group-text">
          <i class="fas fa-mobile icons"></i>
      </div>
  </div>
</div>
</div>
</div>
<!--end col-->
<div align="center" class="col-lg-12 mt-2 mb-0">
    <jet-validation-errors class="mb-4" />
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
    </div>
    <button type="submit" class="btn btn-primary">Sauvegarder</button>
</div>
<!--end col-->
</div>
</form>
</div>
</div>
</div>
</div>  
</div>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout.vue'  
    import Sidebar from '@/Layouts/Components/Sidebar.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3'

    export default {
        layout:AppLayout,
        components: {
            Head,
            Link,
            Sidebar,
        },
        data() {
            return {
               user: this.$page.props.auth.user,
               profile: this.$page.props.auth.profile,
               form: this.$inertia.form({
                email: null,
                firstname: null,
                lastname: null,
                phone: null,
                birthdate: null,
            })
           }
       },
       methods: {
        submit() {
            this.form.post(route('store.driver'), {
                errorBag: 'submit',
                preserveScroll: true,
            });
        },
    },
}
</script>