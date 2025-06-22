<!-- src/components/licencia/Formulario04.vue -->
<template>
    <v-container
        fluid
        class="formulario-container"
    >
        <v-card
            class="glass-card"
            elevation="0"
        >
            <!-- Header -->
            <v-card-title class="header-section">
                <v-row
                    align="center"
                    no-gutters
                >
                    <v-col
                        cols="auto"
                        class="mr-4"
                    >
                        <v-img
                            src="@/assets/logo-rrhh.png"
                            max-width="100"
                            contain
                        ></v-img>
                    </v-col>
                    <v-col>
                        <h2 class="text-h5 font-weight-bold text-center mb-2">
                            FORMULARIO DE JUSTIFICACIÓN DE INASISTENCIAS
                            LABORALES
                        </h2>
                        <v-row dense>
                            <v-col cols="auto">
                                <v-chip
                                    small
                                    outlined
                                    >Código: DGRHS.SOC.F-04</v-chip
                                >
                            </v-col>
                            <v-col cols="auto">
                                <v-chip
                                    small
                                    outlined
                                    >Fecha Vigencia: 13/09/2019</v-chip
                                >
                            </v-col>
                            <v-col cols="auto">
                                <v-chip
                                    small
                                    outlined
                                    >Versión: 02</v-chip
                                >
                            </v-col>
                            <v-col cols="auto">
                                <v-chip
                                    small
                                    outlined
                                    >Fecha de Revisión: 03/10/2022</v-chip
                                >
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-card-text>
                <!-- Datos del Agente -->
                <v-row>
                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-card
                            outlined
                            class="pa-4 mb-4"
                        >
                            <v-card-title
                                class="text-subtitle-1 font-weight-bold"
                            >
                                Datos del Agente
                            </v-card-title>
                            <v-row dense>
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="formData.entidadExaminadora"
                                        label="Entidad examinadora"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-menu
                                        ref="fechaExamenMenu"
                                        v-model="fechaExamenMenu"
                                        :close-on-content-click="false"
                                        max-width="290"
                                        min-width="290"
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-text-field
                                                v-model="formData.fechaExamen"
                                                label="Fecha"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            ></v-text-field>
                                        </template>
                                        <v-date-picker
                                            v-model="formData.fechaExamen"
                                            @change="fechaExamenMenu = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="formData.apellidoNombre"
                                        label="Apellido y Nombre"
                                        outlined
                                        dense
                                        :error-messages="errors.apellidoNombre"
                                        hide-details="auto"
                                    ></v-text-field>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-text-field
                                        v-model="formData.dni"
                                        label="DNI"
                                        outlined
                                        dense
                                        :error-messages="errors.dni"
                                        hide-details="auto"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field
                                        v-model="formData.reparticionOrigen"
                                        label="Repartición de origen"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </v-col>
                            </v-row>
                        </v-card>
                    </v-col>

                    <!-- Modalidad de Atención -->
                    <v-col
                        cols="12"
                        md="6"
                    >
                        <v-card
                            outlined
                            class="pa-4 mb-4"
                        >
                            <v-card-title
                                class="text-subtitle-1 font-weight-bold"
                            >
                                Modalidad de Atención
                            </v-card-title>
                            <v-radio-group
                                v-model="formData.modalidadAtencion"
                                row
                                dense
                            >
                                <v-radio
                                    label="CONSULTORIO"
                                    value="CONSULTORIO"
                                ></v-radio>
                                <v-radio
                                    label="JUNTA MÉDICA"
                                    value="JUNTA_MEDICA"
                                ></v-radio>
                            </v-radio-group>
                        </v-card>
                    </v-col>
                </v-row>

                <!-- Justificación -->
                <v-card
                    outlined
                    class="pa-4 mb-4"
                >
                    <v-card-title class="text-subtitle-1 font-weight-bold">
                        Justificación
                    </v-card-title>
                    <v-row dense>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="formData.diasJustificar"
                                label="Días a justificar"
                                type="number"
                                outlined
                                dense
                                :error-messages="errors.diasJustificar"
                                hide-details="auto"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-text-field
                                v-model="formData.articulo"
                                label="Artículo"
                                outlined
                                dense
                                hide-details="auto"
                            ></v-text-field>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-menu
                                ref="fechaDesdeMenu"
                                v-model="fechaDesdeMenu"
                                :close-on-content-click="false"
                                max-width="290"
                                min-width="290"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="formData.fechaDesde"
                                        label="Fecha desde"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        outlined
                                        dense
                                        :error-messages="errors.fechaDesde"
                                        hide-details="auto"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="formData.fechaDesde"
                                    @change="fechaDesdeMenu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-menu
                                ref="fechaHastaMenu"
                                v-model="fechaHastaMenu"
                                :close-on-content-click="false"
                                max-width="290"
                                min-width="290"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="formData.fechaHasta"
                                        label="Fecha hasta"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        outlined
                                        dense
                                        :error-messages="errors.fechaHasta"
                                        hide-details="auto"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="formData.fechaHasta"
                                    @change="fechaHastaMenu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-menu
                                ref="fechaAltaMenu"
                                v-model="fechaAltaMenu"
                                :close-on-content-click="false"
                                max-width="290"
                                min-width="290"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="formData.fechaAlta"
                                        label="Fecha de alta"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="formData.fechaAlta"
                                    @change="fechaAltaMenu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col
                            cols="12"
                            md="6"
                        >
                            <v-menu
                                ref="fechaControlMenu"
                                v-model="fechaControlMenu"
                                :close-on-content-click="false"
                                max-width="290"
                                min-width="290"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="formData.fechaControl"
                                        label="Fecha de control"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="formData.fechaControl"
                                    @change="fechaControlMenu = false"
                                ></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                </v-card>

                <!-- Observaciones -->
                <v-card
                    outlined
                    class="pa-4 mb-4"
                >
                    <v-card-title class="text-subtitle-1 font-weight-bold">
                        Observaciones
                    </v-card-title>
                    <v-textarea
                        v-model="formData.observaciones"
                        outlined
                        rows="4"
                        auto-grow
                        hide-details="auto"
                    ></v-textarea>
                </v-card>

                <!-- Notas -->
                <v-alert
                    type="warning"
                    outlined
                    class="mb-4"
                >
                    LAS LICENCIAS POR ENFERMEDAD SON INCOMPATIBLES CON EL
                    DESEMPEÑO DE CUALQUIER OTRA ACTIVIDAD PROFESIONAL PÚBLICA O
                    PRIVADA
                </v-alert>

                <v-alert
                    type="info"
                    outlined
                    class="mb-4"
                >
                    Se le otorga licencia médica condicionada a la verificación
                    del efectivo cumplimiento de reposo, informando al agente
                    que se le realizará visitas domiciliarias en la dirección
                    denunciada en la solicitud de licencia emitida por la
                    repartición de origen. Dejando constancia de la misma al
                    momento de realizarse, de no encontrarse al momento de la
                    visita domiciliaria deberá acreditar en un plazo de 2 (dos)
                    días hábiles el motivo de la ausencia ante el departamento
                    de salud ocupacional bajo apercibimiento de pasar las
                    actuaciones a la dirección de asuntos disciplinarios.
                </v-alert>

                <!-- Footer -->
                <v-card-text class="text-center text-caption grey--text">
                    DIRECCIÓN GENERAL DE RECURSOS HUMANOS EN SALUD – SI.PRO.SA –
                    VIRGEN DE LA MERCED 189. SAN MIGUEL DE TUCUMÁN
                </v-card-text>

                <!-- Acciones -->
                <v-card-actions class="justify-end">
                    <v-btn
                        text
                        @click="resetForm"
                        class="mr-2"
                    >
                        Limpiar
                    </v-btn>
                    <v-btn
                        color="primary"
                        :loading="loading"
                        @click="submitForm"
                    >
                        Guardar
                    </v-btn>
                </v-card-actions>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
export default {
    name: 'Formulario04',
    data() {
        return {
            errors: {},
            fechaExamenMenu: false,
            fechaDesdeMenu: false,
            fechaHastaMenu: false,
            fechaAltaMenu: false,
            fechaControlMenu: false,
        };
    },
    computed: {
        formData: {
            get() {
                return this.$store.getters['formulario04/formData'];
            },
            set(value) {
                this.$store.dispatch('formulario04/updateFormData', value);
            },
        },
        loading() {
            return this.$store.getters['formulario04/loading'];
        },
        isValid() {
            return this.$store.getters['formulario04/isValid'];
        },
    },
    methods: {
        resetForm() {
            this.$store.dispatch('formulario04/resetForm');
            this.errors = {};
        },
        validate() {
            this.errors = {};

            if (!this.formData.apellidoNombre) {
                this.errors.apellidoNombre =
                    'El nombre del agente es requerido';
            }

            if (!this.formData.dni) {
                this.errors.dni = 'El DNI es requerido';
            }

            if (!this.formData.diasJustificar) {
                this.errors.diasJustificar =
                    'Los días a justificar son requeridos';
            }

            if (!this.formData.fechaDesde) {
                this.errors.fechaDesde = 'La fecha de inicio es requerida';
            }

            if (!this.formData.fechaHasta) {
                this.errors.fechaHasta =
                    'La fecha de finalización es requerida';
            }

            return Object.keys(this.errors).length === 0;
        },
        async submitForm() {
            if (!this.validate()) {
                return;
            }

            try {
                await this.$store.dispatch('formulario04/submitForm');
                this.$toast.success('Formulario enviado correctamente');
                this.resetForm();
            } catch (error) {
                this.$toast.error(
                    error.message || 'Error al enviar el formulario',
                );
            }
        },
    },
};
</script>

<style scoped>
.formulario-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 2rem;
}

.glass-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
}

.header-section {
    background: rgba(255, 255, 255, 0.9);
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.v-card {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.v-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.v-text-field {
    margin-bottom: 0.5rem;
}

.v-alert {
    border-radius: 8px;
}

@media (max-width: 600px) {
    .formulario-container {
        padding: 1rem;
    }

    .header-section {
        padding: 1rem;
    }
}
</style>
