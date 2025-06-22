<template>
  <div>
    <v-container fluid class="fill-height">
      <v-row>
        <v-col>
          <tarjeta-agente
            :titulo="titulo"
            :deshabilitado="deshabilitado"
            @hayAgente="habilitarBusqueda"
          ></tarjeta-agente>
        </v-col>
      </v-row>
      <v-row v-if="!habilitarBuscar" class="py-3">
        <!-- UN SWITCH PARA QUE aparezca o desaparezca la tabla de seleccion de licencia
        luego se debe crear un pago, a traves del pago se crean asientos -->
        <!-- no tengo los codigos de uso pero que los llenen a mano por ahora -->
        <v-col>
          <v-data-table
            v-model="selected"
            v-if="!habilitarBuscar"
            :idagente="hayAgente"
            :headers="resumenLicencia"
            :items="licenciasReintegro"
            :single-select="true"
            item-key="idlicencia"
            show-select
            class="elevation-2"
          > </v-data-table
          >
        </v-col>
      </v-row>
      <v-row>
        <transition name="component-fade" mode="out-in">
          <component
            v-bind:is="view"
            @addLicencia="cambiarComponente"
            :idlicencia="idlicencia"
            :habilitarBuscar="habilitarBuscar"
            :visar="visar"
            :role="role"
            :ver="ver"
            :tipoLicencia="tipoLicencia"
          ></component>
        </transition>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import TarjetaAgente from '../utils/TarjetaAgente.vue'
export default {
  name: 'ReintegrosTratamientosIndex',
  components: {
    TarjetaAgente,
  },
  data: function () {
    return {
      habilitarBuscar: true,
      deshabilitado: false,
      ver: false,
      role: 'No seteado',
      licTitulo: 'Resumen de Licencias',
      titulo: 'Listado de Licencias',
      view: 'TablaLicencia',
      visar: 0,
      idlicencia: 0,
      tipoLicencia: 0,
    }
  },
  methods: {
    habilitarBusqueda(evt) {
            this.habilitarBuscar = evt
        },
  },
}
</script>

<style lang="scss" scoped>
</style>