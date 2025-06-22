<template>
  <v-container fluid>
    <v-row>
      <v-col cols="12">
        <tarjeta-agente
          :titulo="titulo"
          :deshabilitado="deshabilitado"
          @hayAgente="habilitarBusqueda"
        ></tarjeta-agente>

        <div v-if="!habilitarBuscar && view == 'TablaExpediente'">
          <tabla-familiar-activo></tabla-familiar-activo>
        </div>
        <transition name="component-fade" mode="out-in">
          <component
            v-bind:is="view"
            :ver="ver"
            :expediente="expediente"
            :habilitarBuscar="habilitarBuscar"
            @addExpediente="cambiarComponente"
          ></component>
        </transition>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import TarjetaAgente from '../utils/TarjetaAgente.vue'
import TablaFamiliarActivo from '../utils/TablaFamiliarActivo.vue'
import TablaExpediente from './TablaExpediente.vue'
import TablaFamiliar from './TablaFamiliar.vue'

export default {
  components: {
    TarjetaAgente,
    TablaExpediente,
    TablaFamiliar,
    TablaFamiliarActivo
  },
  name: 'GrupoFamiliarIndex',
  data: function() {
    return {
      habilitarBuscar: true,
      deshabilitado: false,
      titulo: 'Listado de Declaraciones Juradas de Grupo Familiar',
      view: 'TablaExpediente',
      expediente: 0,
      ver: false
    }
  },
  created() {},
  computed: {},
  mounted() {
    // Set the initial number of items
  },
  methods: {
    habilitarBusqueda(evt) {
      this.habilitarBuscar = evt
    },
    volverHome() {
      location.replace('http://siarhu.testing/siarhu_v2')
    },
    cambiarComponente(nExpediente) {
      if (this.view === 'TablaFamiliar') {
        this.view = 'TablaExpediente'
        this.deshabilitado = false
        this.$store.state.grupo.personas = []
        this.titulo = 'Listado de Declaraciones Juradas de Grupo Familiar'
      } else {
        this.view = 'TablaFamiliar'
        this.deshabilitado = true
        this.titulo = 'Creacion de Declaracion Jurada'
        if (nExpediente !== 0) {
          this.ver = true
          this.expediente = nExpediente
        } else {
          this.ver = false
          this.expediente = 0
        }
      }
    }
  }
}
</script>

<style scoped>
.component-fade-enter-active,
.component-fade-leave-active {
  transition: opacity 0.3s ease;
}

.component-fade-enter, .component-fade-leave-to
        /* .component-fade-leave-active below version 2.1.8 */
 {
  opacity: 0;
}
</style>
