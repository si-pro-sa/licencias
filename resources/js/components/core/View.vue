<template>
  <transition name="fade" mode="out-in">
    <router-view :key="$route.fullPath"></router-view>
  </transition>
</template>

<script>
export default {
  name: 'CoreView',
  components: {}
}
</script>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition-duration: 0.4s;
    transition-property: opacity;
    transition-timing-function: ease;
}

.fade-enter,
.fade-leave-active {
  opacity: 0;
}
</style>
