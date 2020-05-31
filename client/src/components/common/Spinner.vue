<template>
  <v-overlay absolute :value="true">
    <v-progress-circular size="64" indeterminate color="primary" />
  </v-overlay>
</template>

<script>
export default {
  name: 'Spinner',
};
</script>
