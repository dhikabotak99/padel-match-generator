<template>
  <div class="bg-fuchsia-50 min-h-screen font-inter">
    <Navbar />
    <router-view></router-view>
  </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
</script>
