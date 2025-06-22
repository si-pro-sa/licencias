<template>
  <div>
    <div class="col-md-12 shadow-lg p-4 mb-4 bg-white">
      <div class="card border-primary">
        <div class="card-header bg-primary text-white font-weight-bold">
          <h4>
            <i class="fas fa-address-card"></i>
            {{titulo}}
          </h4>
        </div>
        <div class="card-body">
          <v-row>
            <v-col>
              <v-form ref="form" v-model="valid" lazy-validation>
                <v-text-field
                  v-model="documento"
                  :counter="10"
                  :rules="documentoRules"
                  label="Documento"
                  :disabled="deshabilitado"
                  required
                ></v-text-field>
                <transition name="fade">
                  <v-alert
                    border="top"
                    color="red lighten-2"
                    dark
                    v-if="alert"
                  >Documento no encontrado</v-alert>
                </transition>
                <v-text-field v-model="agente.nombre" label="Nombre" disabled></v-text-field>
                <v-text-field v-model="agente.apellido" label="Apellido" disabled></v-text-field>
                <v-text-field :value="fechaNacimiento" label="Fecha de Nacimiento" disabled></v-text-field>
              </v-form>
            </v-col>
            <v-col>
              <v-text-field v-model="agente.funcion" label="Funcion" disabled></v-text-field>
              <v-text-field v-model="agente.planta" label="Planta" disabled></v-text-field>
              <v-text-field v-model="agente.nivel" label="Nivel" disabled></v-text-field>
            </v-col>
            <v-col>
              <v-text-field v-model="agente.tipohorario" label="Tipo Horario" disabled></v-text-field>
              <v-text-field v-model="agente.hora_desde" label="Horario Desde" disabled></v-text-field>
              <v-text-field v-model="agente.hora_hasta" label="Horario Hasta" disabled></v-text-field>
              <v-text-field v-model="agente.dias_guardia" label="Dias de Guardia" disabled></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field v-model="agente.efector" label="Efector" disabled></v-text-field>
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-text-field v-model="agente.codigoNombre" label="Servicio" disabled></v-text-field>
            </v-col>
          </v-row>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash'

export default {
  name: 'TarjetaAgente',
  created() {
    this.$store.state.agente.agente = {}
    this.debouncedSearchAgente = _.debounce(this.fetchAgente, 400)
    this.debouncedValidate = _.debounce(this.validate, 400)
  },
  mounted() {
    this.validate()
  },
  props: {
    titulo: String,
    deshabilitado: Boolean
  },
  data: () => ({
    documento: '',
    alert: false,
    valid: true,
    documentoRules: [
      v => !!v || 'El Documento es requerido',
      v => (v && v.length <= 10) || 'El documento debe tener menos de 10 cifras'
    ]
  }),
  watch: {
    documento: function(newValue, oldValue) {
      this.debouncedSearchAgente()
      this.debouncedValidate()
    }
  },
  computed: {
    fechaNacimiento() {
      var cadena = String(this.agente.fnacimiento)
      return new Date(
        cadena.slice(0, 4),
        cadena.slice(5, 7) - 1,
        cadena.slice(8, 10)
      ).toLocaleDateString()
    },
    agente() {
      return this.$store.getters['agente/agente']
    },
    isAgente: function() {
      return this.$store.getters['agente/foundAgente']
    },
    existeSancion() {
      return this.$store.getters['sancion/existe']
    }
  },
  methods: {
    validate() {
      this.$refs.form.validate()
    },
    reset() {
      this.$refs.form.reset()
    },
    resetValidation() {
      this.$refs.form.resetValidation()
    },
    // Busca al agente a traves del documento y si tiene sancion preexistente
    async fetchAgente() {
      await this.$store
        .dispatch('agente/getAgentesHijos', {
          documento: this.documento,
          hijo: this.hijo
        })
        .then(() => {
          this.tiene_sancion()
          if (this.isAgente) {
            this.$emit('hayAgente', false)
          } else {
            console.log('Error finding agente with dni: ' + this.documento)
            this.$emit('hayAgente', true)
            this.showAlert()
          }
        })
        .catch(err => {
          this.$emit('hayAgente', true)
          this.showAlert()
          console.log('Error finding agente with dni: ' + this.documento + err)
        })
    },
    // Busca si es que hay sanciones de dos años atras
    async tiene_sancion() {
      await this.$store.dispatch('sancion/existSancion', this.agente.idagente)
    },
    showAlert() {
      this.alert = !this.alert
      setTimeout(() => {
        this.alert = false
      }, 2000)
    }
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
 {
  opacity: 0;
}
</style>
