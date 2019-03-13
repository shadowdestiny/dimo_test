<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">

      <div class="lg:flex -mx-6">

          <Sidebar :page="5"></Sidebar>

          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">


              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top ">
                               Información general de la cuenta
                          </div>
                      </div>
                  </div>

                  <br />

              </div>

              <div id="content">

                  <div>
                      <!--<div class="text-section top2 ">
                         &lt;!&ndash; Información general de la cuenta&ndash;&gt;
                      </div>-->

                      <div class="text-section pt-4">
                          Visualiza el estatus de cada usuario que ingresa a tu plataforma recientemente y visualiza el estatus de cada uno de ellos.
                      </div>
                  </div>

                  <div class="box pt-4">

                      <v-client-table
                              :data="tableData"
                              :columns="columns"
                              :options="options">

                          <a slot="action" slot-scope="props" v-on:click="details(props.row.action)" class="style-link">
                              Ver
                          </a>

                      </v-client-table>

                  </div>

                  <Footer></Footer>
              </div>

          </div>

      </div>

  </div>
</template>

<script>
import { mapActions } from 'vuex';
import Sidebar from "../layouts/partials/sidebar";
import Footer from "../layouts/partials/footer";
import { ClientTable } from 'vue-tables-2';
import Pagination from 'vue-pagination-2';
import Vue from 'vue';
Vue.use(ClientTable);
//Vue.component('pagination', Pagination);
//https://appdividend.com/2018/06/07/vue-datatable-component-example/
//https://onewaytech.github.io/vue2-datatable/doc/#/en/details/dynamic-comps
export default {
    components: {
        Sidebar,
        Footer,
        Pagination
    },
    data() {
        return {
            clients : [],
            columns: [
                'creation date',
                'dui',
                'first name',
                'last name',
                'phone',
                'ranking',
                'status_description',
                'action'
            ],
            tableData: [

            ],
            options: {
                headings: {
                    'creation date'         : 'Fecha de Creación',
                    'dui'                   : 'DUI',
                    'first name'            : 'PRIMER NOMBRE',
                    'last name'             : 'SEGUNDO NOMBRE',
                    'phone'                 : 'TELÉFONO',
                    'ranking'               : 'RANKING',
                    'status_description'    : 'ESTATUS',
                    'action'                : 'ACCIÓN'
                },
                /*sortable: [
                    'creation date',
                    'dui',
                    'first name',
                    'last name',
                    'phone',
                    'ranking',
                    'status',
                    'action'
                ],*/
                texts: {
                    count: 'Mostrando {from} a {to} de {count} registros|{count} registros|Un registro',
                    first: 'Primero',
                    last: 'Último',
                    filter: "",
                    filterPlaceholder: "Buscar usuario",
                    limit: "Cantidad a mostrar:",
                    page: "Página:",
                    noResults: "No se ha encontrado registro",
                    filterBy: "Filtro por {column}",
                    loading: 'Cargando...',
                    defaultOption: 'Seleccionado {column}',
                    columns: 'Columnas'
                },
                //filterable: ['first name', 'last name']
            }
        }
    },
    mounted:  function(){
        this.getClients();
    },
    created () {
        //this.fillData()
    },
    methods: {
        ...mapActions({
            get_client: "client/clients",
        }),

        getClients(type = 'all') {

            const params = {
                type :  type,
                status : this.$route.params.status,
            };

            this.get_client(params).then((response)=>{
                this.clients = response.data;

                let table = [];
                if (response.data !== undefined)
                    response.data.forEach(function(e) {
                        const data = {
                            'id' : e.client_uuid,
                            'creation date' : e.client_created_at,
                            'dui'           : e.dui,
                            'first name'    : e.first_name,
                            'last name'     : e.last_name,
                            'phone'         : e.number_phone,
                            'ranking'       : e.name,
                            'status_description'        : e.status_description,
                            'action'        : e.uuid_client_info
                        };
                        table.push(data);
                    });
                this.tableData = table;

            });
        },

        clientDetail(client){
            this.$router.push({
                name: "client_detail",
                params:{
                    client_id  : client.uuid
                }
            });
        },

        details(uuid){

            this.$router.push({
                name: "client_detail",
                params:{
                    client_id  : uuid
                }
            });
        }


    }
};
</script>
