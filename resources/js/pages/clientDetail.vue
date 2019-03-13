<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">
      <div class="lg:flex -mx-6">
          <Sidebar :page="5"></Sidebar>
          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">
              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top flex">
                              <a class="back mr-4" href="#" @click.prevent="back"><&nbsp;&nbsp;Regresar</a>
                              <p>Información general de las cuentas registradas</p>
                          </div>
                      </div>
                  </div>
                  <br />
              </div>
              <div id="content">
                  <div v-if="result.client">
                      <div class="text-section top2 ">
                          {{first_name}} {{last_name}}
                      </div>

                      <div class="text-section pt-2">
                          {{ setStatusClient(result.client.status) }}
                      </div>
                  </div>
                  <div class="pt-4"></div>
                  <div class="flex flex-wrap menu-right-bottom normal">
                      <div class="w-full md:w-1/4 bg-grey p-4 text-center text-grey-lighter" >
                          <div class="text-1">
                              Máximo al que puede aplicar
                          </div>
                          <div class="text-2" >
                                $ {{ result.setting ? result.setting.value : "0.00" }}
                          </div>
                      </div>
                      <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker" >
                          <div class="text-1">
                              Total prestamo
                          </div>
                          <div class="text-2" >
                              $ {{ result.loan_total ? result.loan_total : "0.00" }}
                          </div>
                      </div>
                      <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker">
                          <div class="text-1">
                              Número de Prestamos
                          </div>
                          <div class="text-2">
                              {{ result.loan_count ? result.loan_count : 0 }}
                          </div>
                      </div>
                      <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker">
                          <div class="text-1">
                              Prestamos Actual
                          </div>
                          <div class="text-2">
                              $ {{ result.loan_balance_now ? result.loan_balance_now : "0.00" }}
                          </div>
                      </div>
                  </div>
                  <div>
                      <div class="text-section top2 ">
                          Todos los créditos
                      </div>
                      <div class="text-section pt-2">
                          Revisa el historial de créditos aprobados y denegados para este usuario.
                      </div>
                  </div>
                  <div class="box pt-4">
                      <v-client-table
                              :data="tableData"
                              :columns="columns"
                              :options="options">
                          <a slot="loans_detail_uuid" slot-scope="props" v-on:click="loan_details(props.row.loans_detail_uuid)" class="style-link">
                              Ver
                          </a>
                          <span slot="balance" slot-scope="props">
                              $ {{props.row.balance}}
                          </span>
                          <span slot="balance_total" slot-scope="props">
                              $ {{props.row.balance_total}}
                          </span>
                          <span slot="loan_status" slot-scope="props">
                              {{  setStatus(props.row.loan_status) }}
                          </span>
                      </v-client-table>
                  </div>
                  <div class="pt-4">
                      <div class="text-section top2 ">
                          Más informacion
                      </div>
                  </div>
                  <div class="flex flex-wrap card-information">
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                             DUI
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.dui}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              País
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.address}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              NIT
                          </div>
                          <div class="text-section pt-2">
                              1234
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Ciudad
                          </div>
                          <div class="text-section pt-2">
                              El salvador
                          </div>
                      </div>
                      <!--<div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Information
                          </div>
                          <div class="text-section pt-2">
                              1
                          </div>
                      </div>-->
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Correo Electrónico
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.email}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Dirección
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.address}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Código de Invitación
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.invitation_code}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Teléfono
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.number_phone}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Fecha de Nacimiento
                          </div>
                          <div class="text-section pt-2">
                              {{result.client.birth_date}}
                          </div>
                      </div>
                      <div class="w-full md:w-1/3 p-4 text-left n-padding">
                          <div class="text-section box-text pt-4">
                              Última vez en la Aplicación
                          </div>
                          <div class="text-section pt-2">
                              2018-06-06 00:14:17
                          </div>
                      </div>
                  </div>
                  <Footer></Footer>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import Sidebar from "../layouts/partials/sidebar";
import Footer from "../layouts/partials/footer";
import { ClientTable } from "vue-tables-2";
import Pagination from "vue-pagination-2";
import Vue from "vue";
Vue.use(ClientTable);
export default {
  components: {
    Sidebar,
    Footer
  },
  data() {
    return {
      first_name: "",
      last_name: "",
      result: {
        client: {
          status: ""
        },
        setting: {
          value: ""
        }
      },
      columns: [
        "created_at",
        "balance",
        "balance_total",
        "loan_status",
        "loans_detail_uuid"
      ],
      options: {
        headings: {
          created_at: "CRÉDITOS APLICADOS",
          balance: "MONTO",
          balance_total: "TOTAL",
          loan_status: "ESTATUS DE PRÉSTAMOS",
          loans_detail_uuid: "ACCIÓN"
        },
        sortable: [
          "created_at",
          "balance",
          "balance_total",
          "status",
          "action"
        ],
        texts: {
          count:
            "Mostrar {from} a {to} de {count} registros|{count} registros|Un registro",
          first: "Primero",
          last: "Último",
          filter: "Filtro:",
          filterPlaceholder: "Buscar",
          limit: "Registro:",
          page: "Página:",
          noResults: "No se ha encontrado registro",
          filterBy: "Filtro por {column}",
          loading: "Cargando...",
          defaultOption: "Seleccionado {column}",
          columns: "Columnas"
        }
        //filterable: ['status']
      },
      tableData: [],
      statuses: [
        { id: 1, name: "pendiente" },
        { id: 2, name: "aprobado" },
        { id: 3, name: "Aceptado" },
        { id: 4, name: "Denegado" }
      ],
      statuses_client: [
        { id: "initial", name: "Inicial" },
        { id: "available", name: "Disponible" },
        { id: "in_process", name: "En Proceso" },
        { id: "approved", name: "Aprobado" },
        { id: "active", name: "Activo" },
        { id: "rejected", name: "Rechazado" },
        { id: "completed", name: "Completado" }
      ]
    };
  },
  mounted() {
    this.getClient();
  },
  methods: {
    ...mapActions({
      clientDetail: "clientdetails/clientDetail"
    }),

    async loanDefault(type = "week") {
      this.active = 1;
      this.loan(type);
    },

    getClient() {
      this.clientDetail(this.$route.params.client_id).then(response => {
        this.result = response.data;
        this.first_name = this.result.client.first_name;
        this.last_name = this.result.client.last_name;
        this.tableData = this.result.all_loan;
      });
    },
    loan_details(uuid) {
      this.$router.push({
        name: "loan_detail",
        params: {
          loan_id: uuid
        }
      });
    },
    back() {
      this.$router.go(-1);
    },
    setStatus(id) {
      return this.statuses.find(s => s.id == id).name;
    },
    setStatusClient(id) {
      const sc = this.statuses_client.find(sc => sc.id == id);
      if (sc) {
        return sc.name;
      }

      return "";
    }
  }
};
</script>
