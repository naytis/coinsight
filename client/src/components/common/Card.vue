<template>
  <div>
    <v-card elevation="0" v-if="!isDataLoading">
      <v-card-text class="subtitle-1">
        <div>{{ title }}</div>
        <div v-if="value" :class="valuesTypographyClass">
          {{ dynamicFilter(filter)(value) }}
        </div>
        <div v-else>No data available</div>
      </v-card-text>
    </v-card>
    <v-skeleton-loader v-else type="image" height="7em" />
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    title: {
      type: String,
      required: true,
    },
    value: [Number, String],
    valuesTypographyClass: {
      type: String,
      required: true,
    },
    filter: {
      type: String,
      required: false,
      validator: value =>
        ['formatMarketValue', 'formatPercentWithoutSign'].indexOf(value) !== -1,
    },
    isDataLoading: {
      type: Boolean,
      required: true,
    },
  },

  methods: {
    dynamicFilter(filter) {
      if (filter) {
        return this.$options.filters[filter];
      } else {
        return value => value;
      }
    },
  },
};
</script>
