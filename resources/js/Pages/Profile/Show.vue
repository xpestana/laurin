<template>
    <Sidebar />
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="card  card-dark mt-4">
              <div class="card-header">
                <h3 class="card-title">Editer le profil</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form  @submit.prevent="updateProfileInformation">
                    <div class="row">
                        <div class="col-lg-8 text-md-left text-center image">
                            <img :src="user.profile_photo_url" :alt="profile.firstname"  class="img-circle elevation-2" :key="$page.props.flash.id"  width="100" height="100">
                            <button 
                            type="button"
                            style="border-radius: 20px"
                            size="md"
                            class="btn btn-primary text-uppercase ml-3" 
                            variant="info" 
                            @click.prevent="selectNewPhoto"
                            >
                            Imagen de Perfil
                        </button>
                    </div>
                    <div class="col-3 text-md-left text-center">
                        <div class="mt-4 image" v-show="photoPreview">
                            <img :src="photoPreview" :alt="user.name" class="img-circle elevation-2"  width="100" height="100">
                        </div>
                        <input type="file" class="hidden"
                        ref="photo"
                        @change="updatePhotoPreview" hidden>
                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>
                    </div>
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
                <label>nom de famille</label>
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="nom de famille" v-model="form.lastname" required>
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
          <input type="email" class="form-control" placeholder="nom de famille" v-model="form.email" required>
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
    <button type="submit" class="btn btn-primary">Guardar</button>
</div>
<!--end col-->
</div>
</form>
</div>
<!-- /.card-body -->
</div>
<!-- /.card --> 
</div>
</div>
</div>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout.vue'  
    import Sidebar from '@/Layouts/Components/Sidebar.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue' 
    import { Head, Link } from '@inertiajs/inertia-vue3'
    import Moment from 'moment'

    export default {
        layout:AppLayout,
        components: {
            Head,
            Link,
            JetValidationErrors,
            Sidebar,
        },
        data() {
            return {
            moment:null,
             photoPreview: null,
             user: this.$page.props.auth.user,
             profile: this.$page.props.auth.profile,
             form: this.$inertia.form({
                _method: 'PUT',
                email: this.$page.props.auth.user.email,
                firstname: this.$page.props.auth.profile.firstname,
                lastname: this.$page.props.auth.profile.lastname,
                phone: this.$page.props.auth.profile.phone,
                birthdate: this.$page.props.auth.profile.birthdate,
                photo: null,
            })
         }
     },
     created(){
            this.moment=Moment;
        },
     methods: {
        updateProfileInformation() {
            if (this.$refs.photo) {
                this.form.photo = this.$refs.photo.files[0]
            }

            this.form.post(route('user-profile-information.update'), {
                errorBag: 'updateProfileInformation',
                preserveScroll: true,
                onSuccess: () => {
                    this.$toast.show("enregistré avec succès", {
                        type: "success",
                        position : "top-right",
                        pauseOnHover: "true",
                    });
                    this.clearPhotoFileInput();
                    location.reload();
                },
            });
        },

        selectNewPhoto() {
            this.$refs.photo.click();
        },

        updatePhotoPreview() {
            const photo = this.$refs.photo.files[0];

            if (! photo) return;

            const reader = new FileReader();

            reader.onload = (e) => {
                this.photoPreview = e.target.result;
            };

            reader.readAsDataURL(photo);
        },

        deletePhoto() {
            this.$inertia.delete(route('current-user-photo.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.photoPreview = null;
                    this.clearPhotoFileInput();
                },
            });
        },

        clearPhotoFileInput() {
            if (this.$refs.photo?.value) {
                this.$refs.photo.value = null;
            }
        },
    },
}
</script>