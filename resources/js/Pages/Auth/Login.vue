<template>
    <Head title="Inicio de Sesión" />
<div class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <Link :href="route('login')">
        <img src="/img/logo.jpeg" alt="Logo" class="img-fluid my-4" width="250">
    </Link>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      
      <jet-validation-errors class="mb-4" />
      <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
        {{ status }}
      </div>
      <form @submit.prevent="submit" class="text-left">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="EXEMPLE@EMAIL.COM" v-model="form.email" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <i class="far fa-user"></i>
            </div>
          </div>
        </div>
        <p align="right" class="mb-0 pb-0">
            <small class="mb-0">
                <Link :href="route('password.request')">¿MOT DE PASSE OUBLIÉ?</Link>
            </small>
        </p>
        <div class="input-group mb-3">
          <input id="password" type="password" class="form-control" placeholder="MOT DE PASSE" v-model="form.password" required >
          <div class="input-group-append">
            <div class="input-group-text">
              <span style="cursor: pointer;" class="fas fa-eye" v-on:click="showPass('password')"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="block icheck-primary">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-sm text-gray-600">Rester Connecté</span>
                </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" class="btn btn-primary btn-block">Connexion</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
<!--
      <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div>-->
      <!-- /.social-auth-links -->
<!--
      <p class="mb-1">
        <Link href="forgot-password.html">¿MOT DE PASSE OUBLIÉ?</Link>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

</div>
<!-- /.login-box -->

    <!--<div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">
                        <h1 class=""></h1>
                        
                        

                        
                            <div class="form">
                                <div id="username-field" class="field-wrapper input">
                                    <label for="username">UTILISATEUR</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="username" name="username" type="text" class="form-control" placeholder=""  >
                                </div>
                                <div id="password-field" class="field-wrapper input mb-2">
                                    <div class="d-flex justify-content-between">
                                        <label for="password">MOT DE PASSE</label>
                                        <Link  class="forgot-pass-link"></Link>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                                    <input id="password" name="password" type="password" class="form-control" autocomplete="current-password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" id="toggle-password" class="feather feather-eye"  ><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </div>
                                <div class="block mt-4">
                                    
                                        
                                        
                                    </label>
                                </div>
                                <div class="d-sm-flex justify-content-between">
                                    <div class="field-wrapper">
                                        <button type="submit" class="btn btn-primary" value="">Connexion</button>
                                    </div>
                                </div>
                                <p class="signup-link" hidden>Not registered ? <a href="auth_register_boxed.html">Create an account</a></p>
                            </div>
                        </form>
                    </div>                    
                </div>
            </div>
        </div>
    </div>-->
</template>
<script>
    import { defineComponent } from 'vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Head,
            JetValidationErrors,
            JetCheckbox,
            Link,
        },
        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                .transform(data => ({
                    ... data,
                    remember: this.form.remember ? 'on' : ''
                }))
                .post(this.route('login'), {
                    onFinish: () => this.form.reset('password'),
                })
            },
            showPass: function (id){
            let x = document.getElementById(id);
            x.type = x.type == 'password' ? 'text' : 'password';            
        }
        }
    })
</script>
