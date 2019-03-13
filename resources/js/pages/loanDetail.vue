<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">

      <div class="lg:flex -mx-6">
          <Sidebar :page="6"></Sidebar>
          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">
              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top flex">
                              <a class="back mr-4" href="#" @click.prevent="back"><&nbsp;&nbsp;Regresar</a>
                              <p>Detalles del prestamo</p>
                          </div>
                      </div>
                  </div>

                  <br />

              </div>

              <div id="content">

                  <div>
                      <div class="text-section top2 ">
                          Detalle del prestamo
                      </div>

                      <div class="text-section pt-2">
                          Se muestra el detalle del prestamo
                      </div>
                  </div>

                  <div class="box pt-4">
                      <div class="box pt-4 no-border">

                          <v-client-table
                                  :data="tableData"
                                  :columns="columns"
                                  :options="options">

                              <!--<a slot="loans_detail_uuid" slot-scope="props" v-on:click="details(props.row.loans_detail_uuid)" class="style-link">
                                  View
                              </a>-->
                              <div slot="amount_loan" slot-scope="props">
                                  $ {{ props.row.amount_loan }}
                              </div>

                              <div slot="status" slot-scope="props">
                                  {{ props.row.status === true ? "Activo" : "Inactivo"}}
                              </div>

                          </v-client-table>

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
      data: {},

      columns: [
        "amount_loan",
        "loan_with_interest",
        "date_pay",
        "status",
        "status_loan"
      ],
      tableData: [],
      options: {
        headings: {
          amount_loan: "Monto",
          loan_with_interest: "Crédito con interés",
          date_pay: "Fecha de pago",
          status: "Estado del detalle",
          status_loan: "Estado del crédito"
        },
        sortable: ["date_pay"],
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
        },
        /*filterable: ['first_name',]*/
      }
    };
  },
  mounted: function() {
    this.getDetail();
  },
  methods: {
    ...mapActions({
      loanDetail: "loandetails/loanDetail"
    }),
    async loanDefault(type = "week") {
      this.active = 1;
      this.loan(type);
    },
    getDetail() {
      this.loanDetail(this.$route.params.loan_id).then(response => {
        const result = response.data;
        this.tableData = result;
      });
    },
    back() {
      this.$router.go(-1);
    }
  }
};
</script>
