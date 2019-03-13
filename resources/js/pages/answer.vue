<template>
  <div class="w-full max-w-screen-xl mx-auto px-6">
      <div class="lg:flex -mx-6">
          <Sidebar></Sidebar>
          <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">
              <!--\{{#if ok}}
              <h1>Yes</h1>
              \{{/if}}-->
              <div id="app" class="flex">
                  <div class="pt-24  lg:pt-28 w-full">
                      <div class="top-menu">
                          <div class="text-section top ">
                              <a class="back" href="#" @click="back"><&nbsp;&nbsp;Regresar</a>
                              <span class="title">&nbsp;&nbsp;{{client_data.first_name}}</span>
                          </div>
                      </div>
                  </div>
                  <br />
              </div>
              <div id="content">
                  <div>
                      <div class="text-section-2 top2 " >
                          Respuesta de preguntas
                      </div>
                      <div class="text-section pt-2">
                          Las respuestas que observas,
                          estan bajo la revisión para aprobar o denegar el préstamo solicitado,
                          favor revisar bien cada una de las resupeestas que cumplan con los lineamientos
                          previamente establecidos.
                      </div>
                  </div>
                  <div v-for="(stepsQuestion, index) in stepsQuestions" :key="index">
                      <div v-if="validate(index,stepsQuestions)" class="row-dim">
                          <br />
                          <br />
                          <h5>
                              {{stepsQuestion.steps_order}}. {{stepsQuestion.name}}
                          </h5>
                      </div>
                      <div class="row-dim-2" v-bind:class="{background:index % 2 === 0}">
                              <div class="title-col">
                                  {{ stepsQuestion.text }}
                              </div>
                              <div class="title-col-2">
                                  {{ stepsQuestion.response }} &nbsp;
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
      stepsQuestions: [],
      client_data : ""
    };
  },
  mounted: function() {
    this.loadSteps();
  },
  methods: {
    ...mapActions({
      steps: "step/steps",
      client: "step/client"
    }),
    loanDefault(type = "week") {
      this.active = 1;
      this.loan(type);
    },
    getLoans(type = "week") {
      this.loan().then(response => {
        this.loans = response.data;
      });
    },
    loadSteps() {
      this.steps(this.$route.params.uuid).then(response => {
        this.stepsQuestions = response.data;
      });
      this.client(this.$route.params.uuid).then(response => {
        this.client_data = response.data;
      });
    },
    back() {
      this.$router.go(-1);
    },
    validate(index, stepsQuestions) {
      if (index === 0) {
        return true;
      } else {
        return (
          stepsQuestions[index - 1] !== undefined &&
          stepsQuestions[index].name !== stepsQuestions[index - 1].name
        );
      }
    }
  }
};
</script>
