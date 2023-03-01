<script setup lang="ts">
import { RouterLink, RouterView } from "vue-router";
</script>

<template>
  <header>
    <div class="wrapper">
      <nav class="text-center mb-5">
        <RouterLink to="/">Home</RouterLink>
      </nav>
    </div>
  </header>
  <RouterView />
</template>

<style scoped>
.text-center{
  text-align: center;
}
.mb-5{
  margin-bottom: 5rem;
}
</style>
