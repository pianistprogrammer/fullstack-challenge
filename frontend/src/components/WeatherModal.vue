<template>
  <div class="modal" v-if="isOpen">
    <div class="modal-overlay" @click="close"></div>
    <div class="modal-container">
      <div class="modal-header">
        <h3>{{ title }}</h3>
        <button class="modal-close" @click="close">×</button>
      </div>
      <div class="modal-body">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
export default {
  props: {
    title: String,
    isOpen: {
      type: Boolean,
      required: true,
    },
  },
  methods: {
    close() {
      this.$emit("update:isOpen", false);
    },
  },
};
</script>

<style>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-container {
  position: relative;
  background-color: #fff;
  padding: 1rem;
  max-width: 500px;
  max-height: 80vh;
  overflow-y: auto;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-close {
  font-size: 1.5rem;
  font-weight: bold;
  color: #ccc;
  background-color: transparent;
  border: none;
  cursor: pointer;
  transition: color 0.2s;
}

.modal-close:hover {
  color: #000;
}

.modal-body {
  padding: 1rem;
}
</style>
