<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title class="headline primary white--text">
                            <v-icon
                                large
                                dark
                                left
                                >mdi-clipboard-check</v-icon
                            >
                            Evaluación de Junta Médica
                        </v-card-title>

                        <v-card-text class="py-4">
                            <v-row>
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-card
                                        outlined
                                        class="mb-4"
                                    >
                                        <v-card-title class="subtitle-1">
                                            Información de la Junta Médica
                                        </v-card-title>
                                        <v-card-text>
                                            <v-row>
                                                <v-col cols="12">
                                                    <p>
                                                        <strong>Nombre:</strong>
                                                        {{ juntaMedica.nombre }}
                                                    </p>
                                                    <p>
                                                        <strong>Tipo:</strong>
                                                        {{ juntaMedica.tipo }}
                                                    </p>
                                                    <p>
                                                        <strong
                                                            >Fecha y
                                                            Hora:</strong
                                                        >
                                                        {{ juntaMedica.fecha }}
                                                        - {{ juntaMedica.hora }}
                                                    </p>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                                <v-col
                                    cols="12"
                                    md="6"
                                >
                                    <v-card
                                        outlined
                                        class="mb-4"
                                    >
                                        <v-card-title class="subtitle-1">
                                            Información del Agente
                                        </v-card-title>
                                        <v-card-text>
                                            <v-row>
                                                <v-col cols="12">
                                                    <p>
                                                        <strong>Nombre:</strong>
                                                        Pérez, Juan
                                                    </p>
                                                    <p>
                                                        <strong
                                                            >Tipo de
                                                            Licencia:</strong
                                                        >
                                                        Enfermedad Prolongada
                                                    </p>
                                                    <p>
                                                        <strong
                                                            >Período:</strong
                                                        >
                                                        01/02/2024 - 15/03/2024
                                                    </p>
                                                </v-col>
                                            </v-row>
                                        </v-card-text>
                                    </v-card>
                                </v-col>
                            </v-row>

                            <v-tabs
                                v-model="activeTab"
                                background-color="primary"
                                dark
                                class="mt-4"
                            >
                                <v-tab>Historia Clínica</v-tab>
                                <v-tab>Diagnósticos</v-tab>
                                <v-tab>Observaciones</v-tab>
                                <v-tab>Evaluación</v-tab>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <h3 class="subtitle-1 mb-3">
                                                Exámenes Médicos
                                            </h3>
                                            <v-data-table
                                                :headers="headersExamenes"
                                                :items="examenesMedicos"
                                                :items-per-page="5"
                                                class="elevation-1 mb-6"
                                            >
                                                <template
                                                    v-slot:item.estado="{
                                                        item,
                                                    }"
                                                >
                                                    <v-chip
                                                        :color="
                                                            getChipColor(
                                                                item.estado,
                                                            )
                                                        "
                                                        text-color="white"
                                                        small
                                                    >
                                                        {{ item.estado }}
                                                    </v-chip>
                                                </template>
                                            </v-data-table>

                                            <h3 class="subtitle-1 mb-3">
                                                Consultas Médicas
                                            </h3>
                                            <v-data-table
                                                :headers="headersConsultas"
                                                :items="consultasMedicas"
                                                :items-per-page="5"
                                                class="elevation-1"
                                            >
                                            </v-data-table>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <h3 class="subtitle-1 mb-3">
                                                Diagnósticos Previos
                                            </h3>
                                            <v-timeline dense>
                                                <v-timeline-item
                                                    v-for="(
                                                        diagnostico, i
                                                    ) in diagnosticos"
                                                    :key="i"
                                                    color="primary"
                                                    small
                                                >
                                                    <template v-slot:opposite>
                                                        <span
                                                            class="text-caption"
                                                        >
                                                            {{
                                                                diagnostico.fecha
                                                            }}
                                                        </span>
                                                    </template>
                                                    <v-card outlined>
                                                        <v-card-title
                                                            class="text-subtitle-2"
                                                        >
                                                            {{
                                                                diagnostico.tipo
                                                            }}
                                                        </v-card-title>
                                                        <v-card-text>
                                                            <p>
                                                                <strong
                                                                    >Profesional:</strong
                                                                >
                                                                {{
                                                                    diagnostico.profesional
                                                                }}
                                                            </p>
                                                            <p>
                                                                <strong
                                                                    >Diagnóstico:</strong
                                                                >
                                                                {{
                                                                    diagnostico.descripcion
                                                                }}
                                                            </p>
                                                            <div
                                                                v-if="
                                                                    diagnostico.observaciones
                                                                "
                                                            >
                                                                <p>
                                                                    <strong
                                                                        >Observaciones:</strong
                                                                    >
                                                                    {{
                                                                        diagnostico.observaciones
                                                                    }}
                                                                </p>
                                                            </div>
                                                            <div
                                                                v-if="
                                                                    diagnostico.archivos &&
                                                                    diagnostico
                                                                        .archivos
                                                                        .length
                                                                "
                                                            >
                                                                <p>
                                                                    <strong
                                                                        >Archivos
                                                                        adjuntos:</strong
                                                                    >
                                                                </p>
                                                                <v-chip
                                                                    v-for="(
                                                                        archivo,
                                                                        j
                                                                    ) in diagnostico.archivos"
                                                                    :key="j"
                                                                    class="ma-1"
                                                                    small
                                                                    @click="
                                                                        verArchivo(
                                                                            archivo,
                                                                        )
                                                                    "
                                                                >
                                                                    <v-icon
                                                                        left
                                                                        small
                                                                        >mdi-file-document</v-icon
                                                                    >
                                                                    {{
                                                                        archivo.nombre
                                                                    }}
                                                                </v-chip>
                                                            </div>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-timeline-item>
                                            </v-timeline>

                                            <!-- Formulario para añadir nuevo diagnóstico -->
                                            <v-divider class="my-5"></v-divider>

                                            <h3 class="subtitle-1 mb-3">
                                                Nuevo Diagnóstico
                                            </h3>
                                            <v-form
                                                ref="diagnosticoForm"
                                                v-model="diagnosticoFormValid"
                                            >
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        md="6"
                                                    >
                                                        <v-select
                                                            v-model="
                                                                nuevoDiagnostico.tipo
                                                            "
                                                            :items="
                                                                tiposDiagnostico
                                                            "
                                                            label="Tipo de Diagnóstico"
                                                            outlined
                                                            dense
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El tipo es obligatorio',
                                                            ]"
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        md="6"
                                                    >
                                                        <v-select
                                                            v-model="
                                                                nuevoDiagnostico.profesional
                                                            "
                                                            :items="
                                                                profesionalesMedicos
                                                            "
                                                            label="Profesional que evalúa"
                                                            outlined
                                                            dense
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El profesional es obligatorio',
                                                            ]"
                                                        ></v-select>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                nuevoDiagnostico.descripcion
                                                            "
                                                            label="Descripción del Diagnóstico"
                                                            outlined
                                                            dense
                                                            rows="3"
                                                            counter="500"
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El diagnóstico es obligatorio',
                                                            ]"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                nuevoDiagnostico.observaciones
                                                            "
                                                            label="Observaciones"
                                                            outlined
                                                            dense
                                                            rows="3"
                                                            counter="300"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-file-input
                                                            v-model="
                                                                nuevoDiagnostico.archivos
                                                            "
                                                            label="Documentos de respaldo"
                                                            outlined
                                                            dense
                                                            multiple
                                                            counter
                                                            show-size
                                                            prepend-icon="mdi-paperclip"
                                                        >
                                                            <template
                                                                v-slot:selection="{
                                                                    index,
                                                                    text,
                                                                }"
                                                            >
                                                                <v-chip
                                                                    v-if="
                                                                        index <
                                                                        2
                                                                    "
                                                                    color="primary"
                                                                    dark
                                                                    label
                                                                    small
                                                                >
                                                                    {{ text }}
                                                                </v-chip>
                                                                <span
                                                                    v-else-if="
                                                                        index ===
                                                                        2
                                                                    "
                                                                    class="text-overline grey--text text--darken-3 mx-2"
                                                                >
                                                                    +{{
                                                                        nuevoDiagnostico
                                                                            .archivos
                                                                            .length -
                                                                        2
                                                                    }}
                                                                    archivos
                                                                </span>
                                                            </template>
                                                        </v-file-input>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        class="text-right"
                                                    >
                                                        <v-btn
                                                            color="primary"
                                                            @click="
                                                                agregarDiagnostico
                                                            "
                                                            :disabled="
                                                                !diagnosticoFormValid
                                                            "
                                                            :loading="
                                                                guardandoDiagnostico
                                                            "
                                                        >
                                                            <v-icon left
                                                                >mdi-plus</v-icon
                                                            >
                                                            Agregar Diagnóstico
                                                        </v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <h3 class="subtitle-1 mb-3">
                                                Observaciones Registradas
                                            </h3>

                                            <v-timeline
                                                dense
                                                v-if="observaciones.length > 0"
                                            >
                                                <v-timeline-item
                                                    v-for="(
                                                        observacion, i
                                                    ) in observaciones"
                                                    :key="i"
                                                    color="info"
                                                    small
                                                >
                                                    <template v-slot:opposite>
                                                        <span
                                                            class="text-caption"
                                                        >
                                                            {{
                                                                observacion.fecha
                                                            }}
                                                        </span>
                                                    </template>
                                                    <v-card outlined>
                                                        <v-card-title
                                                            class="text-subtitle-2"
                                                        >
                                                            {{
                                                                observacion.titulo
                                                            }}
                                                        </v-card-title>
                                                        <v-card-subtitle>
                                                            {{
                                                                observacion.autor
                                                            }}
                                                        </v-card-subtitle>
                                                        <v-card-text>
                                                            <p>
                                                                {{
                                                                    observacion.descripcion
                                                                }}
                                                            </p>

                                                            <div
                                                                v-if="
                                                                    observacion.archivos &&
                                                                    observacion
                                                                        .archivos
                                                                        .length
                                                                "
                                                            >
                                                                <p>
                                                                    <strong
                                                                        >Archivos
                                                                        adjuntos:</strong
                                                                    >
                                                                </p>
                                                                <v-chip
                                                                    v-for="(
                                                                        archivo,
                                                                        j
                                                                    ) in observacion.archivos"
                                                                    :key="j"
                                                                    class="ma-1"
                                                                    small
                                                                    @click="
                                                                        verArchivo(
                                                                            archivo,
                                                                        )
                                                                    "
                                                                >
                                                                    <v-icon
                                                                        left
                                                                        small
                                                                        >mdi-file-document</v-icon
                                                                    >
                                                                    {{
                                                                        archivo.nombre
                                                                    }}
                                                                </v-chip>
                                                            </div>
                                                        </v-card-text>
                                                    </v-card>
                                                </v-timeline-item>
                                            </v-timeline>

                                            <v-alert
                                                v-else
                                                type="info"
                                                outlined
                                            >
                                                No hay observaciones registradas
                                                para esta junta médica.
                                            </v-alert>

                                            <!-- Formulario para añadir nueva observación -->
                                            <v-divider class="my-5"></v-divider>

                                            <h3 class="subtitle-1 mb-3">
                                                Nueva Observación
                                            </h3>
                                            <v-form
                                                ref="observacionForm"
                                                v-model="observacionFormValid"
                                            >
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        md="6"
                                                    >
                                                        <v-text-field
                                                            v-model="
                                                                nuevaObservacion.titulo
                                                            "
                                                            label="Título"
                                                            outlined
                                                            dense
                                                            counter="100"
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El título es obligatorio',
                                                            ]"
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        md="6"
                                                    >
                                                        <v-select
                                                            v-model="
                                                                nuevaObservacion.autor
                                                            "
                                                            :items="
                                                                profesionalesMedicos
                                                            "
                                                            label="Autor"
                                                            outlined
                                                            dense
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El autor es obligatorio',
                                                            ]"
                                                        ></v-select>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                nuevaObservacion.descripcion
                                                            "
                                                            label="Descripción"
                                                            outlined
                                                            dense
                                                            rows="4"
                                                            counter="800"
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'La descripción es obligatoria',
                                                            ]"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-file-input
                                                            v-model="
                                                                nuevaObservacion.archivos
                                                            "
                                                            label="Documentos de respaldo"
                                                            outlined
                                                            dense
                                                            multiple
                                                            counter
                                                            show-size
                                                            prepend-icon="mdi-paperclip"
                                                        >
                                                            <template
                                                                v-slot:selection="{
                                                                    index,
                                                                    text,
                                                                }"
                                                            >
                                                                <v-chip
                                                                    v-if="
                                                                        index <
                                                                        2
                                                                    "
                                                                    color="primary"
                                                                    dark
                                                                    label
                                                                    small
                                                                >
                                                                    {{ text }}
                                                                </v-chip>
                                                                <span
                                                                    v-else-if="
                                                                        index ===
                                                                        2
                                                                    "
                                                                    class="text-overline grey--text text--darken-3 mx-2"
                                                                >
                                                                    +{{
                                                                        nuevaObservacion
                                                                            .archivos
                                                                            .length -
                                                                        2
                                                                    }}
                                                                    archivos
                                                                </span>
                                                            </template>
                                                        </v-file-input>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        class="text-right"
                                                    >
                                                        <v-btn
                                                            color="info"
                                                            @click="
                                                                agregarObservacion
                                                            "
                                                            :disabled="
                                                                !observacionFormValid
                                                            "
                                                            :loading="
                                                                guardandoObservacion
                                                            "
                                                        >
                                                            <v-icon left
                                                                >mdi-plus</v-icon
                                                            >
                                                            Agregar Observación
                                                        </v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>

                                <v-tab-item>
                                    <v-card flat>
                                        <v-card-text>
                                            <h3 class="subtitle-1 mb-3">
                                                Evaluación de la Junta Médica
                                            </h3>
                                            <v-form
                                                ref="evaluacionForm"
                                                v-model="evaluacionValid"
                                            >
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        md="6"
                                                    >
                                                        <v-select
                                                            v-model="
                                                                evaluacion.decision
                                                            "
                                                            :items="
                                                                tiposDecision
                                                            "
                                                            label="Decisión"
                                                            outlined
                                                            dense
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'La decisión es obligatoria',
                                                            ]"
                                                        ></v-select>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        md="6"
                                                    >
                                                        <v-select
                                                            v-model="
                                                                evaluacion.estadoAgente
                                                            "
                                                            :items="
                                                                estadosAgente
                                                            "
                                                            label="Estado del Agente"
                                                            outlined
                                                            dense
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El estado es obligatorio',
                                                            ]"
                                                        >
                                                            <template
                                                                v-slot:selection="{
                                                                    item,
                                                                }"
                                                            >
                                                                <v-chip
                                                                    small
                                                                    :color="
                                                                        getEstadoColor(
                                                                            item,
                                                                        )
                                                                    "
                                                                    dark
                                                                >
                                                                    {{ item }}
                                                                </v-chip>
                                                            </template>
                                                            <template
                                                                v-slot:item="{
                                                                    item,
                                                                }"
                                                            >
                                                                <v-chip
                                                                    small
                                                                    :color="
                                                                        getEstadoColor(
                                                                            item,
                                                                        )
                                                                    "
                                                                    dark
                                                                    class="mr-2"
                                                                >
                                                                    {{ item }}
                                                                </v-chip>
                                                            </template>
                                                        </v-select>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                evaluacion.diagnosticoFinal
                                                            "
                                                            label="Diagnóstico Final"
                                                            outlined
                                                            dense
                                                            rows="3"
                                                            counter="500"
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'El diagnóstico final es obligatorio',
                                                            ]"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                evaluacion.observaciones
                                                            "
                                                            label="Observaciones"
                                                            outlined
                                                            dense
                                                            rows="3"
                                                            counter="500"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                evaluacion.recomendaciones
                                                            "
                                                            label="Recomendaciones y Tratamiento"
                                                            outlined
                                                            dense
                                                            rows="3"
                                                            counter="500"
                                                            :rules="[
                                                                (v) =>
                                                                    !!v ||
                                                                    'Las recomendaciones son obligatorias',
                                                            ]"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-textarea
                                                            v-model="
                                                                evaluacion.seguimiento
                                                            "
                                                            label="Plan de Seguimiento"
                                                            outlined
                                                            dense
                                                            rows="3"
                                                            counter="300"
                                                        ></v-textarea>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-switch
                                                            v-model="
                                                                evaluacion.notificar
                                                            "
                                                            label="Notificar al área de Personal"
                                                            color="primary"
                                                        ></v-switch>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col cols="12">
                                                        <v-expansion-panels>
                                                            <v-expansion-panel>
                                                                <v-expansion-panel-header>
                                                                    <div
                                                                        class="d-flex align-center"
                                                                    >
                                                                        <v-icon
                                                                            left
                                                                            color="primary"
                                                                            >mdi-clipboard-file</v-icon
                                                                        >
                                                                        Adjuntar
                                                                        documentos
                                                                        a la
                                                                        evaluación
                                                                    </div>
                                                                </v-expansion-panel-header>
                                                                <v-expansion-panel-content>
                                                                    <v-file-input
                                                                        v-model="
                                                                            evaluacion.archivos
                                                                        "
                                                                        label="Documentos de respaldo"
                                                                        outlined
                                                                        dense
                                                                        multiple
                                                                        counter
                                                                        show-size
                                                                        prepend-icon="mdi-paperclip"
                                                                    >
                                                                        <template
                                                                            v-slot:selection="{
                                                                                index,
                                                                                text,
                                                                            }"
                                                                        >
                                                                            <v-chip
                                                                                v-if="
                                                                                    index <
                                                                                    2
                                                                                "
                                                                                color="primary"
                                                                                dark
                                                                                label
                                                                                small
                                                                            >
                                                                                {{
                                                                                    text
                                                                                }}
                                                                            </v-chip>
                                                                            <span
                                                                                v-else-if="
                                                                                    index ===
                                                                                    2
                                                                                "
                                                                                class="text-overline grey--text text--darken-3 mx-2"
                                                                            >
                                                                                +{{
                                                                                    evaluacion
                                                                                        .archivos
                                                                                        .length -
                                                                                    2
                                                                                }}
                                                                                archivos
                                                                            </span>
                                                                        </template>
                                                                    </v-file-input>
                                                                </v-expansion-panel-content>
                                                            </v-expansion-panel>
                                                        </v-expansion-panels>
                                                    </v-col>
                                                </v-row>

                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        class="text-right"
                                                    >
                                                        <v-btn
                                                            text
                                                            color="grey darken-1"
                                                            @click="
                                                                cancelarEvaluacion
                                                            "
                                                        >
                                                            Cancelar
                                                        </v-btn>
                                                        <v-btn
                                                            color="primary"
                                                            @click="
                                                                guardarEvaluacion
                                                            "
                                                            :disabled="
                                                                !evaluacionValid
                                                            "
                                                            :loading="
                                                                guardandoEvaluacion
                                                            "
                                                            class="ml-2"
                                                        >
                                                            <v-icon left
                                                                >mdi-content-save</v-icon
                                                            >
                                                            Guardar Evaluación
                                                        </v-btn>
                                                        <v-btn
                                                            color="success"
                                                            @click="
                                                                finalizarJuntaMedica
                                                            "
                                                            :disabled="
                                                                !evaluacionValid
                                                            "
                                                            :loading="
                                                                guardandoEvaluacion
                                                            "
                                                            class="ml-2"
                                                        >
                                                            <v-icon left
                                                                >mdi-check-circle</v-icon
                                                            >
                                                            Finalizar Junta
                                                            Médica
                                                        </v-btn>
                                                    </v-col>
                                                </v-row>
                                            </v-form>
                                        </v-card-text>
                                    </v-card>
                                </v-tab-item>
                            </v-tabs>
                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="error"
                                text
                                @click="cancelar"
                            >
                                Cancelar
                            </v-btn>
                            <v-btn
                                color="success"
                                :disabled="!validForm || loading"
                                :loading="loading"
                                @click="guardarEvaluacion"
                            >
                                <v-icon left>mdi-content-save</v-icon>
                                Guardar Evaluación
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    name: 'JuntaMedicaEvaluacion',
    props: {
        idJuntaMedica: {
            type: [Number, String],
            required: true,
        },
    },
    data() {
        return {
            loading: false,
            activeTab: 0,
            validForm: false,
            archivos: [],

            // Opciones de selección
            resultadosEvaluacion: [
                'Alta Médica',
                'Prórroga de Licencia',
                'Cambio de Funciones',
                'Incapacidad Parcial',
                'Incapacidad Total',
            ],
            opcionesVotos: ['A favor', 'En contra', 'Abstención'],

            // Datos de la evaluación
            evaluacion: {
                decision: '',
                estadoAgente: '',
                diagnosticoFinal: '',
                observaciones: '',
                recomendaciones: '',
                seguimiento: '',
                notificar: true,
                archivos: [],
            },

            // Votos de los miembros (se generan dinámicamente)
            votos: [],

            // Datos mockeados para desarrollo
            juntaMedica: {
                id: 1,
                nombre: 'Junta Médica de Evaluación Licencia Prolongada',
                tipo: 'Licencia Prolongada',
                fecha: '2024-03-29',
                hora: '10:00',
                miembros: [
                    { idagente: 1, rol: 'Presidente' },
                    { idagente: 2, rol: 'Vocal' },
                    { idagente: 3, rol: 'Secretario' },
                ],
                estado: 'En Evaluación',
            },

            // Datos de historia clínica
            examenesMedicos: [
                {
                    id: 1,
                    fecha: '15/01/2024',
                    tipo: 'Examen Preocupacional',
                    profesional: 'Dr. Martínez, Carlos',
                    estado: 'Completado',
                },
                {
                    id: 2,
                    fecha: '05/02/2024',
                    tipo: 'Control Periódico',
                    profesional: 'Dra. González, María',
                    estado: 'Completado',
                },
            ],
            consultasMedicas: [
                {
                    id: 1,
                    fecha: '10/02/2024',
                    motivo: 'Dolor lumbar',
                    profesional: 'Dr. López, Miguel',
                    especialidad: 'Traumatología',
                },
                {
                    id: 2,
                    fecha: '25/02/2024',
                    motivo: 'Control de evolución',
                    profesional: 'Dra. González, María',
                    especialidad: 'Traumatología',
                },
            ],
            diagnosticos: [
                {
                    fecha: '10/02/2024',
                    tipo: 'Diagnóstico Inicial',
                    profesional: 'Dr. López, Miguel',
                    descripcion:
                        'Lumbalgia aguda con componente ciático. Posible hernia discal a confirmar.',
                    observaciones: 'Paciente refiere dolor intenso al caminar.',
                    archivos: [
                        { nombre: 'resonancia.pdf', url: '#' },
                        { nombre: 'informe_traumatologo.pdf', url: '#' },
                    ],
                },
                {
                    fecha: '25/02/2024',
                    tipo: 'Seguimiento',
                    profesional: 'Dra. González, María',
                    descripcion:
                        'Hernia discal L4-L5 confirmada por RMN. Continúa con dolor y limitación funcional.',
                    observaciones: 'Se recomienda evaluación por neurocirugía.',
                    archivos: [{ nombre: 'informe_rmn.pdf', url: '#' }],
                },
            ],
            agentesDisponibles: [
                { idagente: 1, nombre: 'Dr. Martínez, Carlos' },
                { idagente: 2, nombre: 'Dra. González, María' },
                { idagente: 3, nombre: 'Dr. Sánchez, Javier' },
                { idagente: 4, nombre: 'Dra. Fernández, Laura' },
                { idagente: 5, nombre: 'Dr. Rodríguez, Eduardo' },
                { idagente: 6, nombre: 'Dra. López, Ana' },
                { idagente: 7, nombre: 'Dr. García, Pablo' },
            ],
            headersExamenes: [
                { text: 'Fecha', value: 'fecha', sortable: true },
                { text: 'Tipo', value: 'tipo', sortable: true },
                { text: 'Profesional', value: 'profesional', sortable: true },
                { text: 'Estado', value: 'estado', sortable: true },
            ],
            headersConsultas: [
                { text: 'Fecha', value: 'fecha', sortable: true },
                { text: 'Motivo', value: 'motivo', sortable: true },
                { text: 'Profesional', value: 'profesional', sortable: true },
                { text: 'Especialidad', value: 'especialidad', sortable: true },
            ],
            nuevoDiagnostico: {
                tipo: '',
                profesional: '',
                descripcion: '',
                observaciones: '',
                archivos: [],
            },
            diagnosticoFormValid: false,
            guardandoDiagnostico: false,
            tiposDiagnostico: [
                'Diagnóstico Inicial',
                'Seguimiento',
                'Evaluación',
            ],
            profesionalesMedicos: [
                'Dr. López, Miguel',
                'Dra. González, María',
                'Dr. Sánchez, Javier',
            ],
            evaluacionValid: false,
            guardandoEvaluacion: false,
            tiposDecision: [
                'Apto',
                'No Apto',
                'Apto con Restricciones',
                'En Evaluación',
            ],
            estadosAgente: [
                'Activo',
                'Licencia',
                'Suspendido',
                'Reincorporación Parcial',
            ],
            observaciones: [
                {
                    fecha: '15/02/2024',
                    titulo: 'Evolución de dolor lumbar',
                    autor: 'Dr. López, Miguel',
                    descripcion:
                        'El paciente refiere mejoría parcial del dolor tras iniciar tratamiento con AINES. Continúa con dificultad para realizar actividades que impliquen flexión lumbar prolongada. Se observa limitación en la movilidad con test de Lasegue positivo a 45° en miembro inferior derecho.',
                    archivos: [{ nombre: 'evolucion_15_feb.pdf', url: '#' }],
                },
                {
                    fecha: '01/03/2024',
                    titulo: 'Resultados de estudios complementarios',
                    autor: 'Dra. González, María',
                    descripcion:
                        'Se reciben resultados de electromiografía que confirman compromiso radicular L5-S1 derecho, compatible con el diagnóstico de hernia discal. Se observa disminución de la velocidad de conducción y signos de denervación aguda en territorio L5.',
                    archivos: [
                        { nombre: 'electromiografia.pdf', url: '#' },
                        { nombre: 'informe_neurologico.pdf', url: '#' },
                    ],
                },
            ],
            nuevaObservacion: {
                titulo: '',
                autor: '',
                descripcion: '',
                archivos: [],
            },
            observacionFormValid: false,
            guardandoObservacion: false,
        };
    },
    created() {
        // Inicializar votos para cada miembro
        this.inicializarVotos();

        // En un entorno real, aquí cargaríamos la información de la junta médica
        // this.cargarJuntaMedica();
    },
    methods: {
        inicializarVotos() {
            this.votos = this.juntaMedica.miembros.map((miembro) => ({
                idagente: miembro.idagente,
                rol: miembro.rol,
                voto: null,
            }));
        },
        async cargarJuntaMedica() {
            try {
                this.loading = true;
                // Cargar datos de la junta médica desde el backend
                // const response = await this.$store.dispatch('juntaMedica/getJuntaMedica', this.idJuntaMedica);
                // this.juntaMedica = response.data;

                // Inicializar votos
                this.inicializarVotos();

                // Cargar historia clínica del agente
                // await this.cargarHistoriaClinica();
            } catch (error) {
                console.error('Error al cargar junta médica:', error);
                // Mostrar mensaje de error
            } finally {
                this.loading = false;
            }
        },
        async cargarHistoriaClinica() {
            try {
                // Cargar historia clínica del agente
                // const response = await this.$store.dispatch('historiaClinica/loadHistoriaClinica', this.juntaMedica.idagente);
                // this.examenesMedicos = response.examenes;
                // this.consultasMedicas = response.consultas;
            } catch (error) {
                console.error('Error al cargar historia clínica:', error);
            }
        },
        async guardarEvaluacion() {
            if (!this.$refs.evaluacionForm.validate()) return;

            this.guardandoEvaluacion = true;

            try {
                // Preparar datos para enviar
                const datosEvaluacion = {
                    id_junta_medica: this.juntaMedica.id,
                    decision: this.evaluacion.decision,
                    estado_agente: this.evaluacion.estadoAgente,
                    diagnostico_final: this.evaluacion.diagnosticoFinal,
                    observaciones: this.evaluacion.observaciones,
                    recomendaciones: this.evaluacion.recomendaciones,
                    seguimiento: this.evaluacion.seguimiento,
                    notificar: this.evaluacion.notificar,
                    archivos: this.evaluacion.archivos,
                };

                // En un entorno real, aquí enviaríamos los datos al backend
                // await this.$store.dispatch('juntaMedica/guardarEvaluacion', datosEvaluacion);

                // Simulamos una demora para mostrar el loading
                await new Promise((resolve) => setTimeout(resolve, 1000));

                // Mostrar mensaje de éxito
                this.$emit('message', {
                    text: 'Evaluación guardada correctamente',
                    color: 'success',
                });
            } catch (error) {
                console.error('Error al guardar evaluación:', error);
                // Mostrar mensaje de error
                this.$emit('message', {
                    text: 'Error al guardar la evaluación',
                    color: 'error',
                });
            } finally {
                this.guardandoEvaluacion = false;
            }
        },
        cancelar() {
            // Redirigir a la lista de juntas médicas
            this.$router.push('/salud-ocupacional/juntas-medicas');
        },
        getChipColor(estado) {
            switch (estado) {
                case 'Completado':
                    return 'success';
                case 'Pendiente':
                    return 'warning';
                case 'Cancelado':
                    return 'error';
                default:
                    return 'primary';
            }
        },
        verArchivo(archivo) {
            // En un entorno real, aquí abriríamos el archivo
            console.log('Abriendo archivo:', archivo.nombre);
            // window.open(archivo.url, '_blank');
        },
        getNombreAgente(idagente) {
            const agente = this.agentesDisponibles.find(
                (a) => a.idagente === idagente,
            );
            return agente ? agente.nombre : 'Desconocido';
        },
        getEstadoColor(estado) {
            switch (estado) {
                case 'Activo':
                    return 'success';
                case 'Licencia':
                    return 'warning';
                case 'Suspendido':
                    return 'error';
                case 'Reincorporación Parcial':
                    return 'info';
                default:
                    return 'primary';
            }
        },
        async agregarDiagnostico() {
            if (!this.$refs.diagnosticoForm.validate()) return;

            this.guardandoDiagnostico = true;

            try {
                // Preparar datos para enviar
                const nuevoDiagnostico = {
                    fecha: new Date().toLocaleDateString('es-ES'),
                    tipo: this.nuevoDiagnostico.tipo,
                    profesional: this.nuevoDiagnostico.profesional,
                    descripcion: this.nuevoDiagnostico.descripcion,
                    observaciones: this.nuevoDiagnostico.observaciones,
                    archivos: this.nuevoDiagnostico.archivos.map((archivo) => ({
                        nombre: archivo.name,
                        url: URL.createObjectURL(archivo),
                    })),
                };

                // En un entorno real, aquí enviaríamos los datos al backend
                // await this.$store.dispatch('juntaMedica/agregarDiagnostico', {
                //   id_junta_medica: this.juntaMedica.id,
                //   diagnostico: nuevoDiagnostico
                // });

                // Simulamos una demora para mostrar el loading
                await new Promise((resolve) => setTimeout(resolve, 1000));

                // Añadirlo al array local (en un entorno real, esto vendría de la API)
                this.diagnosticos.unshift(nuevoDiagnostico);

                // Limpiar el formulario
                this.$refs.diagnosticoForm.reset();
                this.nuevoDiagnostico = {
                    tipo: '',
                    profesional: '',
                    descripcion: '',
                    observaciones: '',
                    archivos: [],
                };

                // Mostrar mensaje de éxito
                this.$emit('message', {
                    text: 'Diagnóstico agregado correctamente',
                    color: 'success',
                });
            } catch (error) {
                console.error('Error al agregar diagnóstico:', error);
                // Mostrar mensaje de error
                this.$emit('message', {
                    text: 'Error al agregar el diagnóstico',
                    color: 'error',
                });
            } finally {
                this.guardandoDiagnostico = false;
            }
        },
        cancelarEvaluacion() {
            // Mostrar un diálogo de confirmación
            if (
                confirm(
                    '¿Está seguro de cancelar la evaluación? Los cambios no guardados se perderán.',
                )
            ) {
                // Redirigir a la lista de juntas médicas
                this.$router.push('/salud-ocupacional/juntas-medicas');
            }
        },
        finalizarJuntaMedica() {
            // Redirigir a la lista de juntas médicas
            this.$router.push('/salud-ocupacional/juntas-medicas');
        },
        async agregarObservacion() {
            if (!this.$refs.observacionForm.validate()) return;

            this.guardandoObservacion = true;

            try {
                // Preparar datos para enviar
                const nuevaObservacion = {
                    fecha: new Date().toLocaleDateString('es-ES'),
                    titulo: this.nuevaObservacion.titulo,
                    autor: this.nuevaObservacion.autor,
                    descripcion: this.nuevaObservacion.descripcion,
                    archivos: this.nuevaObservacion.archivos.map((archivo) => ({
                        nombre: archivo.name,
                        url: URL.createObjectURL(archivo),
                    })),
                };

                // En un entorno real, aquí enviaríamos los datos al backend
                // await this.$store.dispatch('juntaMedica/agregarObservacion', {
                //   id_junta_medica: this.juntaMedica.id,
                //   observacion: nuevaObservacion
                // });

                // Simulamos una demora para mostrar el loading
                await new Promise((resolve) => setTimeout(resolve, 1000));

                // Añadirlo al array local (en un entorno real, esto vendría de la API)
                this.observaciones.unshift(nuevaObservacion);

                // Limpiar el formulario
                this.$refs.observacionForm.reset();
                this.nuevaObservacion = {
                    titulo: '',
                    autor: '',
                    descripcion: '',
                    archivos: [],
                };

                // Mostrar mensaje de éxito
                this.$emit('message', {
                    text: 'Observación agregada correctamente',
                    color: 'success',
                });
            } catch (error) {
                console.error('Error al agregar observación:', error);
                // Mostrar mensaje de error
                this.$emit('message', {
                    text: 'Error al agregar la observación',
                    color: 'error',
                });
            } finally {
                this.guardandoObservacion = false;
            }
        },
    },
};
</script>

<style scoped>
.v-timeline-item__body {
    max-width: 100%;
}
</style>
