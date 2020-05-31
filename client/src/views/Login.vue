<template>
  <v-row align="center" justify="center" class="fill-height">
    <v-col cols="5">
      <h1 class="pa-4">Welcome to coinsight!</h1>
      <v-form autocomplete="off" v-model="isFormValid">
        <v-text-field
          @keyup.enter="onLogin"
          label="Username"
          v-model="input.username"
          outlined
          flat
          :disabled="isPending"
          :rules="[rules.required]"
        />

        <v-text-field
          @keyup.enter="onLogin"
          label="Password"
          type="password"
          v-model="input.password"
          outlined
          :disabled="isPending"
          :rules="[rules.required]"
        />
        <v-btn
          @click="onLogin"
          block
          elevation="0"
          :disabled="!isFormValid"
          :loading="isPending"
          color="primary"
          height="4em"
        >
          Login
        </v-btn>
        <div class="text-center py-4"><span>or</span></div>
        <v-btn
          color="primary"
          outlined
          height="4em"
          block
          :to="{name: 'register'}"
        >
          Sign up
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
</template>

<script>
import {LOGIN} from '../store/auth/types';
import {mapActions} from 'vuex';
import rules from '../mixins/rules';
import {SHOW_ERROR_MESSAGE} from '../store/notification/types';

export default {
  name: 'Login',

  mixins: [rules],

  data() {
    return {
      isPending: false,
      input: {
        username: '',
        password: '',
      },
      isFormValid: false,
    };
  },

  methods: {
    async onLogin() {
      if (!this.isFormValid) {
        return;
      }

      this.isPending = true;

      try {
        await this.login({
          username: this.input.username,
          password: this.input.password,
        });

        if (this.$route.query.redirect) {
          await this.$router.push({path: this.$route.query.redirect});
        } else {
          await this.$router.push({name: 'portfolios'});
        }
      } catch (e) {
        this.showErrorMessage(e);
      }

      this.isPending = false;
    },

    ...mapActions('auth', {
      login: LOGIN,
    }),

    ...mapActions('notification', {
      showErrorMessage: SHOW_ERROR_MESSAGE,
    }),
  },
};
</script>
