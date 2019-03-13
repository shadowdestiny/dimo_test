<template>
    <div class="w-full max-w-screen-xl mx-auto px-6">

        <div class="lg:flex -mx-6">

            <Sidebar :page="3"></Sidebar>

            <div id="content-wrapper" class="min-h-screen w-full lg:static lg:max-h-full lg:overflow-visible lg:w-3/4 xl:w-4/5">

                <div id="app" class="flex">
                    <div class="pt-24 pb-8 lg:pt-28 w-full">
                        <div class="top-menu">
                            <div class="flex flex-wrap menu-right-top">
                                <div class="w-full md:w-1/5 bg-grey p-4 text-center text-grey-lighter" v-bind:class="{selected:active === 1}">
                                    <div @click="loanDefault('week')">
                                        ÚLTIMA SEMANA
                                    </div>
                                </div>
                                <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker" v-bind:class="{selected:active === 2}">
                                    <div @click="loan30()">
                                        ÚLTIMOS 30 DIAS
                                    </div>
                                </div>
                                <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker" v-bind:class="{selected:active === 3}">
                                    <div @click="loanYear()">
                                        ÚLTIMOS AÑO
                                    </div>
                                </div>
                                <div class="w-full md:w-1/5 bg-grey-light p-4 text-center text-grey-darker" v-bind:class="{selected:active === 4}">
                                    <div @click="loanDate()">
                                        RANGO DE FECHAS
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="content" class="pt-12">

                    <div class="flex flex-wrap range-position" v-if="active===4">
                        <div class="w-full md:w-1/2">
                            <date-picker v-model="range" range lang="es"  format="MM-DD-YYYY"></date-picker>
                        </div>
                        <div class="w-full md:w-1/2" style="padding-top:0px;">
                            <button @click="filter" >Filtrar</button>
                        </div>
                    </div>

                    <div class="box pt-4">

                        <v-client-table
                                :data="tableData"
                                :columns="columns"
                                :options="options">

                            <select slot="status_description" slot-scope="props" title="" v-on:change="changeStatus($event,props.row.uuid)" :value=props.row.status>
                                <option value="initial">Pendiente</option>
                                <option value="available">Disponible</option>
                                <option value="in_process">En progreso</option>
                                <option value="approved">Aprobado</option>
                                <option value="active">Activo</option>
                                <option value="rejected">Rechazado</option>
                                <option value="completed">Completado</option>
                            </select>

                            <input slot="comment" slot-scope="props" type="text" title="Comentario" v-on:keyup="comment($event,props.row.uuid)" :value="props.row.comment"/>

                            <a slot="action" slot-scope="props" v-on:click="view(props.row.uuid)" class="style-link">
                                Ver
                            </a>

                        </v-client-table>

                    </div>

                    <br />

                    <Footer></Footer>

                </div>

            </div>

        </div>

    </div>
</template>

<script>
    import MonthlyIncome from '../components/MonthlyIncome'
    import { mapActions } from 'vuex';
    import Content from "../layouts/partials/content";
    import Sidebar from "../layouts/partials/sidebar";
    import Footer from "../layouts/partials/footer";
    import * as axios from "axios";
    import DatePicker from 'vue2-datepicker'
    import VueMoment from 'vue-moment'
    import moment from 'moment'
    export default {
        components: {
            Sidebar,
            Content,
            Footer,
            MonthlyIncome,
            DatePicker,
        },
        /*middleware: "auth",*/
        data() {
            return {
                client_row : [],
                active : 0,
                type : 'week',
                range:'',

                columns: [
                    'dui',
                    'first_name',
                    'last_name',
                    'email',
                    'number_phone',
                    'client_created_at',
                    'status_description',
                    'comment',
                    'action',
                ],
                tableData: [

                ],
                options: {
                    headings: {
                        'dui'                   : 'DUI',
                        'first_name'            : 'PRIMER NOMBRE',
                        'last_name'             : 'SEGUNDO NOMBRE',
                        'email'                 : 'CORREO',
                        'number_phone'          : 'TELÉFONO',
                        'client_created_at'     : 'FECHA DE CREACIÓN',
                        'status_description'    : 'ESTATUS',
                        'comment'               : 'COMENTARIO',
                        'action'                : 'RESPUESTA',
                    },
                    /*sortable: [
                     'creation date',
                     'dui',
                     'first name',
                     'last name',
                     'phone',
                     'ranking',
                     'status',
                     'action'
                     ],*/
                    texts: {
                        count: 'Mostrando {from} a {to} de {count} registros|{count} registros|Un registro',
                        first: 'Primero',
                        last: 'Último',
                        filter: "",
                        filterPlaceholder: "Buscar usuario",
                        limit: "Cantidad a mostrar:",
                        page: "Página:",
                        noResults: "No se ha encontrado registro",
                        filterBy: "Filtro por {column}",
                        loading: 'Cargando...',
                        defaultOption: 'Seleccionado {column}',
                        columns: 'Columnas'
                    },
                }
            }
        },
        mounted:  function(){
            this.active = 1;
            this.loan();
        },
        created () {
            //this.fillData()
        },
        methods: {
            ...mapActions({
                clients: "client/clients",
                saveClient: "client/saveRecord",
            }),

            async loan30(){
                this.active = 2;
                this.loan('month');
            },

            async loanYear(){
                this.active = 3;
                this.loan('year');
            },

            async loanDate(){
                this.active = 4;
            },

            async loanDefault(type = 'week') {
                this.active = 1;
                this.loan(type);
            },

            loan(type = 'week') {
                const params = {
                     type : type,
                     status : "all"
                };

                this.clients(params).then((response)=>{
                    this.client_row = response.data;

                    let tempDataTable = [];

                    response.data.forEach(function(element){
                        tempDataTable.push({
                            'uuid'                  : element.uuid,
                            'dui'                   : element.dui,
                            'first_name'            : element.first_name,
                            'last_name'             : element.last_name,
                            'email'                 : element.email,
                            'number_phone'          : element.number_phone,
                            'client_created_at'     : element.client_created_at ,
                            'status_description'    : element.status_description,
                            'comment'               : element.comment,
                            'action'                : element.action,
                            'status'                : element.status,
                            'isUpdate'              : false,
                        });
                    });

                    this.tableData = tempDataTable;

                });
            },

            fillData () {


            },

            client_list(){
                this.$router.push({
                    name: "client_list"
                });
            },

            view(uuid){
                this.$router.push({
                    name: "answer",
                    params : {
                        uuid : uuid
                    }
                });
            },

            async comment(e,uuid){
                this.saveClient({
                    uuid : uuid,
                    comment : e.target.value
                }).then((response)=>{
                    //
                });
            },

            async changeStatus(e,uuid){

                this.saveClient({
                    uuid : uuid,
                    status : e.target.value
                }).then((response)=>{
                    //
                });
            },
            filter(){
                const start = this.formatDate(this.range[0]);
                const end = this.formatDate(this.range[1]);
                this.loan(start + '|' + end);
            },
            formatDate(today){

                let dd = today.getDate();
                let mm = today.getMonth()+1; //January is 0!

                let yyyy = today.getFullYear();
                if(dd<10){
                    dd='0'+dd;
                }
                if(mm<10){
                    mm='0'+mm;
                }
                return yyyy+'-'+mm+'-'+dd;
            },
        }
    };
</script>
