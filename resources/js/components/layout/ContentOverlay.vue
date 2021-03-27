<template>

  <div
  @click.prevent="closeMobileMenu"
  class="content-overlay"
  ></div>

</template>

<script>

export default {

   methods: {

    closeMobileMenu() {
      Fire.$emit('menu/toggle');
    },

  },

};

</script>
