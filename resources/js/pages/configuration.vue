<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">

      <div class="lg:flex -mx-6">

          <Sidebar :page="4"></Sidebar>

          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">


              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top ">
                              Configuraciónes generales
                          </div>
                      </div>
                  </div>

                  <br />

              </div>

              <div id="content">

                  <div>
                      <div class="text-section top2 ">
                          Niveles de préstamos
                      </div>

                      <div class="text-section pt-2">
                          Necesitas definir que usuarios serán los que optaran por nuevos préstamos y con mayor cantidad monetaria, es importante que especifiques el nivel y requerimientos para lograrlo
                      </div>
                  </div>

                  <div class="pt-4">
                      <div class="text-section box-text row-cop"  v-cloak>
                          <div class="cop-section" v-for="level in levels" @click="levelAmount(level)">
                              <div class="cop-image">
                                  <div>
                                      <img :src="require('img/cop.png')" alt="Logo Diimo">
                                  </div>
                              </div>
                              <div class="description" >
                                  <div>
                                      {{level.name}}
                                  </div>
                              </div>
                          </div>
                          <!--<div class="cop-section">
                              <div class="cop-image">
                                  <div>
                                      <img :src="require('img/cop.png')" alt="Logo Diimo">
                                  </div>
                              </div>
                              <div class="description" @click="limit('silver')">
                                  <div>
                                      Plata
                                  </div>
                              </div>
                          </div>
                          <div class="cop-section">
                              <div class="cop-image">
                                  <div>
                                      <img :src="require('img/cop.png')" alt="Logo Diimo">
                                  </div>
                              </div>
                              <div class="description" @click="limit('gold')">
                                  <div>
                                      Oro
                                  </div>
                              </div>
                          </div>-->
                          <!--<div class="cop-section disabled">
                              <div class="cop-image">
                                  <div>
                                      <img :src="require('img/cop-disable.png')" alt="Logo Diimo">
                                  </div>
                              </div>
                          </div>-->
                      </div>

                  </div>

                  <div class="line pt-3 pb-2"></div>

                  <div>
                      <div class="text-section top2 ">
                          Definir Impuesto
                      </div>

                      <div class="flex flex-warp pt-3">
                          <div class="w-full md:w-1/6">
                              <label>Tasa anual de interés</label>
                              <input type="text" v-model=annual_interest_rate class="style-a" required/>%
                          </div>
                      </div>

                      <div class="text-section pt-2">
                          Es importante definir un impuesto ante tu préstamo, escribe cuál será para cada préstamo en especifico.
                      </div>

                      <div class="text-section top2 ">
                          Parámetros de nivel {{ name_level_selected }}
                      </div>

                      <div class="flex flex-wrap pt-3">
                          <!--<div class="w-full md:w-1/6">
                              <label>Monto mínimo</label>
                              <input type="text" v-model=minimun_to_apply class="style-a" required>
                          </div>-->
                          <!--<div class="w-full md:w-1/6">
                              <label>Préstamo máximo</label>
                              <input type="text" v-model=max_loans class="style-a" required>
                          </div>-->
                          <div class="w-full md:w-1/6">
                              <label>Monto siguiente nivel</label>
                              <input type="text" v-model=maximum_next_level class="style-a" required/>
                          </div>
                          <div class="w-full md:w-1/6">
                              <label>Préstamos siguiente nivel</label>
                              <input type="text" v-model=max_loans class="style-a" required/>
                          </div>
                          <div class="w-full md:w-1/6">
                              <label>Tiempo para siguiente nivel</label>
                              <input type="text" v-model=max_time class="style-a" required/>
                          </div>
                          <div class="w-full md:w-1/6">
                              <label>Comisión</label>
                              <input type="text" v-model=commission class="style-a" required/>
                          </div>
                          <div class="w-full md:w-1/6 checkbox-grid">
                              <label>Periodo 30 días</label>
                              <div>
                                  <input type="checkbox"
                                         name="required"
                                         id="unit_has_30"
                                         :checked=has_30
                                         :value=has_30
                                         v-model=has_30
                                         @click="changeRequired($event, props.row)">
                              </div>
                          </div>
                          <div class="w-full md:w-1/6">
                              <button class="enabled configuration-button" @click="update">Editar</button>
                          </div>

                      </div>
                  </div>

                  <div class="line pt-3 pb-2"></div>

                  <div class="box pt-4">

                       <v-client-table
                              :data="tableData"
                              :columns="columns"
                              :options="options">

                           <div slot="amount" slot-scope="props">
                               <div v-if="props.row.edit === false">
                                   $ {{ props.row.amount }}
                               </div>
                               <div v-if="props.row.edit === true">
                                   <input type="text" :value="props.row.amount" v-on:keypress="text($event,props.row)"/>
                               </div>
                           </div>

                           <div slot="available" slot-scope="props">
                               <input type="checkbox"
                                      name="required"
                                      id="required"
                                      :checked="props.row.available === 'true'"
                                      :value="props.row.available"
                                      @click="changeRequired($event, props.row)">
                           </div>
                           <div slot="has_30" slot-scope="props">
                               <input type="checkbox"
                                      name="required"
                                      id="has_30"
                                      :checked="props.row.has_30"
                                      :value="props.row.has_30"
                                      @click="has_30($event, props.row)">
                           </div>

                           <div slot="column_edit" slot-scope="props" class="edit-independent" @click="edit(props.row)">Editar</div>

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
import DatePicker from "vue2-datepicker";
export default {
  components: {
    Sidebar,
    Footer,
    DatePicker
  },
  data() {
    return {
      name_level_selected : 'Bronce',
      level_uuid_selected : '',
      time1: "",
      minimun_to_apply: 0,
      maximum_to_apply: 0,
      maximum_next_level: 0,
      annual_interest_rate: 0,
      max_loans: 0,
      max_time: 0,
      commission: 0,
      has_30 : false,

      levels: [],

      columns: ["amount", /*"available",*/ /*"created_at",*//*"has_30",*/"column_edit"],
      tableData: [],
      options: {
        headings: {
          amount        : "Monto",
          available     : "¿Disponible?",
          created_at    : "Fecha de creación",
          has_30        : "Periodo 30 días",
          column_edit   : " ",
        },
        sortable: ["amount", "available", "created_at"],
        //filterable: ['amount', 'available'],
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

      //
    };
  },
  mounted: function() {
    this.getDataSetting();
  },
  created() {
    //this.fillData()
  },
  methods: {
    ...mapActions({
      getSetting: "setting/get",
      saveUpdate: "level/update",
      getLevels: "level/get",
      saveLevel: "levelAmount/update",
      getLevelAmount: "levelAmount/get",
      getOneLevel: "level/getOne",
    }),

    getDataSetting(key) {

      this.getLevels().then(response => {
        this.levels = response.data;
        if (this.levels.length > 0) {
          this.levelAmount(this.levels[0]);
        }
      });
    },

    update() {
      this.saveUpdate({
          next_level_amount     : this.maximum_next_level,
          annual_interest_rate  : this.annual_interest_rate,
          uuid                  : this.level_uuid_selected,
          max_loans             : this.max_loans,
          max_time              : this.max_time,
          commission            : this.commission,
          has_30                : this.has_30,
      }).then(response => {
        //this.getDataSetting();
      });
    },

    levelAmount(level) {

        const uuid = level.uuid;
        this.level_uuid_selected = uuid;
        this.getLevelAmount(uuid).then(response => {
            let table = response.data;

            let tableData = [];

            table.forEach(function(element){
                tableData.push({
                    amount      : element.amount,
                    uuid        : element.uuid,
                    available   : element.available,
                    created_at  : element.created_at,
                    level_uuid  : element.level_uuid,
                    edit        : false,
                    column_edit : '',
                    has_30      : element.has_30,
                })
            });

            this.tableData = tableData;
        });
        this.getOneLevel(uuid).then(response => {
            this.maximum_next_level = response.data.next_level_amount;
            this.annual_interest_rate           = response.data.annual_interest_rate;
            this.name_level_selected            = response.data.name;
            this.max_loans                      = response.data.max_loans;
            this.max_time                       = response.data.max_time;
            this.commission                     = response.data.commission;
            this.has_30                         = response.data.has_30;
        });
    },

    changeRequired(e, field) {
      this.saveLevel({
          uuid: field.uuid,
          available: e.target.checked
      }).then(response => {
         //
      });
    },

    has_30(e, field) {
          this.saveLevel({
              uuid: field.uuid,
              has_30: e.target.checked
          }).then(response => {
              //
          });
    },

      text(e, field) {

          var charCode = (e.which) ? e.which : e.keyCode;
          if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
              e.preventDefault();
          } else {
              const foundIndex = this.tableData.findIndex(function(element) {
                  return element.uuid === field.uuid;
              });

              this.tableData[foundIndex].amount = e.target.value + e.key;

              this.saveLevel({
                  uuid: field.uuid,
                  amount: e.target.value + e.key,
              }).then(response => {
                  //
              });
              return false;
          }

      },
      edit(question) {
          const foundIndex = this.tableData.findIndex(function(element) {
              return element.uuid === question.uuid;
          });
          this.tableData[foundIndex].edit = !this.tableData[foundIndex].edit;
      },
  }
};
</script>
