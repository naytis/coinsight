<template>
  <v-col>
    <spinner
      v-if="isProfileLoading || isLatestLoading || isHistoricalLoading"
    />
    <div>
      <coin-header />
    </div>
    <v-row v-if="!isHistoricalLoading" justify="end" class="mt-4 px-3">
      <v-btn
        class="mx-1"
        :input-value="period === value"
        small
        v-for="(value, index) in periods"
        :key="index"
        @click="period = value"
        color="surface"
      >
        {{ value }}
      </v-btn>
    </v-row>
    <v-row v-if="!isHistoricalLoading">
      <chart
        :line-items="chartData.line"
        line-label="Price"
        :columns-items="chartData.columns"
        columns-label="Volume"
      />
    </v-row>
    <v-row v-if="!isProfileLoading">
      <profile />
    </v-row>
  </v-col>
</template>

<script>
import Chart from '../components/common/Chart';
import Spinner from '../components/common/Spinner';
import {mapActions, mapGetters} from 'vuex';
import {SHOW_ERROR_MESSAGE} from '../store/notification/types';
import {
  FETCH_HISTORICAL,
  FETCH_LATEST,
  FETCH_PROFILE,
  GET_HISTORICAL,
  IS_HISTORICAL_LOADING,
  IS_LATEST_LOADING,
  IS_PROFILE_LOADING,
} from '../store/coin/types';
import CoinHeader from '../components/coin/CoinHeader';
import Profile from '../components/coin/Profile';

export default {
  name: 'Coin',

  components: {
    CoinHeader,
    Chart,
    Profile,
    Spinner,
  },

  props: {
    id: {
      type: [Number, String],
      required: true,
    },
  },

  created() {
    try {
      this.fetchProfile({id: this.id});
    } catch (e) {
      this.showErrorMessage(e);
    }

    try {
      this.fetchLatest({id: this.id});
    } catch (e) {
      this.showErrorMessage(e);
    }

    try {
      this.fetchHistorical({id: this.id, period: this.period});
    } catch (e) {
      this.showErrorMessage(e);
    }
  },

  watch: {
    period() {
      try {
        this.fetchHistorical({id: this.id, period: this.period});
      } catch (e) {
        this.showErrorMessage(e);
      }
    },
  },

  data() {
    return {
      periods: ['1d', '1w', '1m', '6m', '1y', 'all'],
      period: '1d',
    };
  },

  methods: {
    ...mapActions('notification', {
      showErrorMessage: SHOW_ERROR_MESSAGE,
    }),

    ...mapActions('coin', {
      fetchProfile: FETCH_PROFILE,
      fetchLatest: FETCH_LATEST,
      fetchHistorical: FETCH_HISTORICAL,
    }),
  },

  computed: {
    ...mapGetters('coin', {
      isProfileLoading: IS_PROFILE_LOADING,
      isLatestLoading: IS_LATEST_LOADING,
      isHistoricalLoading: IS_HISTORICAL_LOADING,
      historical: GET_HISTORICAL,
    }),

    chartData() {
      let data = {line: [], columns: []};

      for (let i = 0; i < this.historical.length - 1; i++) {
        data.line.push([
          this.historical[i].timestamp,
          this.historical[i].price,
        ]);
        data.columns.push([
          this.historical[i].timestamp,
          this.historical[i].volume,
        ]);
      }

      return data;
    },
  },
};
</script>
