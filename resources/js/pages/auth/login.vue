<template>
    <div class="w-1/4">
        <div class="mb-8">
            <h1 class="mb-2">Bienvenido</h1>
            <p class="text-sm tracking-medium leading-normal">Bienvenido a la plataforma Diimo, en donde te ayudaremos a encontrar todos los datos relacionados con préstamos activos, detalle de usuarios activos, movimientos de cuenta, entre otros.</p>
        </div>
        <div class="border rounded flex flex-col">
            <div class="bg-green-lighter py-5 px-8">
                <img :src="require('img/logo-diimo.png')" alt="Logo Diimo">
            </div>
            <form @submit.prevent="submit">
                <div class="h-48 px-6 pt-8 flex-1 mb-6">
                    <div class="flex flex-col h-24">
                        <label for="username"
                               class="mb-2">Nombre de usuario</label>
                        <input id="username"
                               name="username"
                               v-validate="'required'"
                               v-model="form.username"
                               :class="[errors.has('username') ? 'has-error' : 'focus:border-green',enable]"
                               type="text"
                               class="appearance-none border rounded px-4 h-10 outline-none"
                               autocomplete="off" v-on:keyup="go">
                        <small class="text-error pt-1 pb-0 mb-0">{{ errors.first('username') || message }}</small>
                    </div>
                    <div class="flex flex-col h-24">
                        <label for="password"
                            class="mb-2">Contraseña</label>
                        <input id="password"
                            name="password"
                            v-validate="'required'"
                            v-model="form.password"
                            :class="[errors.has('password') ? 'has-error' : 'focus:border-green',enable]"
                            type="password"
                            class="appearance-none border rounded px-4 h-10 outline-none"
                            autocomplete="off" v-on:keyup="go">
                        <small class="text-error py-1">{{ errors.first('password') }}</small>
                    </div>
                </div>
                <div class="flex justify-between items-center px-6 pb-8 pt-2">
                    <div>
                        <a href="#"
                           class="no-underline text-black" @click="recovery">¿Olvidaste tu contraseña?</a>
                    </div>
                    <div>
                        <button type="submit"
                                :class="enable"
                                class="bg-grey-lighter text-white px-4 py-3 rounded font-medium hover:bg-green active:bg-green no-outline">Iniciar Sesión</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  layout: "auth",
  middleware: "guest",
  data() {
    return {
      message: "",
      form: {
        username: "",
        password: ""
      },
      enable: false
    };
  },
  methods: {
    ...mapActions({
      login: "auth/login"
    }),
    async submit() {
      try {
        const validate = await this.$validator.validateAll();
        if (validate) {
          await this.login(this.form);
          this.$router.replace({
            name: "home"
          });
        }
      } catch (e) {
        console.log(e);
        this.message = e;
      }
    },
    clean() {
      this.message = null;
    },
    recovery() {
      this.$router.push({
        name: "recovery"
      });
    },
    go() {
      if (this.form.username.length > 0 && this.form.password.length > 0) {
        this.enable = "selection_enable";
      } else {
        this.enable = "";
      }
    }
  }
};
</script>
