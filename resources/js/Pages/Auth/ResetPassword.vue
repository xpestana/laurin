<template>
    <Head title="Reset Password" />

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
            <p class="login-box-msg">Vous n'êtes qu'à un pas de votre nouveau mot de passe, récupérez votre mot de passe maintenant.</p>
            <form @submit.prevent="submit">
                <div class="input-group mb-3">
                  <input id="email" name="email" type="text" class="form-control"  placeholder="Courrier électronique"  v-model="form.email" required autofocus>
                  <div class="input-group-append">
                    <div class="input-group-text">
                      <span class="fas fa-envelope"></span>
                  </div>
              </div>
          </div>
          <div class="input-group mb-3">
              <input type="password" class="form-control" placeholder="Password"  v-model="form.password" required autocomplete="new-password" >
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
              </div>
          </div>
      </div>
      <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirm Password"  v-model="form.password_confirmation" required autocomplete="new-password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-12">
        <button type="submit" class="btn btn-primary btn-block" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">Changer le mot de passe</button>
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
    import { defineComponent } from 'vue';
    import { Head } from '@inertiajs/inertia-vue3';
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'

    export default defineComponent({
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetLabel,
            JetValidationErrors
        },

        props: {
            email: String,
            token: String,
        },

        data() {
            return {
                form: this.$inertia.form({
                    token: this.token,
                    email: this.email,
                    password: '',
                    password_confirmation: '',
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('password.update'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    })
</script>
