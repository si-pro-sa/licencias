<template>
    <v-data-table
        :id="id"
        v-model="selected"
        :headers="headers"
        :items="items"
        show-select
        class="elevation-1"
        sort-by="idlicencia"
        item-key="idlicencia"
        :loading="loading"
        :item-class="row_classes"
        loading-text="Cargando Datos... Por favor tenga paciencia"
    >
        <template v-slot:top>
            <v-toolbar color="white" flat>
                <v-toolbar-title> {{ tituloTipoLicencia }}</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </template>
    </v-data-table>
</template>

<script>
export default {
    name: 'TablaLicencias',
    props: {
        id: {
            type: Number,
            default: 0
        },
        headers: {
            type: Array,
            default() {
                return []
            }
        },
        items: {
            type: Array,
            default() {
                return []
            }
        },
        tituloTipoLicencia: {
            type: String,
            default: 'Salud Ocupacional'
        }
    },
    data: vm => ({
        loading: false,
        selected: []
    }),
    watch: {
        selected: {
            handler() {
                this.updateLicencia()
            },
            deep: true
        }
    },
    methods: {
        row_classes(item) {
            if (item.primer_visado === true && item.segundo_visado === null) {
                return 'advertencia'
            } else if (item.segundo_visado === true) {
                return 'visado'
            } else if (
                item.primer_visado === false ||
                item.segundo_visado === false
            ) {
                return 'desvisado'
            }
        },
        updateLicencia() {
            this.$emit('update:Licencias', this.selected)
        }
    }
}
</script>

<style scoped>
.advertencia {
    background-color: #f7f5dd;
}

.desvisado {
    background-color: #e2979c;
}

.visado {
    background-color: #9bdeac;
}
</style>
