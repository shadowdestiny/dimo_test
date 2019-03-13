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
                <div class="h-20 px-6 pt-8 flex-1 mb-6">
                    <div class="flex flex-col h-24">
                        <label for="username"
                               class="mb-2">Login de usuario</label>
                        <input id="username"
                               name="username"
                               v-validate="'required'"
                               v-model="form.username"
                               :class="errors.has('username') ? 'has-error' : 'focus:border-green'"
                               type="text"
                               class="appearance-none border rounded px-4 h-10 outline-none"
                               autocomplete="off">
                        <small class="pt-1 pb-0 mb-0 pt-2" v-bind:class="{'text-error':response === false}">{{ errors.first('username') || message }}</small>
                    </div>

                </div>
                <div class="flex justify-between items-center px-6 pb-2 pt-2">

                    <div>
                        <button type="submit"
                                class="bg-grey-lighter text-white px-4 py-3 rounded font-medium hover:bg-green active:bg-green no-outline">Recuperar</button>
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
      response : false,
      form: {
        username: "",
        password: ""
      },
    };
  },
  methods: {
    ...mapActions({
        recover: "auth/recovery"
    }),

    async submit() {
      try {
        const validate = await this.$validator.validateAll();
        if (validate) {
          this.recover(this.form).then((response)=>{
              this.response = true;
              this.message = response.data.message;
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
    recovery(){
        this.$router.push({
            name: "recovery"
        });
    }
  }
};
</script>
