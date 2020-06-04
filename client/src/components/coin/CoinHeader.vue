<template>
  <div>
    <v-row>
      <v-col>
        <v-row v-if="!isProfileLoading">
          <v-col class="py-0">
            <div class="d-flex align-center">
              <div>
                <v-img :src="profile.icon" width="4em" height="4em" />
              </div>
              <div class="ml-3">
                <div class="headline">
                  {{ profile.name }} ({{ profile.symbol.toUpperCase() }})
                </div>
                <div class="muted">{{ profile.tagline }}</div>
              </div>
            </div>
          </v-col>
          <v-col class="py-0" cols="5" align-self="center">
            <add-transaction-button
              v-if="!isLatestLoading && isLoggedIn"
              :coin-price="latest.price"
              :coin-symbol="profile.symbol"
            />
          </v-col>
        </v-row>
      </v-col>
      <v-col
        v-if="!isLatestLoading"
        class="d-flex align-center justify-space-between"
      >
        <div class="price">
          {{ latest.price | formatMarketValue }}
        </div>
        <div
          class="d-flex justify-center flex-column"
          v-for="(priceChange, index) in priceChanges"
          :key="index"
        >
          <div class="text-center">{{ priceChange.title }}</div>
          <div :class="percentColorClass(priceChange.value)">
            {{ priceChange.value | formatPercent }}
          </div>
        </div>
      </v-col>
    </v-row>
    <v-row class="mt-4" v-if="!isLatestLoading && !isProfileLoading">
      <v-col
        cols="3"
        class="text-center"
        v-for="(latestDataItem, index) in latestDataCards"
        :key="index"
      >
        <card
          :title="latestDataItem.title"
          :is-data-loading="isLatestLoading"
          :value="latestDataItem.value"
          :filter="latestDataItem.filter"
          values-typography-class="subtitle-1"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script>
import {
  GET_LATEST,
  GET_PROFILE,
  IS_LATEST_LOADING,
  IS_PROFILE_LOADING,
} from '../../store/coin/types';
import {mapGetters} from 'vuex';
import Card from '../common/Card';
import percentColorClass from '../../mixins/percentColorClass';
import AddTransactionButton from './AddTransactionButton';
import {IS_LOGGED_IN} from '../../store/auth/types';

export default {
  name: 'CoinHeader',

  components: {
    AddTransactionButton,
    Card,
  },

  mixins: [percentColorClass],

  methods: {
    formatSupply(supply) {
      if (supply === 0) {
        return '––';
      }

      return (
        supply.toLocaleString('en-US') + ' ' + this.profile.symbol.toUpperCase()
      );
    },
  },

  computed: {
    ...mapGetters('auth', {
      isLoggedIn: IS_LOGGED_IN,
    }),

    ...mapGetters('coin', {
      profile: GET_PROFILE,
      latest: GET_LATEST,
      isLatestLoading: IS_LATEST_LOADING,
      isProfileLoading: IS_PROFILE_LOADING,
    }),

    latestDataCards() {
      return [
        {
          title: 'Market Cap',
          value: this.latest.marketCap,
          filter: 'formatMarketValue',
        },
        {
          title: 'Volume (24h)',
          value: this.latest.volume,
          filter: 'formatMarketValue',
        },
        {
          title: 'Circulating Supply',
          value: this.formatSupply(this.latest.circulatingSupply),
        },
        {
          title: 'Max Supply',
          value: this.formatSupply(this.latest.maxSupply),
        },
      ];
    },

    priceChanges() {
      return [
        {
          title: '1H',
          value: this.latest.priceChange1H,
        },
        {
          title: '1D',
          value: this.latest.priceChange24H,
        },
        {
          title: '1W',
          value: this.latest.priceChange7D,
        },
        {
          title: '1M',
          value: this.latest.priceChange30D,
        },
        {
          title: '1Y',
          value: this.latest.priceChange1Y,
        },
      ];
    },
  },
};
</script>

<style scoped lang="scss">
.muted {
  color: var(--v-text-darken2);
}
.price {
  font-size: 2rem;
}
</style>
