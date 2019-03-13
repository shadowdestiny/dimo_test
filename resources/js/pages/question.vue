<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">
      <div class="lg:flex -mx-6">
          <Sidebar :page="2"></Sidebar>
          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">
              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top flex">
                              <a class="back mr-4" href="#" @click.prevent="back"><&nbsp;&nbsp;Regresar</a>
                              <p>Preguntas para aprobación de préstamos</p>
                          </div>
                      </div>
                  </div>
                  <br />
              </div>
              <div id="content">
                  <div>
                      <div class="text-section top2 ">
                          Lista de preguntas
                      </div>

                      <div class="text-section pt-2">
                          Crear un listado de pasos para poder realizar aprobación del préstamo solicitado, tienes un máximo de <b>10 pasos</b> para poder agregar.
                      </div>
                  </div>
                  <div class="flex">
                      <div class="w-full md:w-1/1">
                          <div class="section-grid-button" >
                              <button @click="show">
                                  <div>
                                      <div>
                                          +
                                      </div>
                                  </div>
                                  Agregar pregunta
                              </button>
                          </div>
                      </div>
                  </div>
                  <modal name="verify-modal" class="default-modal">
                      <div class="step-format">
                          <div class="button-modal-close" @click="hide_verify">
                              <div>
                                  <div>x</div>
                              </div>
                          </div>
                          <div>
                              <div class="text-section modal pt-12 center">
                                  Estas apunto de eliminar un item, ¿estás de acuerdo con esta acción?
                              </div>
                              <div class="pt-24 section-button modal">
                                  <div class="section-grid-button modal">
                                      <div class="flex flex-wrap">
                                          <div class="w-full md:w-1/2">
                                              <button @click="deleteRow" class="delete">Si</button>
                                          </div>
                                          <div class="w-full md:w-1/2">
                                              <button @click="hide_verify" class="enabled">No</button>
                                          </div>
                                      </div>

                                  </div>
                              </div>
                          </div>
                      </div>
                  </modal>
                  <modal name="question-modal" class="default-modal">
                      <div class="step-format">
                          <div class="button-modal-close" @click="hide">
                              <div>
                                  <div>x</div>
                              </div>
                          </div>
                          <div>
                            <div class="text-section modal">
                                Escribe tu pregunta
                            </div>
                            <div class="pt-4">
                                <input v-bind:class="{enabled:modal_form.enabled === true}" type="text" placeholder="¿En que nivel de estudio estas?" v-model="modal_form.question" v-on:keyup="go">
                            </div>
                            <label class="text-section modal pt-4">
                                Tipo de respuesta
                            </label>
                            <div class="pt-1">
                                <select ref="question_type" title="" v-model="modal_form.type" v-bind:class="{enabled:modal_form.enabled === true}">
                                    <option value="SINGLE">Simple</option>
                                    <option value="CHECK">Check</option>
                                    <option value="LARGE_TEXT">Texto largo</option>
                                    <option value="PHONE">Teléfono</option>
                                    <option value="MULTIPLE">Multiple</option>
                                    <option value="SIMPLE_TEXT">Texto simple</option>
                                    <option value="NUMBER_HYPHEN">Número guíon</option>
                                    <option value="LARGE_TEXT">Texto largo</option>
                                    <option value="EMAIL">Correo</option>
                                    <option value="DATE">Fecha</option>
                                    <option value="DUI">DUI</option>
                                    <option value="NIT">NIT</option>
                                </select>
                            </div>
                            <div class="pt-6 section-button modal">
                                  <div class="section-grid-button modal">
                                      <button @click="add" v-bind:class="{enabled:modal_form.enabled === true}">Crear pregunta</button>
                                  </div>
                            </div>
                          </div>
                      </div>
                  </modal>
                  <div class="box pt-4">
                      <v-client-table
                              :data="tableData"
                              :columns="columns"
                              :options="options">
                          <select slot="type" slot-scope="props" title="" v-on:change="changeType($event,props.row)" :value=props.row.type>
                              <option value="SINGLE">Simple</option>
                              <option value="CHECK">Check</option>
                              <option value="LARGE_TEXT">Texto largo</option>
                              <option value="PHONE">Teléfono</option>
                              <option value="MULTIPLE">Multiple</option>
                              <option value="SIMPLE_TEXT">Texto simple</option>
                              <option value="NUMBER_HYPHEN">Número guíon</option>
                              <option value="LARGE_TEXT">Texto largo</option>
                              <option value="EMAIL">Correo</option>
                              <option value="DATE">Fecha</option>
                              <option value="DUI">DUI</option>
                              <option value="NIT">NIT</option>
                          </select>
                          <div slot="text" slot-scope="props">
                              <div v-if="props.row.edit === false">
                                  {{ props.row.text }}
                              </div>
                              <div v-if="props.row.edit === true">
                                  <input type="text" :value="props.row.text" v-on:keyup="text($event,props.row)"/>
                              </div>
                          </div>
                          <div slot="required" slot-scope="props">
                              <input type="checkbox"
                                     name="required"
                                     id="required"
                                     :checked="props.row.required == 1 ? true : false"
                                     :value="props.row.required"
                                     @click="changeRequired($event, props.row)">
                          </div>
                          <div slot="column_edit" slot-scope="props" class="edit-independent" @click="edit(props.row)">Editar</div>
                          <div slot="column_delete" slot-scope="props" class="other-independent" @click="show_verify(props.row)">Borrar</div>
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
import vmodal from "vue-js-modal";
import Vue from "vue";
Vue.use(vmodal);
export default {
  components: {
    Sidebar,
    Footer
  },
  data() {
    return {
      question_to_delete: {},
      modal_form: {
        question: "",
        type: "SINGLE",
        enabled: false
      },
      required: [],
      questions: [],
      columns: ["text", "type", "required", "column_edit", "column_delete"],
      tableData: [],
      options: {
        headings: {
          text: "PREGUNTA",
          type: "TIPO",
          required: "Obligatorio",
          column_edit: "EDITAR",
          column_delete: "ELIMINAR"
        },
        texts: {
          count:
            "Mostrando {from} a {to} de {count} registros|{count} registros|Un registro",
          first: "Primero",
          last: "Último",
          filter: "",
          filterPlaceholder: "Buscar usuario",
          limit: "Cantidad a mostrar:",
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
    this.getQuestions();
  },
  methods: {
    ...mapActions({
      listSteps: "step/listSteps",
      getQuestion: "question/list",
      updateQuestion: "question/update",
      deleteQuestion: "question/delete",
      addQuestion: "question/add"
    }),

    getQuestions() {
      this.getQuestion(this.$route.params.question_uuid).then(response => {
        let questions_modify = [];
        response.data.forEach(function(element) {
          questions_modify.push({
            edit: false,
            required: element.required,
            uuid: element.uuid,
            text: element.text,
            type: element.type
          });
        });

        this.questions = questions_modify;
        this.tableData = questions_modify;
      });
    },

    edit(question) {
      const foundIndex = this.questions.findIndex(function(element) {
        return element.uuid === question.uuid;
      });
      this.questions[foundIndex].edit = !this.questions[foundIndex].edit;
    },

    deleteRow() {
      this.deleteQuestion({
        uuid: this.question_to_delete.uuid
      }).then(response => {
        this.hide_verify();
        this.getQuestions();
      });
    },

    text(e, question) {
      const foundIndex = this.questions.findIndex(function(element) {
        return element.uuid === question.uuid;
      });

      this.questions[foundIndex].text = e.target.value;

      this.updateQuestion({
        uuid: question.uuid,
        text: e.target.value,
        required: question.required
      }).then(response => {
        //
      });
    },

    changeType(e, question) {
      this.updateQuestion({
        uuid: question.uuid,
        type: e.target.value,
        required: question.required
      }).then(response => {
        this.getQuestions();
      });
    },
    changeRequired(e, question) {
      console.log(!e.target.value);
      this.updateQuestion({
        uuid: question.uuid,
        type: question.type,
        required: e.target.value == 1 ? 0 : 1
      }).then(response => {
        this.getQuestions();
      });
    },

    add() {
      this.addQuestion({
        text: this.modal_form.question,
        type: this.modal_form.type,
        step_uuid: this.$route.params.question_uuid,
        order: 0,
        is_profile: false,
        required: false
        //order       : this.$refs["question_order"].value,
        //is_profile  : this.$refs["question_id_profile"].checked,
      }).then(response => {
        this.hide();
        this.getQuestions();
      });
    },

    show() {
      this.$modal.show("question-modal");
    },
    hide() {
      this.$modal.hide("question-modal");
    },
    show_verify(question) {
      this.question_to_delete = question;
      this.$modal.show("verify-modal");
    },
    hide_verify() {
      this.$modal.hide("verify-modal");
    },
    go() {
      this.modal_form.enabled = this.modal_form.question.length > 0;
    },
    back() {
      this.$router.go(-1);
    }
  }
};
</script>
