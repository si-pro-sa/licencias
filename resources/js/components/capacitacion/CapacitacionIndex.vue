<template>
    <v-container>
        <v-card>
            <v-tabs v-model="tab" background-color="primary" dark>
                <v-tab
                    v-for="item in items.filter(el => {
                        return permisos.includes(el.perm)
                    })"
                    :key="item.tab"
                >
                    {{ item.tab }}
                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab">
                <v-tab-item
                    v-for="item in items.filter(el => {
                        return permisos.includes(el.perm)
                    })"
                    :key="item.tab"
                >
                    <v-card flat>
                        <component :is="item.content" />
                    </v-card>
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-container>
</template>

<script>
import Alcance from './AlcanceTab.vue'
import TipoEvento from './TipoEventoTab.vue'
import Capacitaciones from './CapacitacionTab.vue'
import Informes from './InformesTab.vue'
import Caracter from './CaracterTab.vue'
export default {
    name: 'CapacitacionIndex',
    components: {
        Alcance,
        TipoEvento,
        Capacitaciones,
        Caracter,
        Informes
    },
    data() {
        return {
            tab: null,
            items: [
                {
                    tab: 'Alcance',
                    content: 'Alcance',
                    perm: 'ver-capacitacionAlcance'
                },
                {
                    tab: 'Tipo de Evento',
                    content: 'TipoEvento',
                    perm: 'ver-capacitacionTipoEvento'
                },
                {
                    tab: 'Caracter',
                    content: 'Caracter',
                    perm: 'ver-capacitacionCaracter'
                },
                {
                    tab: 'Capacitaciones',
                    content: 'Capacitaciones',
                    perm: 'ver-capacitacionCapacitacion'
                },
                {
                    tab: 'Informes',
                    content: 'Informes',
                    perm: 'ver-capacitacionInforme'
                }
            ]
        }
    },
    computed: {
        permisos() {
            return this.$store.getters['user/permisos'].map(el => el.name)
        }
    },
    methods: {
        can(cadena) {
            let permiso = this.$store.getters['user/permisos']
            return _.findIndex(permiso, ['name', cadena]) >= 0 ? true : false
        }
    }
}
</script>

<style lang="scss" scoped></style>
