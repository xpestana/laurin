<template>
    <Head title="Restaurar Contraseña" />
    <div class="hold-transition login-page">
        <div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <img src="/img/logo.jpeg" alt="Logo" class="img-fluid my-4" width="250">
    </div>
    <div class="card-body">
        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>
    <jet-validation-errors class="mb-4" />
      <p class="login-box-msg">Vous avez oublié votre mot de passe ? Ici, vous pouvez facilement récupérer un nouveau mot de passe.</p>
      <form @submit.prevent="submit" class="text-left">
        <div class="input-group mb-3">
          <input id="email" name="email" type="text" class="form-control"  placeholder="Courrier électronique"  v-model="form.email" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" value="">Restaurer</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <Link :href="route('login')">Connexion</Link>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            JetValidationErrors,
            Link,
        },
        props: {
            status: String
        },
        data() {
            return {
                form: this.$inertia.form({
                    email: ''
                })
            }
        },
        methods: {
            submit() {
                this.form.post(this.route('password.email'))
            }
        }
    })
</script>
