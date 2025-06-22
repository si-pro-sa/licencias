<template>
    <v-container>
        <v-card>
            <v-row>
                <v-col>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-text-field label="Tipo de Licencia"
                                              v-model="tipo_licencia" disabled>
                                </v-text-field>

                                <v-text-field label="Fecha de Inicio de Pedido Licencia "
                                              v-model="fecha_pedido_inicio" disabled>
                                </v-text-field>

                                <v-text-field label="Fecha de Fin de Pedido Licencia "
                                              v-model="fecha_pedido_final" disabled>
                                </v-text-field>

                                <v-text-field
                                    v-show="tipoLicencia === 11 || tipoLicencia === 21 || tipoLicencia === 22 || tipoLicencia === 23 || tipoLicencia === 24"
                                    label="Persona a Cargo o Enferma"
                                    v-model="persona" disabled>
                                </v-text-field>

                                <div v-show="tipoLicencia===18 || tipoLicencia===19">
                                    <v-text-field label="Nombre del Evento"
                                                  v-model="licencia.evento_nombre" disabled>
                                    </v-text-field>
                                    <v-text-field label="Lugar del Evento"
                                                  v-model="licencia.evento_lugar" disabled>
                                    </v-text-field>
                                    <v-text-field label="Modalidad"
                                                  v-model="licencia.modalidad" disabled>
                                    </v-text-field>

                                    <v-text-field label="Fecha de Inicio del Evento"
                                                  v-model="fecha_evento_inicio" disabled>
                                    </v-text-field>
                                    <v-text-field label="Fecha de Fin del Evento"
                                                  v-model="fecha_evento_final" disabled>
                                    </v-text-field>
                                    <v-text-field label="Razon de Participacion"
                                                  v-model="licencia.razon" disabled>
                                    </v-text-field>
                                </div>
                            </v-col>
                        </v-row>
                    </v-container>

                    <!-- borrar tan solo para ver-->
                    <div v-show="debug">
                        <p>{{visar}}</p>
                    </div>

                    <v-container v-show="this.licencia.primer_visado">
                        <v-row>
                            <v-col>
                                <v-text-field label="Visado:"
                                              v-model="primer_visado" disabled>
                                </v-text-field>
                                <v-text-field label="Fecha de Inicio Efectivo"
                                              v-model="fecha_efectiva_inicio" disabled>
                                </v-text-field>
                                <v-text-field label="Fecha de Fin Efectivo"
                                              v-model="fecha_efectiva_final" disabled>
                                </v-text-field>
                                <v-text-field label="Observacion"
                                              v-model="licencia.observacion_primera" disabled>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>

                    <v-container v-show="this.licencia.segundo_visado">
                        <v-row>
                            <v-col>
                                <v-text-field label="Segundo Visado:"
                                              v-model="segundo_visado" disabled>
                                </v-text-field>
                                <v-text-field label="Segunda Observacion:"
                                              v-model="licencia.observacion_segunda" disabled>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-container v-show="this.licencia.cuarta_visado">
                        <v-row>
                            <v-col>
                                <v-text-field label="Interrumpido"
                                              v-model="cuarta_visado" disabled>
                                </v-text-field>
                                <v-text-field label="Fecha de Inicio Interrupcion"
                                              v-model="fecha_interrupcion_inicio" disabled>
                                </v-text-field>
                                <v-text-field label="Observacion de la Interrupcion"
                                              v-model="licencia.observacion_cuarta" disabled>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-text-field label="Dias de Licencia"
                                              v-model="licencia.dias" disabled>
                                </v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>

                    <v-container>
                        <v-row>
                            <v-col class="d-flex flex-row-reverse p-5">
                                <v-btn class="mr-2" variant="outline-danger" @click="volverLicencia">
                                    <v-icon>mdi-reply</v-icon>
                                    Volver
                                </v-btn>
                                <!--                                <v-btn class="mr-2" variant="outline-warning" @click="imprimirLicencia">-->
                                <!--                                    <i class="fas fa-print"></i> Imprimir-->
                                <!--                                </v-btn>-->
                            </v-col>
                        </v-row>
                    </v-container>
                </v-col>
            </v-row>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        name: "LicenciaView",
        created() {
            console.log("tipo de licencia pasada: ", this.tipoLicencia);
            this.documento = window.location.pathname.split("/")[2];
            if (this.idlicencia !== 0) {
                this.buscarLicencia(this.idlicencia);
            }
            var pathname = window.location.pathname;
            console.log(pathname);
        },
        data: function () {
            return {
                licencia: {
                    fecha_pedido_inicio: new Date().toISOString().substr(0, 10),
                    fecha_pedido_final: new Date().toISOString().substr(0, 10),
                    fecha_efectiva_inicio: new Date().toISOString().substr(0, 10),
                    fecha_efectiva_final: new Date().toISOString().substr(0, 10),
                    fecha_evento_inicio: new Date().toISOString().substr(0, 10),
                    fecha_evento_final: new Date().toISOString().substr(0, 10),
                    fecha_interrupcion_inicio: new Date().toISOString().substr(0, 10),
                    fecha_interrupcion_final: new Date().toISOString().substr(0, 10),
                    primer_visado: false,
                    segundo_visado: false,
                    tercera_visado: false,
                    cuarta_visado: false,
                    observacion_primera: "",
                    observacion_segunda: "",
                    observacion_tercera: "",
                    observacion_cuarta: "",
                    idtipoLicencia: "",
                    idpersona: null,
                    evento_nombre: "",
                    evento_lugar: "",
                    razon: "",
                    modalidad: "",
                    resolucion: "",
                    nombre: "",
                    apellido: "",
                    dias: 0
                },
                fecha_pedido_inicio: "",
                fecha_pedido_final: "",
                fecha_efectiva_inicio: "",
                fecha_efectiva_final: "",
                fecha_evento_inicio: "",
                fecha_evento_final: "",
                fecha_interrupcion_inicio: "",
                fecha_interrupcion_final: "",
                debug: false
            };
        },
        props: {
            idlicencia: {
                type: Number
            },
            visar: {
                type: Number,
                default: 6
            },
            tipoLicencia: {
                type: Number,
                default: 1
            }
        },
        computed: {
            primer_visado() {
                return this.licencia.primer_visado ? "SI" : "NO";
            },
            segundo_visado() {
                return this.licencia.segundo_visado ? "SI" : "NO";
            },
            tercera_visado() {
                return this.licencia.tercera_visado ? "SI" : "NO";
            },
            cuarta_visado() {
                return this.licencia.cuarta_visado ? "SI" : "NO";
            },
            persona() {
                return this.licencia.nombre + " " + this.licencia.apellido;
            },
            tipo_licencia() {
                let tipoLicencia = 0;
                switch (this.tipoLicencia) {
                    case 1:
                        tipoLicencia = "Corto Tratamiento";
                        break;
                    case 2:
                        tipoLicencia = "Largo Tratamiento";
                        break;
                    case 3:
                        tipoLicencia = "Enfermedad Critica";
                        break;
                    case 4:
                        tipoLicencia = "ART";
                        break;
                    case 5:
                        tipoLicencia = "Gremial";
                        break;
                    case 6:
                        tipoLicencia = "Deportiva";
                        break;
                    case 7:
                        tipoLicencia = "Jubilacion Invalidez";
                        break;
                    case 8:
                        tipoLicencia = "Preparto";
                        break;
                    case 9:
                        tipoLicencia = "Maternidad";
                        break;
                    case 10:
                        tipoLicencia = "Postparto";
                        break;
                    case 11:
                        tipoLicencia = "Familiar Enfermo";
                        break;
                    case 12:
                        tipoLicencia = "Nacimiento";
                        break;
                    case 13:
                        tipoLicencia = "Adopcion";
                        break;
                    case 14:
                        tipoLicencia = "Fallecimiento";
                        break;
                    case 15:
                        tipoLicencia = "Sin Sueldo";
                        break;
                    case 16:
                        tipoLicencia = "Ordinaria";
                        break;
                    case 17:
                        tipoLicencia = "DLA";
                        break;
                    case 18:
                        tipoLicencia = "Capacitacion";
                        break;
                    case 19:
                        tipoLicencia = "Capacitacion Prolongada";
                        break;
                    case 20:
                        tipoLicencia = "Familiar Discapacitado";
                        break;
                    case 21:
                        tipoLicencia = "1A";
                        break;
                    case 22:
                        tipoLicencia = "1B";
                        break;
                    case 23:
                        tipoLicencia = "2A";
                        break;
                    case 24:
                        tipoLicencia = "2B";
                        break;
                    case 25:
                        tipoLicencia = "Proporcional";
                        break;
                    case 26:
                        tipoLicencia = "Postergacion";
                        break;
                    case 27:
                        tipoLicencia = "Anticipo";
                        break;
                    case 28:
                        tipoLicencia = "Examen";
                        break;
                    case 29:
                        tipoLicencia = "Obligacion Militar";
                        break;
                    case 30:
                        tipoLicencia = "Cargo Publico";
                        break;
                    case 31:
                        tipoLicencia = "Otro Causal PE";
                        break;
                    case 32:
                        tipoLicencia = "Deportiva no rentada";
                        break;
                    case 33:
                        tipoLicencia = "Inasistencia con Aviso";
                        break;
                    case 34:
                        tipoLicencia = "Inasistencia sin Aviso";
                        break;
                    default:
                        tipoLicencia = null;
                        break;
                }
                return tipoLicencia;
            }
        },
        methods: {
            imprimirLicencia() {
                window.print();
            },
            volverLicencia() {
                this.$emit("addLicencia", {licencia: 0, visar: 0});
            },
            async buscarLicencia(idlicencia) {
                await this.$store
                    .dispatch("licencia/getLicencia", idlicencia)
                    .then(() => {
                        console.log(
                            "listo para pasar la licencia traida, ",
                            Object.entries(this.$store.state.licencia.licencia)
                        );

                        this.licencia = this.$store.state.licencia.licencia;
                        console.log("licencia ", this.licencia);

                        if (this.licencia.fecha_pedido_inicio) {
                            this.fecha_pedido_inicio = this.licencia.fecha_pedido_inicio.slice(
                                0,
                                10
                            );
                            this.fecha_pedido_final = this.licencia.fecha_pedido_final.slice(
                                0,
                                10
                            );
                        }
                        if (this.licencia.fecha_efectiva_inicio) {
                            this.fecha_efectiva_inicio = this.licencia.fecha_efectiva_inicio.slice(
                                0,
                                10
                            );
                            this.fecha_efectiva_final = this.licencia.fecha_efectiva_final.slice(
                                0,
                                10
                            );
                        }
                        if (this.licencia.fecha_evento_inicio) {
                            this.fecha_evento_inicio = this.licencia.fecha_evento_inicio.slice(
                                0,
                                10
                            );
                            this.fecha_evento_final = this.licencia.fecha_evento_final.slice(
                                0,
                                10
                            );
                        }
                        if (this.licencia.fecha_interrupcion_inicio) {
                            this.fecha_interrupcion_inicio = this.licencia.fecha_interrupcion_inicio.slice(
                                0,
                                10
                            );
                            this.fecha_interrupcion_final = this.licencia.fecha_interrupcion_final.slice(
                                0,
                                10
                            );
                        }
                    })
                    .catch(err => { console.log(err);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
