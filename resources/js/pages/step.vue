<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">

      <div class="lg:flex -mx-6">

          <Sidebar :page="2"></Sidebar>

          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">

              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top ">
                              Preguntas para aprobación de préstamos
                              <!-- <div class="section-top-button">
                                  <button class="top-menu" @click="show">
                                      <div>
                                          <div>
                                              +
                                          </div>
                                      </div>
                                      &nbsp;&nbsp;
                                      Agregar pasos
                                  </button>
                              </div> -->
                          </div>
                      </div>
                  </div>

                  <br />

              </div>

              <div id="content">

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
                                  Escribe el nombre del nuevo paso
                              </div>
                              <div class="pt-4">
                                  <input v-bind:class="{enabled:modal_form.enabled === true}" type="text" placeholder="¿En que nivel de estudio estas?" v-model="modal_form.step" v-on:keyup="go">
                              </div>

                              <div class="pt-4">
                                  <input v-bind:class="{enabled:modal_form.enabled === true}" type="number" placeholder="Orden" v-model="modal_form.order" v-on:keyup="go">
                              </div>

                              <div class="pt-24 section-button modal">
                                  <div class="section-grid-button modal">
                                      <button @click="add" v-bind:class="{enabled:modal_form.enabled === true}">Crear paso</button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </modal>

                  <div>
                      <div class="text-section top2 ">
                          Lista de pasos
                      </div>

                      <div class="text-section pt-2">
                          Crear un listado de pasos para poder realizar aprobación del préstamo solicitado, tienes un máximo de <b>10 pasos</b> para poder agregar.
                      </div>
                  </div>

                  <div class="box pt-4">
                      <div class="text-section box-text" v-for="step in steps">
                          <span v-if="step.isUpdate === false">
                              Paso {{ step.order}} - {{ step.name}}
                          </span>
                          <span v-if="step.isUpdate === true">
                              Paso {{ step.order}} <input type="text" :value="step.name" v-on:keyup="text($event,step)"/>
                          </span>

                          <div class="tool">
                              <div class="edit" @click="edit(step)">Editar</div>
                              <!--<div class="drop" @click="show_verify(step)">Borrar</div>-->
                              <div class="other" @click="view_question(step)">Ver</div>
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
import * as axios from "axios";
export default {
  components: {
    Sidebar,
    Footer
  },
  data() {
    return {
      step_to_delete: {},
      steps: [],
      modal_form: {
        step: "",
        order: "0",
        enabled: false
      }
    };
  },
  mounted: function() {
    this.getSteps();
  },
  created() {
    //this.fillData()
  },
  methods: {
    ...mapActions({
      listSteps: "step/listSteps",
      addStep: "step/addStep",
      editStep: "step/editStep",
      dropStep: "step/dropStep"
    }),

    getSteps() {
      this.listSteps().then(response => {
        this.steps = response.data;
        let arraySteps = [];
        response.data.forEach(function(element) {
          arraySteps.push({
            isUpdate: false,
            uuid: element.uuid,
            created_at: element.created_at,
            name: element.name,
            order: element.order,
            updated_at: element.updated_at
          });
        });

        this.steps = arraySteps;
      });
    },

    view_question(step) {
      this.$router.push({
        name: "question",
        params: {
          question_uuid: step.uuid
        }
      });
    },

    go() {
      this.modal_form.enabled = this.modal_form.step.length > 0;
    },

    add() {
      this.addStep({
        name: this.modal_form.step,
        order: this.modal_form.order
      }).then(response => {
        this.hide();
        this.getSteps();
      });
    },

    show_verify(step) {
      this.step_to_delete = step;
      this.$modal.show("verify-modal");
    },
    hide_verify() {
      this.$modal.hide("verify-modal");
    },

    show() {
      this.$modal.show("question-modal");
    },

    hide() {
      this.$modal.hide("question-modal");
    },

    edit(step) {
      const foundIndex = this.steps.findIndex(function(element) {
        return element.uuid === step.uuid;
      });

      this.steps[foundIndex].isUpdate = !this.steps[foundIndex].isUpdate;
    },

    text(e, step) {
      const foundIndex = this.steps.findIndex(function(element) {
        return element.uuid === step.uuid;
      });

      this.steps[foundIndex].name = e.target.value;

      this.editStep({
        uuid: step.uuid,
        name: e.target.value
      }).then(response => {
        //
      });
    },

    deleteRow() {
      this.dropStep({
        uuid: this.step_to_delete.uuid
      }).then(response => {
        this.hide_verify();
        this.getSteps();
      });
    }
  }
};
</script>
