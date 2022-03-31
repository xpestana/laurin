<template>
    <Sidebar />
    <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="card  card-dark mt-4">
              <div class="card-header">
                <h3 class="card-title">Historique des achats</h3>
            </div>
            <div class="card-body">
                <div class="card-body">
                <!-- we are adding the accordion ID so Bootstrap's collapse plugin detects it -->
                <div id="accordion" v-if="services.length != 0">
                  <div v-for="service in services" :key="service.id" class="card card-primary">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" :href="'#collapse'+service.id">
                          Facture {{ service.id.toString().padStart (5,'0') }} 
                          <template v-if="service.annuled == true">Service annulé</template>
                          <template v-if="service.goa == true">Service GOA</template>
                        </a>
                      </h4>
                    </div>
                    <div :id="'collapse'+service.id" class="collapse" data-parent="#accordion">
                      <div class="card-body">
                        <ul>
                          <li><strong><i class=" mb-3 fas fa-wrench mr-2"></i> Enterprise :</strong> {{ service.enterprise }}</li>
                      <template v-if="service.annuled == false && service.goa == false">
                        <li><strong><i class=" mb-3 fas fa-map-marker-alt mr-2"></i> Base :</strong> 
                          <template v-if="service.base == 1">
                            1620 rang Saint Eduard, St-Liboire QC J0H 1R0
                          </template>
                          <template v-else>
                            1200 Rue Daniel - Johnson O, Saint-Hyacinthe, QC J2S 7K7
                          </template>
                        </li>
                        <li><strong><i class=" mb-3 fas fa-map-marker-alt mr-2"></i> Destination:</strong> 
                          {{ service.destination }}
                        </li>
                        <li><strong><i class="mb-3 fas fa-fire mr-2"></i> Flair:</strong> 
                          {{ service.flair }}
                        </li>
                        <li><strong><i class="mb-3 fas fa-gas-pump mr-2"></i> Essence:</strong> 
                          {{ service.essence }}
                        </li>
                        <li><strong><i class=" mb-3 fas fa-wrench mr-2"></i> Service :</strong> 
                          <template v-if="service.service == 'T1'">CREVAISON</template>
                          <template v-if="service.service == 'T3'">SURVOLTAGE</template>
                          <template v-if="service.service == 'T4'">ESSENCE</template>
                          <template v-if="service.service == 'T5'">ENLISSEMENT</template>
                          <template v-if="service.service == 'T6'">REMORQUAGE</template>
                          <template v-if="service.service == 'T7'">DÉVERROUILLAGE</template>
                          <template v-if="service.service == 'T8'">ACCIDENT</template>
                          <template v-if="service.service == 'SAISIE'">SAISIE</template>
                          <template v-if="service.service == 'REMISAGE'">REMISAGE</template>
                        </li>
                        <li><strong><i class=" mb-3 fas fa-user mr-2"></i> Nom:</strong> {{ service.name }}</li>
                        <li><strong><i class=" mb-3 fas fa-phone mr-2"></i> Téléphone:</strong> {{ service.phone }}</li>
                        <li><strong><i class=" mb-3 fas fa-envelope mr-2"></i> Courriel électronique:</strong> {{ service.email }}</li>
                        <li v-if="service.file" class="col-5 mt-4">
                          <img style="width: 100%" :src="'/storage/img'+service.file">
                        </li>
                      </template>
                      
                    </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else>
                  <h5>Rien à montrer</h5>
                </div>
              </div>
              <!-- /.card-body -->
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
            services: Object,
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