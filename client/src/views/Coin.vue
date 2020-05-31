<template>
  <v-col>
    <spinner
      v-if="isProfileLoading || isMarketDataLoading || isHistoricalDataLoading"
    />
    <v-row>
      <v-col>
        <v-row v-if="!isProfileLoading">
          <v-col class="py-0">
            <div class="d-flex align-center">
              <div>
                <v-img :src="profile.icon" width="4em" height="4em" />
              </div>
              <div class="ml-3">
                <div class="display-1">
                  {{ profile.name }} ({{ profile.symbol.toUpperCase() }})
                </div>
                <div class="muted">{{ profile.tagline }}</div>
              </div>
            </div>
          </v-col>
          <v-col class="py-0" cols="5" align-self="center">
            <add-transaction-button
              :coin-price="marketData.price"
              :coin-symbol="profile.symbol"
            />
          </v-col>
        </v-row>
      </v-col>
      <v-col
        v-if="!isMarketDataLoading"
        class="d-flex align-center justify-space-between"
      >
        <div class="price">
          {{ marketData.price | formatMarketValue }}
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
    <v-row class="mt-4" v-if="!isMarketDataLoading && !isProfileLoading">
      <v-col
        cols="3"
        class="text-center"
        v-for="(marketDataItem, index) in marketDataCards"
        :key="index"
      >
        <card
          :title="marketDataItem.title"
          :is-data-loading="isMarketDataLoading"
          :value="marketDataItem.value"
          :filter="marketDataItem.filter"
          :percent="marketDataItem.percentChange"
          values-typography-class="subtitle-1"
        />
      </v-col>
    </v-row>
    <v-row v-if="!isHistoricalDataLoading" justify="end" class="mt-4 px-3">
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
    <v-row>
      <v-col v-if="!isHistoricalDataLoading">
        <chart
          :line-items="chartData.line"
          line-label="Price"
          :columns-items="chartData.columns"
          columns-label="Volume"
        />
      </v-col>
    </v-row>
    <v-row v-if="!isProfileLoading">
      <v-col cols="7">
        <h2>About {{ profile.name }}</h2>
        <p class="mt-2">{{ profile.description }}</p>
        <div>
          <h2>Links</h2>
          <div class="d-flex flex-wrap space-between pt-3">
            <v-btn
              small
              outlined
              :href="link.link"
              target="_blank"
              v-for="(link, index) in links"
              :key="index"
              color="text"
              class="mb-3 mr-3"
            >
              <v-icon small>{{ link.icon }}</v-icon>
              <span class="ml-1">{{ link.title }}</span>
            </v-btn>
          </div>
        </div>
      </v-col>
      <v-col>
        <h2>Technical details</h2>
        <v-simple-table>
          <template v-slot:default>
            <tbody>
              <tr>
                <td>Token Type</td>
                <td>
                  {{ profile.type }}
                </td>
              </tr>
              <tr>
                <td>Genesis Date</td>
                <td>
                  {{ profile.genesisDate | prettifyDate }}
                </td>
              </tr>
              <tr>
                <td>Hashing Algorithm</td>
                <td>{{ profile.hashingAlgorithm }}</td>
              </tr>
              <tr>
                <td>Consensus Mechanism</td>
                <td>{{ profile.consensusMechanism }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-col>
    </v-row>
  </v-col>
</template>

<script>
import {profile, marketData, historicalData} from '../api/coin';
import AddTransactionButton from '../components/coin/AddTransactionButton';
import Card from '../components/common/Card';
import Chart from '../components/common/Chart';
import percentColorClass from '../mixins/percentColorClass';
import Spinner from '../components/common/Spinner';

export default {
  name: 'Coin',

  components: {
    AddTransactionButton,
    Card,
    Chart,
    Spinner,
  },

  mixins: [percentColorClass],

  data() {
    return {
      isProfileLoading: false,
      isMarketDataLoading: false,
      isHistoricalDataLoading: false,
      profile: {},
      marketData: {},
      historicalData: [],
      periods: ['1d', '1w', '1m', '6m', '1y', 'all'],
      period: '1d',
      linkIconMapper: {
        twitter: 'mdi-twitter',
        telegram: 'mdi-telegram',
        reddit: 'mdi-reddit',
        github: 'mdi-github',
        website: 'mdi-web',
        whitepaper: 'mdi-file-document',
        explorer: 'mdi-compass',
      },
    };
  },

  created() {
    this.fetchCoinProfile();
    this.fetchCoinMarketData();
    this.fetchCoinHistoricalData();
  },

  watch: {
    period() {
      this.fetchCoinHistoricalData();
    },
  },

  methods: {
    async fetchCoinProfile() {
      this.isProfileLoading = true;
      try {
        let result = await profile(this.$route.params.id);
        this.profile = result.data;
      } catch (e) {
        alert(e);
      }
      this.isProfileLoading = false;
    },

    async fetchCoinMarketData() {
      this.isMarketDataLoading = true;
      try {
        let result = await marketData(this.$route.params.id);
        this.marketData = result.data;
      } catch (e) {
        alert(e);
      }
      this.isMarketDataLoading = false;
    },

    async fetchCoinHistoricalData() {
      this.isHistoricalDataLoading = true;
      try {
        let result = await historicalData(this.$route.params.id, {
          period: this.period,
        });
        this.historicalData = result.data.historicalData;
      } catch (e) {
        alert(e);
      }
      this.isHistoricalDataLoading = false;
    },

    formatSupply(supply) {
      return (
        supply.toLocaleString('en-US') + ' ' + this.profile.symbol.toUpperCase()
      );
    },
  },

  computed: {
    chartData() {
      let data = {line: [], columns: []};

      for (let i = 0; i < this.historicalData.length - 1; i++) {
        data.line.push([
          this.historicalData[i].timestamp,
          this.historicalData[i].price,
        ]);
        data.columns.push([
          this.historicalData[i].timestamp,
          this.historicalData[i].volume,
        ]);
      }

      return data;
    },

    marketDataCards() {
      return [
        {
          title: 'Market Cap',
          value: this.marketData.marketCap,
          filter: 'formatMarketValue',
        },
        {
          title: 'Volume (24h)',
          value: this.marketData.volume,
          filter: 'formatMarketValue',
        },
        {
          title: 'Circulating Supply',
          value: this.formatSupply(this.marketData.circulatingSupply),
        },
        {
          title: 'Max Supply',
          value: this.formatSupply(this.marketData.maxSupply),
        },
      ];
    },

    priceChanges() {
      return [
        {
          title: '1H',
          value: this.marketData.priceChange1H,
        },
        {
          title: '1D',
          value: this.marketData.priceChange24H,
        },
        {
          title: '1W',
          value: this.marketData.priceChange7D,
        },
        {
          title: '1M',
          value: this.marketData.priceChange30D,
        },
        {
          title: '1Y',
          value: this.marketData.priceChange1Y,
        },
      ];
    },

    links() {
      return this.profile.links.map(link => {
        let icon;
        if (link.type.toLowerCase() in this.linkIconMapper) {
          icon = this.linkIconMapper[link.type.toLowerCase()];
        } else {
          icon = this.linkIconMapper.website;
        }

        return {
          title: link.type,
          link: link.link,
          icon: icon,
        };
      });
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
