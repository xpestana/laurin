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
              <template v-if="services.data.length != 0">
              <div id="accordion">
                <div v-for="service in servicios" :key="service.id" class="card card-primary">
                  <div class="card-header">
                    <h4 class="card-title w-100">
                      <a class="d-block w-100" data-toggle="collapse" :href="'#collapse'+service.id">
                        <div class="row justify-content-between">
                          <div class="col-md-6">
                            Facture {{ service.id.toString().padStart (5,'0') }}     
                            <template v-if="service.annuled == true">Service annulé</template>
                            <template v-if="service.goa == true">Service GOA</template>
                          </div>
                          <div class="col-md-3">
                            <span><strong>Total</strong> {{ service.amount }} $</span>    
                          </div>
                        </div>
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
                            {{ service.destination }} ({{ service.km }})
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
                        <li><a :href="route('print.facture', { invoice:service.id })" target="_blank"> Télécharger la facture <i class="fas fa-arrow-alt-circle-down"></i></a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <paginator :paginator="services"/>
                </div>
              </div>
              </template>
              <template  v-else>
                <div>
                <h5>Rien à montrer</h5>
              </div>  
              </template>
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
    computed:{
      servicios(){
        const obj = this.services.data.map((col)=>{
          /**** DISTANCIAS ****/
          var amount_col = 0;
          var feriado = 0;
          var nocturno = 0;

          if (col.date_Time != null) {
            /*feriados*/
            var date = this.moment(col.date_Time).format("MM-DD");  
            if (date == "01-01" || date == "07-01"  || date == "12-25"  || date == "06-24" ) {
              feriado = 1;
            }
            /*Nocturnos*/
            var hour = this.moment(col.date_Time).format("H");
            if (hour >= "19" && hour <= "23") {
              nocturno = 1;
            }
            if (hour >= "0" && hour <= "5") {
              nocturno = 1;
            }
          }
          /**** VALIDACIONES ANULADO *****/
          if(col.annuled == true){
            amount_col = this.annulled(col);
          }
          /**** VALIDACIONES GOA *****/
          if(col.goa == true){
            amount_col = this.goa(col);
          }
          /**** VALIDACIONES con servicios *****/
          if(col.annuled == false && col.goa == false){

            if (col.service == 'T1' || col.service == 'T2' || col.service == 'T3' || col.service == 'T4' || col.service == 'T7') {
              amount_col = this.firstT(col, feriado, nocturno);
            }else if (col.service == 'T5' || col.service == 'T6') {
              amount_col = this.secondT(col, feriado, nocturno);
            }else if (col.service == 'T8') {
              amount_col = this.thirdT(col, feriado, nocturno);
            }

          }

          /**** FLAIR Y ESSENCE ****/
          if (col.flair > 0) {
            amount_col = amount_col + (col.flair * 20);
          }
          if (col.essence > 0) {
            amount_col = amount_col + col.essence;
          }
          
          return {
            id : col.id,
            annuled : col.annuled,
            goa    : col.goa,
            enterprise: col.enterprise,
            base: col.base,
            destination: col.destination,
            flair: col.flair,
            essence: col.essence,
            service: col.service,
            name: col.name,
            phone: col.phone,
            email: col.email,
            file: col.file,
            amount: amount_col,
          }
        });
        return obj;
      },

    },
    created(){
      this.moment=Moment;
      console.log(this.services);
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
      annulled(col){
        
            var amount_col = 0;
            if (col.enterprise == "PDG" || col.enterprise == "URGENTLY") {
              amount_col = 25;
            }
            if (col.enterprise == "AXA") {
              amount_col = 60 + (3 * (col.km - 5));
            }
            if (col.enterprise == "GSA") {
              amount_col = 22 + (2.75 * (col.km - 5));
            }

            return amount_col;
      },
      goa(col){
          var amount_col = 0;
          if (col.enterprise == "PDG") {
              amount_col = 45;
          }
          if (col.enterprise == "ALLSTATE") {
              amount_col = 55;
          }
          if (col.enterprise == "GSA") {
              amount_col = 35;
          }
          if (col.enterprise == "ASSISTEL") {
              amount_col = 30;
          }
          if (col.enterprise == "URGENTLY") {
            if (col.service == "T1" || col.service == "T3" || col.service == "T4" || col.service == "T7" ) {
              amount_col = 50 + (3 * (col.km - 5));
            }
            if (col.service == "T5" || col.service == "T6" || col.service == "T8") {
              amount_col = 80 + (3 * (col.km - 5));
            }
          }

          return amount_col;
      },
      firstT(col, feriado, nocturno){

        var amount_col = 0;
        var km = 0;
        var Tkmxh = 0;
        var timeXtra = col.timeservices-30;

          if (col.enterprise == "PDG") {
              km = col.km - 5;
              Tkmxh = 2.75 * km;
              amount_col = 48 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.25);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }
          if (col.enterprise == "ALLSTATE") {
              km = col.km - 10;
              Tkmxh = 2 * km;
              amount_col = 45 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "AXA") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 60 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.67);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }
          if (col.enterprise == "GSA") {
              km = col.km - 5;
              Tkmxh = 2.75 * km;
              amount_col = 50 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.25);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ASSISTEL") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 60 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ROAD") {
              km = col.km - 10;
              Tkmxh = 2.5 * km;
              amount_col = 52.5 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ASSISTENZA") {
              km = col.km - 5;
              Tkmxh = 2.5 * km;
              amount_col = 40 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "URGENTLY") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 50 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.67);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }

          return amount_col;
      },
      secondT(col, feriado, nocturno){

        var amount_col = 0;
        var km = 0;
        var Tkmxh = 0;
        var timeXtra = col.timeservices-30;

          if (col.enterprise == "PDG") {
              km = col.km - 5;
              Tkmxh = 2.75 * km;
              amount_col = 60 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.25);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }
          if (col.enterprise == "ALLSTATE") {
              km = col.km - 10;
              Tkmxh = 2 * km;
              amount_col = 55 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "AXA") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 70 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.67);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }
          if (col.enterprise == "GSA") {
              km = col.km - 5;
              Tkmxh = 2.75 * km;
              amount_col = 60 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.25);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ASSISTEL") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 70 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ROAD") {
              km = col.km - 10;
              Tkmxh = 2.5 * km;
              amount_col = 87 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ASSISTENZA") {
              km = col.km - 5;
              Tkmxh = 2.5 * km;
              amount_col = 70 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "URGENTLY") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.67);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }

          return amount_col;
      },
      thirdT(col, feriado, nocturno){

        var amount_col = 0;
        var km = 0;
        var Tkmxh = 0;
        var timeXtra = col.timeservices-30;

          if (col.enterprise == "PDG") {
              km = col.km - 5;
              Tkmxh = 2.75 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.25);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }
          if (col.enterprise == "ALLSTATE") {
              km = col.km - 10;
              Tkmxh = 2 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "AXA") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.67);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }
          if (col.enterprise == "GSA") {
              km = col.km - 5;
              Tkmxh = 2.75 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.25);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ASSISTEL") {
              km = col.km - 5;
              Tkmxh = 3 * km;            
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ROAD") {
              km = col.km - 10;
              Tkmxh = 2.5 * km;
              amount_col = 102 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "ASSISTENZA") {
              km = col.km - 5;
              Tkmxh = 2.5 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.17);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 12;
              }
              if (feriado == 1) {
                amount_col = amount_col + 12;
              }
          }
          if (col.enterprise == "URGENTLY") {
              km = col.km - 5;
              Tkmxh = 3 * km;
              amount_col = 80 + Tkmxh;

              if (timeXtra > 0) {
                amount_col = amount_col + (timeXtra * 1.67);
              }
              if (nocturno == 1) {
                amount_col = amount_col + 20;
              }
              if (feriado == 1) {
                amount_col = amount_col + 20;
              }
          }

          return amount_col;
      }
    }
  }
</script>