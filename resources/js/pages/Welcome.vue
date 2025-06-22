<template>
  <v-container fluid fill-height>
      <v-row align="center" justify="center">
          <v-col cols="12">
              <v-card width="900" class="mx-auto mt-5" outlined color="rgb(232,236,239)">
                  <v-card-text class="text-md-center">
                      <p
                          class="display-2 font-weight-black"
                      >Sistema de Administración Recursos Humanos de Salud</p>
                      <p class="display-1">Bienvenid@</p>
                  </v-card-text>
              </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: 'Welcome',
  data() {
    return {}
  },
  computed: {
    role_display() {
      return this.$store.getters['user/role_display']
    }
  }
}
</script>

<style scoped>
</style>
