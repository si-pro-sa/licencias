<template>
    <v-data-table
        :headers="headers"
        :items="items"
        class="elevation-1"
        sort-by="idlicencia"
        item-key="idlicencia"
        hide-default-footer
        loading-text="Cargando Datos... Por favor tenga paciencia"
    >
        <template v-slot:top>
            <v-toolbar color="white" flat>
                <v-toolbar-title> {{ title }}</v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </template>
    </v-data-table>
</template>

<script>
export default {
    name: 'TablaSaldos',
    props: {
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
        title: {
            type: String,
            default: 'Saldo'
        }
    }
}
</script>

<style scoped></style>
