<template>
    <v-card
        class="mx-auto fondo"
        color="#26c6da"
        dark
        max-width="400"
    >
        <v-card-title>
            <v-icon
                large
                left
            >
                mdi-file-account
            </v-icon>
            <span class="title font-weight-light">Cantidad de Licencias</span>
        </v-card-title>

        <v-card-text class="headline font-weight-bold">
            Licencias Totales 6
        </v-card-text>

        <v-card-actions>
            <v-list-item class="grow">


                <v-row
                    align="center"
                    justify="end"
                >
                    <v-select
                        v-model="value"
                        :items="items"
                        label="Tipo de Licencias"
                        multiple
                        attach
                        chips
                    >
                        <template v-slot:selection="{ item, index }">
                            <v-chip outlined v-if="index === 0">
                                <span>{{ item }}</span>
                            </v-chip>
                            <span
                                v-if="index === 1"
                                class="white--text caption"
                            >(+ {{ value.length - 1 }})</span>
                        </template>
                    </v-select>
                </v-row>
            </v-list-item>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "TarjetaCantidadLicencias",
        data: () => ({
            items: ['Corto Trat', 'Largo Trat', 'Enf Crit', 'ART', 'Gremial', 'Nac', 'Adop', 'Fallecimiento', 'Sin Goce','Ord','DLA','Cap','Cap Prolong'],
            mod: ['1', '2', '3', '4','5', '12', '13', '14','15', '16', '17', '18','19'],
            value: [],
        }),
    }
</script>

<style scoped>
    /* Permalink - use to edit and share this gradient: https://colorzilla.com/gradient-editor/#3f1674+0,348fda+50,2282d6+51,93e9ff+100 */
    .fondo{
        background: rgb(63,22,116); /* Old browsers */
        background: -moz-linear-gradient(-45deg,  rgba(63,22,116,1) 0%, rgba(52,143,218,1) 50%, rgba(34,130,214,1) 51%, rgba(147,233,255,1) 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(-45deg,  rgba(63,22,116,1) 0%,rgba(52,143,218,1) 50%,rgba(34,130,214,1) 51%,rgba(147,233,255,1) 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(135deg,  rgba(63,22,116,1) 0%,rgba(52,143,218,1) 50%,rgba(34,130,214,1) 51%,rgba(147,233,255,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3f1674', endColorstr='#93e9ff',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
    }
</style>
