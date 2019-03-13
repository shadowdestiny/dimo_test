<template>
    <div id="sidebar" style="position:relative" class="hidden absolute z-90 top-16 bg-white w-full border-b -mb-16 lg:-mb-0 lg:static lg:bg-transparent lg:border-b-0 lg:pt-0 lg:w-1/4 lg:block lg:border-0 xl:w-1/5">
        <div class="lg:block lg:relative lg:sticky lg:top-16 float-section">
            <nav id="nav" class="px-6 pt-6 overflow-y-auto text-base lg:text-sm lg:py-12 lg:pl-6 lg:pr-8 sticky?lg:h-(screen-16)">
                <div class="mb-8">

                    <div class="flex logo">
                        <div class="flex-1 text-left px-4 py-2 m-2 left-logo">
                            <img :src="require('img/sidebar/icons/D-icon.png')" alt="Logo Diimo" />
                        </div>

                        <div class="flex-1 text-left px-4 py-2 m-2 right-logo">
                            <div class="title-1">
                                Super Administrador
                            </div>
                            <div class="title-2">
                                {{ username }}
                            </div>
                        </div>
                    </div>

                    <br />

                    <ul class="diimo-ul">
                        <li class="mb-3 lg:mb-2" v-bind:class="{selected:active === 1}">
                            <a class="hover:underline text-grey-darkest" @click="select(1)">
                                <div>
                                    <div class="image">
                                        <img :src="require('img/sidebar/icons/2x/dashboard@2x.png')" alt="Logo Diimo">
                                    </div>
                                    <div class="text">
                                        Dashboard
                                    </div>

                                </div>
                            </a>
                        </li>
                        <li class="mb-3 lg:mb-2" v-bind:class="{selected:active === 5}">
                            <a class="hover:underline text-grey-darkest"   @click="select(5)">
                                <div>
                                    <div class="image">
                                        <img :src="require('img/sidebar/icons/2x/users@2x.png')" alt="Logo Diimo">
                                    </div>
                                    <div class="text">
                                        Usuarios
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-3 lg:mb-2" v-bind:class="{selected:active === 3}">
                            <a class="hover:underline text-grey-darkest"  @click="select(3)">
                                <div>
                                    <div class="image">
                                        <img :src="require('img/sidebar/icons/2x/register@2x.png')" alt="Logo Diimo">
                                    </div>
                                    <div class="text">
                                        Listado de Registros
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-3 lg:mb-2" v-bind:class="{selected:active === 6}">
                            <a class="hover:underline text-grey-darkest"   @click="select(6)">
                                <div>
                                    <div class="image">
                                        <img :src="require('img/sidebar/icons/2x/creditos@5x.png')" alt="Logo Diimo"/>
                                    </div>
                                    <div class="text">
                                        Créditos
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-3 lg:mb-2" v-bind:class="{selected:active === 2}">
                            <a class="hover:underline text-grey-darkest"   @click="select(2)">
                                <div>
                                    <div class="image">
                                        <img :src="require('img/sidebar/icons/2x/questions@2x.png')" alt="Logo Diimo">
                                    </div>
                                    <div class="text">
                                        Preguntas
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="mb-3 lg:mb-2" v-bind:class="{selected:active === 4}">
                            <a class="hover:underline text-grey-darkest"  @click="select(4)">
                                <div>
                                    <div class="image">
                                        <img :src="require('img/sidebar/icons/2x/settings@2x.png')" alt="Logo Diimo">
                                    </div>
                                    <div class="text">
                                        Configuración
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <div class="footer-menu">
                <ul class="diimo-ul">
                    <li class="mb-3 lg:mb-2">
                        <a class="hover:underline text-grey-darkest" href="#"
                           @click.prevent="logout">
                            <div>
                                <div class="image">
                                    <img :src="require('img/sidebar/icons/2x/log_out.png')" alt="Logo Diimo">
                                </div>
                                <div class="text">
                                    Cerrar Sesión
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import store from "@/store";
export default {
  name: "Sidebar",
  middleware: "auth",
  data() {
    return {
      username: "",
      active: 1
    };
  },
  mounted: function() {
    try {
      this.username = store.getters["auth/user"].name;
    } catch (e) {
      this.$router.replace({
        name: "login"
      });
    }

    if (this.page !== undefined) this.active = this.page;
  },
  props: ["page"],
  methods: {
    async logout() {
      // Log out the user.
      await this.$store.dispatch("auth/logout");
      // Redirect to login.
      this.$router.replace({
        name: "login"
      });
    },

    select(page) {
      this.active = page;
      switch (page) {
        case 1:
          this.$router.push({
            name: "home"
          });
          break;
        case 2:
          this.$router.push({
            name: "step"
          });
          break;
        case 3:
          this.$router.push({
            name: "record_list"
          });
          break;
        case 4:
          this.$router.push({
            name: "configuration"
          });
          break;
        case 5:
          this.$router.push({
            name: "client_list",
            params: {
              status: "all"
            }
          });
          break;
        case 6:
          this.$router.push({
            name: "loan_list",
            params: {
              status: "all"
            }
          });
          break;
      }
    }
  }
};
</script>
