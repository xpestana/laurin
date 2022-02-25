<template>
    <Sidebar />
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
          <div class="container-fluid">
            <div class="card  card-dark mt-4">
              <div class="card-header">
                <h3 class="card-title">Ajouter un pilote</h3>
                <div class="card-tools">
                  <Link :href="route('create.driver')" class="btn btn-block btn-outline-secondary btn-flat text-white">Ajouter un nouveau pilote</Link>
                </div>
            </div>
            <!-- /.card-header -->
            <template v-if="users.data.length > 0">
            <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Le nom</th>
                                <th>Téléphoner</th>
                                <th>E-mail</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                
                                <td class="mb-1">{{ user.profile.firstname }}</td>
                                <td class="mb-1">{{ user.profile.lastname }}</td>
                                <td class="mb-1">{{ user.profile.phone }}</td>
                                <td class="mb-1">{{ user.email }}</td>
                                <td class="mb-1">
                                     <div class="btn-group">
                                        <Link :href="route('edit.driver',{id:user.id})" class="btn btn-block btn-info btn-flat text-white m-0">Mettre à jour</Link>
                                        <a href="javascript:void(0);" class="btn btn-block btn-danger btn-flat text-white m-0" @click.prevent="delete_user(user.id)">Retirer</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <div class="card-footer clearfix">
                <paginator :paginator="users"/>
            </div>
            </template>
            <template v-else>
                <div class="card-body p-0">
                    <h3 class="p-5">
                        Rien à montrer
                    </h3>
                </div>
            </template>
        </div>
    </div>
</div>  
</div>
</template>
<script>
    import AppLayout from '@/Layouts/AppLayout.vue'  
    import Sidebar from '@/Layouts/Components/Sidebar.vue'
    import Moment from 'moment'
    import { Head, Link } from '@inertiajs/inertia-vue3'
    import Paginator from '@/Layouts/Components/Paginator.vue'

    export default {
        layout:AppLayout,
        components: {
            Head,
            Link,
            Sidebar,
            Paginator,
        },
        props: {
            users: Object,
        },
        data(){
            return {
                moment:null
            }
        },
        created(){
            this.moment=Moment;
        },
        methods: {
            delete_user(id){
                this.$swal({
                    title: '¿Tu es sûr?',
                    text: "Cette action ne peut pas être annulée!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui supprimer!',
                    cancelButtonText: 'Annuler',
                }).then((result) => {
                        if (result.isConfirmed) {
                            this.$inertia.delete(route('delete.driver',{id:id}),
                        { preserveScroll: true }
                    )
                        }
                    })
            },
        }
    }
</script>