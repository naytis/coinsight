<template>
  <v-col>
    <v-overlay
      absolute
      :value="
        isProfileLoading || isMarketDataLoading || isHistoricalDataLoading
      "
    >
      <v-progress-circular
        size="60"
        indeterminate
        color="primary"
      ></v-progress-circular>
    </v-overlay>
    <v-row>
      <v-col>
        <v-row>
          <v-col class="py-0" v-if="!isProfileLoading">
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
          <v-col class="py-0" cols="4" align-self="center">
            <v-dialog v-model="addTransactionDialog" width="700">
              <template v-slot:activator="{on}">
                <v-btn small block color="text" outlined v-on="on">
                  Add transaction
                </v-btn>
              </template>
              <v-card>
                <v-card-text class="py-4">
                  <v-tabs grow background-color="surface">
                    <v-tab>Buy</v-tab>
                    <v-tab>Sell</v-tab>
                    <v-tab-item class="py-2">
                      <v-form>
                        <v-text-field
                          label="Price per coin"
                          :value="marketData.price"
                          flat
                          prefix="$"
                        />
                        <v-text-field label="Quantity" flat value="0" />
                        <v-text-field label="Fee" prefix="$" flat value="0" />
                        <v-text-field label="Date" flat />
                      </v-form>
                      <div class="d-flex justify-end">
                        <v-btn
                          class="mt-4"
                          color="primary"
                          outlined
                          @click="addTransactionDialog = false"
                        >
                          Save transaction
                        </v-btn>
                      </div>
                    </v-tab-item>
                  </v-tabs>
                </v-card-text>
              </v-card>
            </v-dialog>
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
        <v-card elevation="0">
          <v-card-text class="subtitle-1">
            <div>{{ marketDataItem.title }}</div>
            <div v-if="!isMarketDataLoading">
              <div v-if="marketDataItem.value">
                {{ marketDataItem.value }}
                <span
                  v-if="marketDataItem.percentChange"
                  :class="percentColorClass(marketDataItem.percentChange)"
                >
                  {{ marketDataItem.percentChange | formatPercent }}
                </span>
              </div>
              <div v-else>No data available</div>
            </div>
            <v-skeleton-loader class="mt-1" v-else type="heading" />
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row justify="end" class="mt-4 px-3">
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
import {formatMarketValue} from '../filters';
import Chart from '../components/Chart';

export default {
  name: 'Coin',

  components: {
    Chart,
  },

  data() {
    return {
      isProfileLoading: false,
      isMarketDataLoading: false,
      isHistoricalDataLoading: false,
      addTransactionDialog: false,
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

    percentColorClass(percent) {
      return (percent > 0 ? 'green' : 'red') + '--text text--lighten-1';
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
          value: formatMarketValue(this.marketData.marketCap),
          percentChange: this.marketData.marketCapChange24H,
        },
        {
          title: 'Volume (24h)',
          value: formatMarketValue(this.marketData.volume),
          percentChange: this.marketData.volumeChange24H,
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
