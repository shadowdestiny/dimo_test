<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">

      <div class="lg:flex -mx-6">

          <Sidebar :page="6"></Sidebar>

          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">


              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top ">
                             Registro de los créditos
                          </div>
                      </div>
                  </div>

                  <br />

              </div>

              <div id="content">

                  <div class="box pt-4">

                      <div class="flex flex-wrap menu-right-bottom normal">
                          <div class="w-full md:w-1/4 bg-grey p-4 text-center text-grey-lighter" >
                              <div class="text-1">
                                  Prestamos vencidos
                              </div>
                              <div class="text-2" >
                                  {{ counts.expired_loans }}
                              </div>
                          </div>
                          <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker" >
                              <div class="text-1">
                                  Prestamos a pagar hoy
                              </div>
                              <div class="text-2" >
                                  {{ counts.loans_pay_today }}
                              </div>
                          </div>
                          <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker">
                              <div class="text-1">
                                  Prestamos sin mora
                              </div>
                              <div class="text-2">
                                  {{ counts.loans_without_default }}
                              </div>
                          </div>
                          <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker">
                              <div class="text-1">
                                  Prestamos con mora
                              </div>
                              <div class="text-2">
                                  {{ counts.loans_with_arrears }}
                              </div>
                          </div>
                      </div>

                      <v-client-table
                              :data="tableData"
                              :columns="columns"
                              :options="options">

                          <a slot="loans_detail_uuid" slot-scope="props" v-on:click="details(props.row.loans_detail_uuid)" class="style-link">
                              Ver
                          </a>
                          <span slot="amount" slot-scope="props">
                              $ {{props.row.amount}}
                          </span>

                      </v-client-table>

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
import * as axios from "axios";
export default {
  components: {
    Sidebar,
    Footer
  },
  data() {
    return {
      counts: {
        expired_loans: 0,
        loans_pay_today: 0,
        loans_without_default: 0,
        loans_with_arrears: 0
      },
      columns: [
        "first_name",
        "amount",
        "status",
        "created_at",
        "loans_detail_uuid"
      ],
      tableData: [],
      options: {
        headings: {
          first_name: "Usuario",
          amount: "Monto",
          status: "Estatus",
          created_at: "Fecha inicial",
          loans_detail_uuid: "Acción"
        },
        sortable: ["first_name", "created_at"],
        //filterable: ['first_name',],
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
      }
    };
  },
  mounted: function() {
    this.getLoans();
  },
  created() {
    //this.fillData()
  },
  methods: {
    ...mapActions({
      loan: "loan/loans"
    }),

    async loanDefault(type = "week") {
      this.active = 1;
      this.loan(type);
    },

    getLoans() {
      this.loan(this.$route.params.status).then(response => {
        this.counts = response.data.counts;
        this.tableData = response.data.loans;
      });
    },

    details(details_id) {
      this.$router.push({
        name: "loan_detail",
        params: {
          loan_id: details_id
        }
      });
    },

    loanDetails(loan) {
      this.$router.push({
        name: "loan_detail",
        params: {
          loan_id: loan.uuid
        }
      });
    }
  }
};
</script>
