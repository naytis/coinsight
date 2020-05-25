<template>
  <v-col>
    <v-overlay absolute :value="isGlobalStatsLoading || isCoinsLoading">
      <v-progress-circular
        size="60"
        indeterminate
        color="primary"
      ></v-progress-circular>
    </v-overlay>
    <v-row justify="space-around">
      <v-col
        cols="3"
        class="text-center"
        v-for="(globalStatsItem, index) in globalStatsCards"
        :key="index"
      >
        <card
          :title="globalStatsItem.title"
          :value="globalStatsItem.value"
          :filter="globalStatsItem.filter"
          :is-data-loading="isGlobalStatsLoading"
        />
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-data-table
        class="elevation-0 mt-4"
        :headers="headers"
        :items="coins"
        :items-per-page="perPage"
        disable-sort
        hide-default-footer
        :loading="isCoinsLoading"
      >
        <template v-slot:item.coin="{item}">
          <router-link
            :to="{name: 'coin', params: {id: item.id}}"
            class="d-flex"
          >
            <div class="d-flex align-center">
              <v-img :src="item.icon" width="2em" height="auto" />
            </div>
            <div class="ml-4">
              <div class="symbol">{{ item.symbol.toUpperCase() }}</div>
              <small class="name">{{ item.name }}</small>
            </div>
          </router-link>
        </template>

        <template v-slot:item.price="{item}">
          {{ item.price | formatMarketValue }}
        </template>

        <template v-slot:item.priceChange24H="{item}">
          <span :class="percentColorClass(item.priceChange24H)">
            {{ item.priceChange24H | formatPercent }}
          </span>
        </template>

        <template v-slot:item.marketCap="{item}">
          {{ item.marketCap | formatMarketValue }}
        </template>

        <template v-slot:item.volume="{item}">
          {{ item.volume | formatMarketValue }}
        </template>
      </v-data-table>
      <v-row class="mt-2 d-flex justify-end">
        <v-btn
          fab
          color="surface"
          class="mr-1"
          :disabled="page === 1"
          @click="page--"
        >
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <v-btn
          fab
          color="surface"
          class="ml-1"
          :disabled="page === coinsPageCount"
          @click="page++"
        >
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-row>
    </v-row>
  </v-col>
</template>

<script>
import {globalStats, coins} from '../api/markets';
import Card from '../components/common/Card';
import percentColorClass from '../mixins/percentColorClass';

export default {
  name: 'Markets',

  components: {
    Card,
  },

  mixins: [percentColorClass],

  async created() {
    this.fetchGlobalStats();
    this.fetchCoins();
  },

  data() {
    return {
      isGlobalStatsLoading: false,
      isCoinsLoading: false,
      page: 1,
      perPage: 100,
      headers: [
        {text: '#', value: 'rank', width: '2%'},
        {text: 'Coin', value: 'coin', width: '20%'},
        {text: 'Price', value: 'price'},
        {text: 'Change (24h)', value: 'priceChange24H'},
        {text: 'Market Cap', value: 'marketCap'},
        {text: 'Volume', value: 'volume'},
      ],
      globalStats: {},
      coins: [],
      coinsTotal: 0,
    };
  },

  watch: {
    page: function () {
      this.fetchCoins();
    },
  },

  methods: {
    async fetchGlobalStats() {
      this.isGlobalStatsLoading = true;
      try {
        let result = await globalStats();
        this.globalStats = result.data;
      } catch (e) {
        alert(e);
      }
      this.isGlobalStatsLoading = false;
    },

    async fetchCoins() {
      this.isCoinsLoading = true;
      try {
        let result = await coins({page: this.page, perPage: this.perPage});
        this.coins = result.data.coins;
        this.coinsTotal = result.meta.total;
      } catch (e) {
        alert(e);
      }
      this.isCoinsLoading = false;
    },
  },

  computed: {
    coinsPageCount() {
      return Math.ceil(this.coinsTotal / this.perPage);
    },
    globalStatsCards() {
      return [
        {
          title: 'Market Cap',
          value: this.globalStats.marketCap,
          filter: 'formatMarketValue',
        },
        {
          title: 'Volume',
          value: this.globalStats.volume,
          filter: 'formatMarketValue',
        },
        {
          title: 'Bitcoin dominance',
          value: this.globalStats.bitcoinDominance,
          filter: 'formatPercentWithoutSign',
        },
      ];
    },
  },
};
</script>

<style scoped lang="scss">
.symbol {
  font-family: NunitoSemiBold, sans-serif;
}
.name {
  color: var(--v-text-darken2);
}
</style>
