<template>
  <v-row align="center" justify="center">
    <v-col cols="5">
      <h1 class="pa-4">Welcome to coinsight</h1>
      <v-form autocomplete="off" v-model="isFormValid">
        <v-text-field
          @keyup.enter="onRegister"
          label="Email"
          name="email"
          type="email"
          v-model="input.email"
          outlined
          dark
          flat
          :disabled="isPending"
          :rules="[rules.required, rules.email]"
        />

        <v-text-field
          @keyup.enter="onRegister"
          label="Username"
          name="username"
          type="text"
          v-model="input.username"
          outlined
          dark
          flat
          :disabled="isPending"
          :rules="[rules.required, rules.username]"
        />

        <v-text-field
          id="password"
          @keyup.enter="onRegister"
          label="Password"
          name="password"
          type="password"
          v-model="input.password"
          outlined
          :disabled="isPending"
          :rules="[rules.required, rules.password]"
        />

        <v-text-field
          id="password"
          @keyup.enter="onRegister"
          label="Repeat password"
          name="repeated"
          type="password"
          v-model="input.repeated"
          outlined
          :disabled="isPending"
          :rules="[rules.required, rules.repeated]"
        />

        <v-btn
          @click="onRegister"
          block
          dark
          elevation="0"
          :disabled="!isFormValid"
          :loading="isPending"
          color="primary"
          height="4em"
        >
          Sign up
        </v-btn>
        <div class="text-center py-4"><span>or</span></div>
        <v-btn
          color="primary"
          outlined
          height="4em"
          block
          :to="{name: 'login'}"
        >
          Login
        </v-btn>
      </v-form>
    </v-col>
  </v-row>
</template>

<script>
import {mapActions} from 'vuex';
import {REGISTER} from '../store/auth/types';
import rules from '../mixins/rules';
import {
  SHOW_ERROR_MESSAGE,
  SHOW_SUCCESS_MESSAGE,
} from '../store/notification/types';

export default {
  name: 'Register',

  mixins: [rules],

  data() {
    return {
      isPending: false,
      input: {
        email: '',
        username: '',
        password: '',
        repeated: '',
      },
      isFormValid: false,
    };
  },
  methods: {
    async onRegister() {
      if (
        this.input.email !== '' &&
        this.input.username !== '' &&
        this.input.password !== ''
      ) {
        this.isPending = true;

        try {
          await this.register({
            email: this.input.email,
            username: this.input.username,
            password: this.input.password,
          });

        this.showSuccessMessage('You are successfully registered.');

          if (this.$route.query.redirect) {
            await this.$router.push({
              name: 'login',
              query: {redirect: this.$route.query.redirect},
            });
          } else {
            await this.$router.push({name: 'login'});
          }
        } catch (e) {
          alert(e);
        }

        this.isPending = false;
      }
    },

    isEmailValid(email) {
      return /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(email);
    },

    isUsernameValid(username) {
      return /^(?=[a-zA-Z0-9._]{3,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/.test(
        username,
      );
    },

    ...mapActions('auth', {
      register: REGISTER,
    }),

    ...mapActions('notification', {
      showSuccessMessage: SHOW_SUCCESS_MESSAGE,
      showErrorMessage: SHOW_ERROR_MESSAGE,
    }),
  },
};
</script>

<style scoped></style>
