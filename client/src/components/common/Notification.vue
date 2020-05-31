<template>
  <v-snackbar
    transition="fade-transition"
    right
    top
    multi-line
    :value="isActive"
    :color="type"
    :timeout="6000"
    @input="hideMessage"
  >
    <div class="justify-start">
      <v-icon small color="white">
        {{ icon }}
      </v-icon>
      <span class="ml-1">
        {{ text }}
      </span>
    </div>
    <v-btn text @click="hideMessage">
      ✕
    </v-btn>
  </v-snackbar>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import {
  GET_TEXT,
  GET_TYPE,
  HIDE_MESSAGE,
  IS_ACTIVE,
} from '../../store/notification/types';

export default {
  name: 'Notification',

  computed: {
    ...mapGetters('notification', {
      text: GET_TEXT,
      type: GET_TYPE,
      isActive: IS_ACTIVE,
    }),

    icon() {
      return this.type === 'success' ? 'mdi-check' : 'mdi-alert-circle-outline';
    },
  },

  methods: {
    ...mapActions('notification', {
      hideMessage: HIDE_MESSAGE,
    }),
  },
};
</script>
