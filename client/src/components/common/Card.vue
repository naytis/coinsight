<template>
  <div>
    <v-card elevation="0" v-if="!isDataLoading">
      <v-card-text class="subtitle-1">
        <div>
          <div>{{ title }}</div>
          <div>
            <div v-if="value">
              <span class="title">{{ dynamicFilter(filter)(value) }}</span>
              <span v-if="percent" :class="percentColorClass(percent)">
                {{ percent | formatPercent }}
              </span>
            </div>
            <div v-else>No data available</div>
          </div>
        </div>
      </v-card-text>
    </v-card>
    <v-skeleton-loader v-else type="image" height="7em" />
  </div>
</template>

<script>
import percentColorClass from '../mixins/percentColorClass';

export default {
  name: 'Card',

  mixins: [percentColorClass],

  props: {
    title: {
      type: String,
      required: true,
    },
    value: [Number, String],
    filter: {
      type: String,
      required: false,
      validator: value =>
        ['formatMarketValue', 'formatPercentWithoutSign'].indexOf(value) !== -1,
    },
    percent: Number,
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

<style scoped></style>
