export default {
  data() {
    return {
      rules: {
        required: v => !!v || 'This field is required',
        email: v => this.isEmailValid(v) || 'E-mail must be valid',
        username: v =>
          (v && v.length >= 3 && this.isUsernameValid(v)) ||
          'Enter the correct information',
        password: v =>
          v.length >= 8 || 'Password must be equal or more than 8 characters',
        repeated: v => v === this.input.password || 'Passwords should match',
        greaterThanOrEquals0: v =>
          v >= 0 || 'Value must be greater than or equals to 0',
        greaterThan0: v => v > 0 || 'Value must be greater than 0',
      },
    };
  },

  methods: {
    isEmailValid(email) {
      return /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/.test(email);
    },

    isUsernameValid(username) {
      return /^(?=[a-zA-Z0-9._]{3,20}$)(?!.*[_.]{2})[^_.].*[^_.]$/.test(
        username,
      );
    },
  },
};
