<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">

      <div class="lg:flex -mx-6">

          <Sidebar :page="1"></Sidebar>

          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">

              <div id="app" class="flex">
                  <div class="pt-24 pb-8 lg:pt-28 w-full">
                      <div class="top-menu">
                        <div class="flex flex-wrap menu-right-top">
                          <div class="w-full md:w-1/6 bg-grey p-4 text-center text-grey-lighter" :class="{selected:active === 1}">
                              <div @click="loanDefault('week')">
                                  ÚLTIMA SEMANA
                              </div>
                          </div>
                          <div class="w-full md:w-1/6 bg-grey-light p-4 text-center text-grey-darker" :class="{selected:active === 2}">
                              <div @click="loan30()">
                                  ÚLTIMOS 30 DIAS
                              </div>
                          </div>
                          <div class="w-full md:w-1/6 bg-grey-light p-4 text-center text-grey-darker" :class="{selected:active === 3}">
                              <div @click="loanYear()">
                                  ÚLTIMOS AÑO
                              </div>
                          </div>
                          <div class="w-full md:w-1/6 bg-grey-light p-4 text-center text-grey-darker" :class="{selected:active === 4}">
                              <div @click="loanDate()">
                                  RANGO DE FECHAS

                              </div>
                          </div>
                      </div>
                      </div>
                  </div>
              </div>

              <div id="content">
                  <div class="text-section">
                      Te presentamos los últimos resultados que has tenido en la última semana de prestamos realizados en tu aplicación, cada dia se presenta con un color distinto en la tabla principal, puedes activar y desactivar los dias que agustes, para hacer la comparación mas especificas.
                  </div>
                  <div class="flex flex-wrap range-position" v-if="active===4">
                      <div class="w-full md:w-1/2">
                          <date-picker v-model="range" range lang="es"  format="MM-DD-YYYY"></date-picker>
                      </div>
                      <div class="w-full md:w-1/2" style="padding-top:0px;">
                          <button @click="filter" >Filtrar</button>
                      </div>
                  </div>
                  <br />
                  <div class="graph">
                      <br>
                      <div class="flex flex-wrap menu-right-bottom">
                          <div class="w-full md:w-1/5 bg-grey p-4 text-center text-grey-lighter" @click="loanView('made')">
                              <div class="text-1">
                                  Préstamos realizados
                              </div>
                              <div class="text-2">
                                  {{loan_accepted.totalCount}} Prestamos
                              </div>
                          </div>
                          <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker" @click="loanView('paid')">
                            <div class="text-1">
                                Préstamos pagados
                            </div>
                            <div class="text-2">
                                {{ loan_paid.totalCount }} Préstamos
                            </div>
                          </div>
                          <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker no-border" @click="client_list('delinquency')">
                              <div class="text-1">
                                  Usuarios en mora
                              </div>
                              <div class="text-2">
                                  {{delinquency_profile.totalCount}} Usuarios
                              </div>
                          </div>
                          <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker no-border">
                              <div class="text-1">
                                  Monto cartera de préstamos
                              </div>
                              <div class="text-2">
                                  $ 0.00
                              </div>
                          </div>
                          <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker no-border">
                              <div class="text-1">
                                  Monto en mora
                              </div>
                              <div class="text-2">
                                  $ 0.00
                              </div>
                          </div>
                      </div>
                      <br /><br />
                      <monthly-income
                              :chart-data="datacollection"
                              :width="400"
                              :height="300"
                      ></monthly-income>
                  </div>
                  <br />
                  <div class="flex flex-wrap menu-right-bottom">
                      <div class="w-full md:w-1/4 bg-grey p-4 text-center text-grey-lighter" @click="client_list">
                          <div class="text-1">
                              Perfiles completos
                          </div>
                          <div class="text-2" >
                            {{ profile_completed.totalCount }}
                          </div>
                      </div>
                      <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker" @click="loanView('approved')">
                          <div class="text-1">
                              Prestamos aprobados
                          </div>
                          <div class="text-2" >
                              {{ loan_aproved.totalCount }}
                          </div>
                      </div>
                      <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker" @click="loanView('deny')">
                          <div class="text-1">
                              Prestamos denegados
                          </div>
                          <div class="text-2">
                              {{ loan_rejected.totalCount }}
                          </div>
                      </div>
                      <div class="w-full md:w-1/4 bg-grey-light p-4 text-center text-grey-darker" @click="loanView('pending')">
                          <div class="text-1">
                              Prestamos pendientes
                          </div>
                          <div class="text-2">
                              {{ loan_pending.totalCount }}
                          </div>
                      </div>
                      <!--<div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker no-border">
                          <div class="text-1">
                              Descargas de Play Store
                          </div>
                          <div class="text-2">
                              {{ downloads_play_store }}
                          </div>
                      </div>-->
                  </div>

                  <Footer></Footer>

              </div>

          </div>

      </div>

  </div>
</template>

<script>
import MonthlyIncome from "../components/MonthlyIncome";
import { mapActions } from "vuex";
import Content from "../layouts/partials/content";
import Sidebar from "../layouts/partials/sidebar";
import Footer from "../layouts/partials/footer";
import * as axios from "axios";
import DatePicker from "vue2-datepicker";
import VueMoment from "vue-moment";
import moment from "moment";
export default {
  components: {
    Sidebar,
    Content,
    Footer,
    MonthlyIncome,
    DatePicker
  },
  /*middleware: "auth",*/
  data() {
    return {
      profile_completed: "0",
      delinquency_profile: "0",
      loan_aproved: "0",
      loan_accepted: "0",
      loan_paid: "0",
      loan_rejected: "0",
      loan_pending: "0",
      downloads_play_store: "251",
      active: 0,
      datacollection: [],
      range: ""
    };
  },
  mounted: function() {
    this.active = 1;
    this.loan();
  },
  methods: {
    ...mapActions({
      loans: "loan/loan",
      loansCount: "loan/loansCount"
    }),
    async loan30() {
      this.active = 2;
      this.loan("month");
    },
    async loanYear() {
      this.active = 3;
      this.loan("year");
    },
    async loanDate() {
      this.active = 4;
    },
    async loanDefault(type = "week") {
      this.active = 1;
      this.loan(type);
    },
    loan(type = "week") {
      this.loansCount(type).then(response => {
        const result = response.data;
        this.loan_aproved       = result.loan_aproved;
        this.loan_accepted      = result.loan_accepted;
        this.loan_paid          = result.loan_paid;
        this.loan_pending       = result.loan_pending;
        this.loan_rejected      = result.loan_rejected;
        this.profile_completed  = result.profile_completed;
        this.delinquency_profile= result.delinquency_profile;
        this.fillData();

      });
    },

    fillData() {

        let data = {};

        switch (this.active){
            case 1:
                data = {
                    labels: ["LUN", "MAR", "MIÉ", "JUV", "VIE", "SAB", "DOM"],
                    datasets: [
                        {
                            label: "Préstamos realizados",
                            backgroundColor: "#BAEBCC",
                            data: [
                                this.loan_paid.weekCount.MO,
                                this.loan_paid.weekCount.TU,
                                this.loan_paid.weekCount.WE,
                                this.loan_paid.weekCount.TH,
                                this.loan_paid.weekCount.FR,
                                this.loan_paid.weekCount.SA,
                                this.loan_paid.weekCount.SU
                            ]
                        },
                        {
                            label: "Préstamos pagados",
                            backgroundColor: "#69CC8D",
                            data: [
                                this.loan_aproved.weekCount.MO,
                                this.loan_aproved.weekCount.TU,
                                this.loan_aproved.weekCount.WE,
                                this.loan_aproved.weekCount.TH,
                                this.loan_aproved.weekCount.FR,
                                this.loan_aproved.weekCount.SA,
                                this.loan_aproved.weekCount.SU
                            ]
                        },
                        {
                            label: "Usuarios en mora",
                            backgroundColor: "#3BA060",
                            data: [
                                this.delinquency_profile.weekCount.MO,
                                this.delinquency_profile.weekCount.TU,
                                this.delinquency_profile.weekCount.WE,
                                this.delinquency_profile.weekCount.TH,
                                this.delinquency_profile.weekCount.FR,
                                this.delinquency_profile.weekCount.SA,
                                this.delinquency_profile.weekCount.SU
                            ]
                        }
                    ]
                };
                break;
            case 2:
                data = {
                    labels: ["Semana 1", "Semana 2", "Semana 3", "Semana 4"],
                    datasets: [
                        {
                            label: "Préstamos realizados",
                            backgroundColor: "#BAEBCC",
                            data: [
                                this.loan_paid.weekOfMonth.ONE,
                                this.loan_paid.weekOfMonth.TWO,
                                this.loan_paid.weekOfMonth.THR,
                                this.loan_paid.weekOfMonth.FOU
                            ]
                        },
                        {
                            label: "Préstamos pagados",
                            backgroundColor: "#69CC8D",
                            data: [
                                this.loan_aproved.weekOfMonth.ONE,
                                this.loan_aproved.weekOfMonth.TWO,
                                this.loan_aproved.weekOfMonth.THR,
                                this.loan_aproved.weekOfMonth.FOU,
                            ]
                        },
                        {
                            label: "Usuarios en mora",
                            backgroundColor: "#3BA060",
                            data: [
                                this.delinquency_profile.weekOfMonth.ONE,
                                this.delinquency_profile.weekOfMonth.TWO,
                                this.delinquency_profile.weekOfMonth.THR,
                                this.delinquency_profile.weekOfMonth.FOU,
                            ]
                        }
                    ]
                };
                break;
            case 3:
                data = {
                    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                    datasets: [
                        {
                            label: "Préstamos realizados",
                            backgroundColor: "#BAEBCC",
                            data: [
                                this.loan_paid.month.January,
                                this.loan_paid.month.February,
                                this.loan_paid.month.March,
                                this.loan_paid.month.April,
                                this.loan_paid.month.May,
                                this.loan_paid.month.June,
                                this.loan_paid.month.July,
                                this.loan_paid.month.August,
                                this.loan_paid.month.September,
                                this.loan_paid.month.October,
                                this.loan_paid.month.November,
                                this.loan_paid.month.December,
                            ]
                        },
                        {
                            label: "Préstamos pagados",
                            backgroundColor: "#69CC8D",
                            data: [
                                this.loan_aproved.month.January,
                                this.loan_aproved.month.February,
                                this.loan_aproved.month.March,
                                this.loan_aproved.month.April,
                                this.loan_aproved.month.May,
                                this.loan_aproved.month.June,
                                this.loan_aproved.month.July,
                                this.loan_aproved.month.August,
                                this.loan_aproved.month.Septembe,
                                this.loan_aproved.month.October,
                                this.loan_aproved.month.November,
                                this.loan_aproved.month.December,
                            ]
                        },
                        {
                            label: "Usuarios en mora",
                            backgroundColor: "#3BA060",
                            data: [
                                this.delinquency_profile.month.January,
                                this.delinquency_profile.month.February,
                                this.delinquency_profile.month.March,
                                this.delinquency_profile.month.April,
                                this.delinquency_profile.month.May,
                                this.delinquency_profile.month.June,
                                this.delinquency_profile.month.July,
                                this.delinquency_profile.month.August,
                                this.delinquency_profile.month.Septembe,
                                this.delinquency_profile.month.October,
                                this.delinquency_profile.month.November,
                                this.delinquency_profile.month.December,
                            ]
                        }
                    ]
                };
                break;
            case 4:
                data = {
                    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                    datasets: [
                        {
                            label: "Préstamos realizados",
                            backgroundColor: "#BAEBCC",
                            data: [
                                this.loan_paid.month.January,
                                this.loan_paid.month.February,
                                this.loan_paid.month.March,
                                this.loan_paid.month.April,
                                this.loan_paid.month.May,
                                this.loan_paid.month.June,
                                this.loan_paid.month.July,
                                this.loan_paid.month.August,
                                this.loan_paid.month.September,
                                this.loan_paid.month.October,
                                this.loan_paid.month.November,
                                this.loan_paid.month.December,
                            ]
                        },
                        {
                            label: "Préstamos pagados",
                            backgroundColor: "#69CC8D",
                            data: [
                                this.loan_aproved.month.January,
                                this.loan_aproved.month.February,
                                this.loan_aproved.month.March,
                                this.loan_aproved.month.April,
                                this.loan_aproved.month.May,
                                this.loan_aproved.month.June,
                                this.loan_aproved.month.July,
                                this.loan_aproved.month.August,
                                this.loan_aproved.month.Septembe,
                                this.loan_aproved.month.October,
                                this.loan_aproved.month.November,
                                this.loan_aproved.month.December,
                            ]
                        },
                        {
                            label: "Usuarios en mora",
                            backgroundColor: "#3BA060",
                            data: [
                                this.delinquency_profile.month.January,
                                this.delinquency_profile.month.February,
                                this.delinquency_profile.month.March,
                                this.delinquency_profile.month.April,
                                this.delinquency_profile.month.May,
                                this.delinquency_profile.month.June,
                                this.delinquency_profile.month.July,
                                this.delinquency_profile.month.August,
                                this.delinquency_profile.month.Septembe,
                                this.delinquency_profile.month.October,
                                this.delinquency_profile.month.November,
                                this.delinquency_profile.month.December,
                            ]
                        }
                    ]
                };
                break;
            default:
                data = {
                    labels: ["LUN", "MAR", "MIÉ", "JUV", "VIE", "SAB", "DOM"],
                    datasets: [
                        {
                            label: "Préstamos realizados",
                            backgroundColor: "#BAEBCC",
                            data: [
                                this.loan_paid.weekCount.MO,
                                this.loan_paid.weekCount.TU,
                                this.loan_paid.weekCount.WE,
                                this.loan_paid.weekCount.TH,
                                this.loan_paid.weekCount.FR,
                                this.loan_paid.weekCount.SA,
                                this.loan_paid.weekCount.SU
                            ]
                        },
                        {
                            label: "Préstamos pagados",
                            backgroundColor: "#69CC8D",
                            data: [
                                this.loan_aproved.weekCount.MO,
                                this.loan_aproved.weekCount.TU,
                                this.loan_aproved.weekCount.WE,
                                this.loan_aproved.weekCount.TH,
                                this.loan_aproved.weekCount.FR,
                                this.loan_aproved.weekCount.SA,
                                this.loan_aproved.weekCount.SU
                            ]
                        },
                        {
                            label: "Usuarios en mora",
                            backgroundColor: "#3BA060",
                            data: [
                                this.delinquency_profile.weekCount.MO,
                                this.delinquency_profile.weekCount.TU,
                                this.delinquency_profile.weekCount.WE,
                                this.delinquency_profile.weekCount.TH,
                                this.delinquency_profile.weekCount.FR,
                                this.delinquency_profile.weekCount.SA,
                                this.delinquency_profile.weekCount.SU
                            ]
                        }
                    ]
                };
                break;
        }

      this.datacollection = data
    },
    client_list(status="completed") {
      this.$router.push({
        name: "client_list",
        params: {
          status
        }
      });
    },
    loanView(status) {
      this.$router.push({
        name: "loan_list",
        params: {
          status: status
        }
      });
    },
    filter() {
      const start = this.formatDate(this.range[0]);
      const end = this.formatDate(this.range[1]);
      this.loan(start + "|" + end);
    },
    formatDate(today) {
      let dd = today.getDate();
      let mm = today.getMonth() + 1; //January is 0!

      let yyyy = today.getFullYear();
      if (dd < 10) {
        dd = "0" + dd;
      }
      if (mm < 10) {
        mm = "0" + mm;
      }
      return yyyy + "-" + mm + "-" + dd;
    }
  }
};
</script>
