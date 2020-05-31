<template>
  <v-dialog :value="show" @click:outside="cancel" width="400">
    <v-card>
      <v-card-title>{{ type | capitalize }} portfolio</v-card-title>
      <v-card-text>
        <v-form v-model="isFormValid" @submit.prevent="">
          <v-text-field
            label="Portfolio name"
            v-model="nameInput"
            @keyup.enter="submit"
            flat
            :rules="[rules.required]"
          />
        </v-form>
        <v-btn
          class="mt-4"
          block
          color="primary"
          :disabled="!isFormValid"
          @click="submit"
        >
          {{ action }}
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import rules from '../../mixins/rules';

export default {
  name: 'PortfolioDialog',

  mixins: [rules],

  props: {
    show: {
      type: Boolean,
      required: true,
    },

    name: {
      type: String,
      required: true,
    },

    type: {
      type: String,
      required: true,
      validator: value => ['create', 'update'].indexOf(value) !== -1,
    },
  },

  data() {
    return {
      isFormValid: false,
    };
  },

  methods: {
    submit() {
      this.$emit('submit');
    },

    cancel() {
      this.$emit('cancel');
    },
  },

  computed: {
    nameInput: {
      get() {
        return this.name;
      },

      set(value) {
        this.$emit('update:name', value);
      },
    },

    action() {
      return this.type[0].toUpperCase() + this.type.slice(1);
    },
  },

  filters: {
    capitalize(value) {
      return value[0].toUpperCase() + value.slice(1);
    },
  },
};
</script>

<style scoped></style>
