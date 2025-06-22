<template>
  <v-data-table :headers="headers" :items="personas" sort-by="calories" class="elevation-1">
    <template v-slot:item.fecha_nacimiento="{ item }">{{ item.fecha_nacimiento | fechaAcomodada }}</template>
    <template v-slot:item.idtipoParentesco="{ item }">{{ item.idtipoParentesco | parentescoConv }}</template>
    <template v-slot:item.discapacidad="{ item }">{{ item.discapacidad ? "Si" : "No" }}</template>
    <template v-slot:item.documento="{ item }">{{ item.documento }}</template>

    <template v-slot:top>
      <v-toolbar flat color="white">
        <v-toolbar-title>Familiares Declarados</v-toolbar-title>
        <v-divider class="mx-4" inset vertical></v-divider>
        <v-spacer></v-spacer>
        <v-dialog v-model="dialog" max-width="600px" height="70%">
          <template v-slot:activator="{ on }">
            <v-btn color="secondary" dark class="m-2" @click="volver">
              <v-icon left>mdi-backspace</v-icon>Volver a DDJJ
            </v-btn>
            <v-btn
              v-if="expediente === 0"
              color="success"
              dark
              class="m-2"
              @click="guardarExpediente"
            >
              <v-icon left>mdi-content-save</v-icon>Guardar DDJJ
            </v-btn>
            <v-btn v-if="expediente === 0" color="primary" dark class="m-2" v-on="on">
              <v-icon left>mdi-account-plus</v-icon>Agregar Familiar
            </v-btn>
          </template>
          <v-card>
            <v-form ref="form" v-model="valid">
              <v-card-title class="font-weight-regular blue-grey white--text headline">
                <span class="white--text">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>
                  <div class="d-flex flex-column">
                    <v-card class="d-flex flex-row">
                      <v-text-field v-model="documento" label="Documento" :rules="documentoRules"></v-text-field>
                    </v-card>

                    <v-card>
                      <v-text-field v-model="editedItem.nombre" label="Nombre" :rules="nombreRules"></v-text-field>
                    </v-card>

                    <v-card>
                      <v-text-field
                        v-model="editedItem.apellido"
                        label="Apellido"
                        :rules="apellidoRules"
                      ></v-text-field>
                    </v-card>

                    <v-card>
                      <v-menu
                        :close-on-content-click="false"
                        full-width
                        max-width="290px"
                        min-width="290px"
                        offset-y
                        ref="menuFechaNacimiento"
                        transition="scale-transition"
                        v-model="menuFechaNacimiento"
                      >
                        <template v-slot:activator="{ on }">
                          <v-text-field
                            v-model="dateFormatted"
                            hint="DD/MM/YYYY"
                            label="Fecha de Nacimiento"
                            persistent-hint
                            prepend-icon="mdi-calendar"
                            :rules="nacRules"
                            @blur="editedItem.fecha_nacimiento = parseDate(dateFormatted)"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          @input="menuFechaNacimiento = false"
                          locale="es-AR"
                          no-title
                          v-model="editedItem.fecha_nacimiento"
                        ></v-date-picker>
                      </v-menu>
                    </v-card>

                    <v-card>
                      <v-select
                        v-model="editedItem.idtipoParentesco"
                        :items="parentescos"
                        item-text="text"
                        item-value="value"
                        label="Parentesco"
                        :rules="parentescoRules"
                      ></v-select>
                    </v-card>

                    <v-card>
                      <v-select
                        v-model="editedItem.discapacidad"
                        :items="options"
                        item-text="text"
                        item-value="value"
                        label="Discapacidad"
                        :rules="discapacidadRules"
                        :disabled="habilitarDiscapacitado"
                      ></v-select>
                    </v-card>
                  </div>
                </v-container>
              </v-card-text>

              <v-card-actions class="blue-grey white--text">
                <v-spacer></v-spacer>
                <v-btn color="red lighten-5" @click="close">
                  <span>Cancelar</span>
                </v-btn>
                <v-btn color="green lighten-4" @click="save">
                  <span>Guardar</span>
                </v-btn>
              </v-card-actions>
            </v-form>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon v-if="expediente === 0" large class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
      <v-icon v-if="expediente === 0" large @click="deleteItem(item)">mdi-delete</v-icon>
    </template>
    <template v-slot:no-data>No hay Datos Cargados</template>
  </v-data-table>
</template>

<script>
import moment from 'moment'

export default {
  name: 'TablaFamiliar',
  filters: {
    parentescoConv: function(value) {
      console.log('se ejecuta el filtro')
      switch (value) {
        case 0:
          return 'Ninguno'
          break
        case 1:
          return 'Padre'
          break
        case 2:
          return 'Madre'
          break
        case 3:
          return 'Suegro'
          break
        case 4:
          return 'Suegra'
          break
        case 5:
          return 'Hijo'
          break
        case 6:
          return 'Hija'
          break
        case 7:
          return 'Yerno'
          break
        case 8:
          return 'Nuera'
          break
        case 9:
          return 'Abuelo'
          break
        case 10:
          return 'Abuela'
          break
        case 11:
          return 'Nieto'
          break
        case 12:
          return 'Nieta'
          break
        case 13:
          return 'Hermano'
          break
        case 14:
          return 'Hermana'
          break
        case 15:
          return 'Cuñado'
          break
        case 16:
          return 'Cuñada'
          break
        case 17:
          return 'Primo'
          break
        case 18:
          return 'Prima'
          break
        case 19:
          return 'Tio'
          break
        case 20:
          return 'Tia'
          break
        case 21:
          return 'Sobrino'
          break
        case 22:
          return 'Sobrina'
          break
        case 23:
          return 'Biznieto'
          break
        case 24:
          return 'Biznieta'
          break
        case 25:
          return 'Bisabuelo'
          break
        case 26:
          return 'Bisabuela'
          break
        case 27:
          return 'Conyuge'
          break
      }
    }
  },
  props: {
    expediente: {
      type: Number,
      default: 0
    },
    ver: {
      type: Boolean,
      default: false
    }
  },
  data: vm => ({
    declarado: '',
    habilitarDiscapacitado: true,
    ejecutadoBusquedadDiscapacitado: false,
    documento: '',
    valid: true,
    menuFechaNacimiento: false,
    dialog: false,
    personas: [],
    editedIndex: -1,
    dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
    documentoRules: [
      v => !!v || 'Documento es requerido',
      v =>
        (v && v.length <= 10) || 'el documento debe tener menos de 10 digitos',
      v => (v && v.length >= 6) || 'el documento debe tener al menos 6 digitos'
    ],
    nombreRules: [
      v => !!v || 'Nombre es requerido',
      v => (v && v.length > 1) || 'el nombre debe tener al menos de 2 letras'
    ],
    apellidoRules: [
      v => !!v || 'Apellido es requerido',
      v => (v && v.length > 1) || 'el apellido debe tener al menos de 2 letras'
    ],
    nacRules: [v => !!v || 'Fecha de Nacimiento es requerido'],
    discapacidadRules: [v => v != null || 'Discapacidad es requerido'],
    parentescoRules: [
      v => !!v || 'Parentesco es requerido',
      v => (v && v !== 0) || 'no ha seleccionado un parentesco'
    ],
    editedItem: {
      idpersona: '',
      documento: '',
      nombre: '',
      apellido: '',
      fecha_nacimiento: '',
      discapacidad: false,
      idtipoParentesco: 0
    },
    defaultItem: {
      idpersona: '',
      documento: '',
      nombre: '',
      apellido: '',
      fecha_nacimiento: '',
      discapacidad: false,
      idtipoParentesco: 0
    },
    parentescos: [
      { value: 0, text: 'Parentesco' },
      { value: 1, text: 'Padre' },
      { value: 2, text: 'Madre' },
      { value: 3, text: 'Suegro' },
      { value: 4, text: 'Suegra' },
      { value: 5, text: 'Hijo' },
      { value: 6, text: 'Hija' },
      { value: 7, text: 'Yerno' },
      { value: 8, text: 'Nuera' },
      { value: 9, text: 'Abuelo' },
      { value: 10, text: 'Abuela' },
      { value: 11, text: 'Nieto' },
      { value: 12, text: 'Nieta' },
      { value: 13, text: 'Hermano' },
      { value: 14, text: 'Hermana' },
      { value: 15, text: 'Cuñado' },
      { value: 16, text: 'Cuñada' },
      { value: 17, text: 'Primo' },
      { value: 18, text: 'Prima' },
      { value: 19, text: 'Tio' },
      { value: 20, text: 'Tia' },
      { value: 21, text: 'Sobrino' },
      { value: 22, text: 'Sobrina' },
      { value: 23, text: 'Biznieto' },
      { value: 24, text: 'Biznieta' },
      { value: 25, text: 'Bisabuelo' },
      { value: 26, text: 'Bisabuela' },
      { value: 27, text: 'Conyuge' }
    ],
    options: [
      { value: false, text: 'No' },
      { value: true, text: 'Si' }
    ]
  }),

  computed: {
    get_personas() {
      return this.$store.getters['grupo/obtenerPersonas']
    },
    hayPersona() {
      return false
    },
    get_persona() {
      return this.$store.getters['grupo/obtenerPersona']
    },
    get_agente() {
      return this.$store.state.agente.agente
    },
    fechaActual() {
      let hoy = new Date()
      return (
        hoy.getDate() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getFullYear()
      )
    },
    formatearFechaNacimiento() {
      return this.editedItem.fecha_nacimiento
        ? moment(this.editedItem.fecha_nacimiento).format('DD/MM/YYYY')
        : ''
    },
    headers() {
      return [
        { text: 'Documento', value: 'documento' },
        { text: 'Nombre', value: 'nombre' },
        { text: 'Apellido', value: 'apellido' },
        { text: 'Fecha de Nacimiento', value: 'fecha_nacimiento' },
        {
          text: 'Discapacidad Presente',
          value: 'discapacidad',
          sortable: true,
          filter: value => {
            return value ? 'Si' : 'No'
          }
        },
        {
          text: 'Parentesco',
          value: 'idtipoParentesco',
          sortable: true,
          filter: value => {
            switch (value) {
              case 0:
                return 'Ninguno'
                break
              case 1:
                return 'Padre'
                break
              case 2:
                return 'Madre'
                break
              case 3:
                return 'Suegro'
                break
              case 4:
                return 'Suegra'
                break
              case 5:
                return 'Hijo'
                break
              case 6:
                return 'Hija'
                break
              case 7:
                return 'Yerno'
                break
              case 8:
                return 'Nuera'
                break
              case 9:
                return 'Abuelo'
                break
              case 10:
                return 'Abuela'
                break
              case 11:
                return 'Nieto'
                break
              case 12:
                return 'Nieta'
                break
              case 13:
                return 'Hermano'
                break
              case 14:
                return 'Hermana'
                break
              case 15:
                return 'Cuñado'
                break
              case 16:
                return 'Cuñada'
                break
              case 17:
                return 'Primo'
                break
              case 18:
                return 'Prima'
                break
              case 19:
                return 'Tio'
                break
              case 20:
                return 'Tia'
                break
              case 21:
                return 'Sobrino'
                break
              case 22:
                return 'Sobrina'
                break
              case 23:
                return 'Biznieto'
                break
              case 24:
                return 'Biznieta'
                break
              case 25:
                return 'Bisabuelo'
                break
              case 26:
                return 'Bisabuela'
                break
              case 27:
                return 'Conyuge'
                break
            }
          }
        },
        {
          text: 'Acciones',
          value: 'actions'
        }
      ]
    },
    formTitle() {
      return this.editedIndex === -1 ? 'Nueva Persona' : 'Editar Persona'
    }
  },

  created() {
    this.debouncedSearchPersona = _.debounce(this.buscarPersona, 500)
    if (this.expediente !== 0) {
      this.buscarPersonaPorExpediente(this.expediente)
    }

    this.initialize()
  },
  watch: {
    documento: function(newValue, oldValue) {
      this.debouncedSearchPersona()
    },
    dialog(val) {
      val || this.close()
    },
    'editedItem.fecha_nacimiento'(val) {
      this.dateFormatted = this.formatDate(this.editedItem.fecha_nacimiento)
    }
  },

  methods: {
    formatDate(date) {
      if (!date) return null

      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    parseDate(date) {
      if (!date) return null

      const [day, month, year] = date.split('/')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    },
    volver() {
      this.$emit('addExpediente')
    },
    validate() {
      this.$refs.form.validate()
    },
    reset() {
      this.$refs.form.reset()
    },
    resetValidation() {
      this.$refs.form.resetValidation()
    },
    initialize() {},

    editItem(item) {
      this.editedIndex = this.personas.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    deleteItem(item) {
      const index = this.personas.indexOf(item)
      confirm('¿Estas seguro de borrar a esta persona?') &&
        this.personas.splice(index, 1)
    },

    close() {
      this.dialog = false
      setTimeout(() => {
        this.documento = ''
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      }, 300)
    },

    save() {
      this.validate()
      if (this.valid) {
        if (this.editedIndex > -1) {
          this.editedItem.documento = this.documento
          Object.assign(this.personas[this.editedIndex], this.editedItem)
        } else {
          this.editedItem.documento = this.documento
          this.personas.push(this.editedItem)
        }
        this.close()
      }
    },
    async buscarPersona() {
      await this.$store
        .dispatch('grupo/getPersona', [
          this.$store.getters['agente/agente'].documento,
          this.documento
        ])
        .then(() => {
          this.buscarDiscapacitado()
          if (this.$store.state.grupo.personaNueva.nombre != null) {
            this.editedItem.idpersona = this.get_persona.idpersona
            this.editedItem.nombre = this.get_persona.nombre
            this.editedItem.apellido = this.get_persona.apellido
            this.editedItem.fecha_nacimiento = this.get_persona.fecha_nacimiento.slice(
              0,
              10
            )
          } else {
            this.idpersona = 0
          }
        })
        .catch(err => {
          console.log(
            'Error al encontrar persona con documento: ' +
              err +
              ' ' +
              this.documento
          )
        })
    },
    async buscarDiscapacitado() {
      await this.$store
        .dispatch('grupo/getDiscapacitado', [
          this.$store.getters['agente/agente'].idagente,
          this.documento
        ])
        .then(() => {
          console.log(
            'Persona discapacitada en otro agente? ',
            this.$store.getters['grupo/get_declarado']
          )
          this.declarado = this.$store.getters['grupo/get_declarado']
          if (!this.declarado) {
            this.habilitarDiscapacitado = false
          } else {
            this.habilitarDiscapacitado = false
          }
          this.ejecutadoBusquedadDiscapacitado = true
          this.editedItem.discapacidad = false
        })
        .catch(err => {
          console.log(
            'Error al encontrar persona con documento discapacitada: ' +
              err +
              ' ' +
              this.documento
          )
        })
    },
    async buscarPersonaPorExpediente(expediente) {
      await this.$store
        .dispatch('grupo/getPersonaPorExpediente', expediente)
        .then(() => {
          this.personas = Array.from(this.get_personas)
          console.log('this.personas = ' + this.personas)
        })
        .catch(err => {
          console.log('Error al encontrar persona con expediente: ' + err)
        })
    },
    async guardarExpediente() {
      if (this.personas.length !== 0) {
        await this.$store
          .dispatch('adminGrupo/postExpediente', [
            this.personas,
            this.get_agente
          ])
          .then(() => {
            console.log(
              'this.$store.getters["grupo/foundPersona"] = ' +
                this.$store.state.grupo.personaNueva
            )
            this.$emit('addExpediente')
          })
          .catch(err => {
            console.log(
              'Error al encontrar persona con documento: ' +
                err +
                ' ' +
                this.documento
            )
          })
      }
    }
  }
}
</script>

<style scoped>
</style>
