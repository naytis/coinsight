<template>
  <v-col class="d-flex">
    <v-overlay
      absolute
      :value="
        isPortfoliosLoading || isPortfolioReportLoading || isTransactionsLoading
      "
    >
      <v-progress-circular
        size="60"
        indeterminate
        color="primary"
      ></v-progress-circular>
    </v-overlay>
    <div
      style="width: 100%;"
      v-if="
        portfolios.length !== 0 &&
        !(
          isPortfoliosLoading ||
          isPortfolioReportLoading ||
          isTransactionsLoading
        )
      "
    >
      <v-row justify="space-between">
        <v-col class="d-flex align-center">
          <div class="display-2">
            {{ currentPortfolio.name }}
          </div>
        </v-col>
        <v-col>
          <div class="d-flex justify-space-around">
            <div>
              <div>Total Value:</div>
              <div class="display-2">
                {{ currentReport.totalValue | formatMarketValue }}
              </div>
            </div>
            <div>
              <div>Net Profit:</div>
              <div
                class="display-2"
                :class="percentColorClass(currentReport.totalValueChange)"
              >
                {{ currentReport.totalValueChange | formatPercent }}
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <chart :line-items="chartItems" line-label="Value" />
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-tabs grow background-color="background" slider-color="primary">
            <v-tab>Holdings</v-tab>
            <v-tab>Transactions</v-tab>
            <v-tab-item>
              <v-data-table
                class="elevation-0 mt-4"
                :headers="assetsHeaders"
                :items="currentReport.assets"
                :items-per-page="perPage"
                disable-sort
                hide-default-footer
              >
                <template v-slot:item.coin="{item}">
                  <router-link
                    :to="{name: 'coin', params: {id: item.coin.id}}"
                    class="d-flex"
                  >
                    <div class="d-flex align-center">
                      <v-img :src="item.coin.icon" width="2em" height="auto" />
                    </div>
                    <div class="ml-4">
                      <div class="symbol">
                        {{ item.coin.symbol.toUpperCase() }}
                      </div>
                      <small class="name">{{ item.coin.name }}</small>
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

                <template v-slot:item.marketValue="{item}">
                  {{ item.marketValue | formatMarketValue }}
                </template>

                <template v-slot:item.netCost="{item}">
                  {{ item.netCost | formatMarketValue }}
                </template>

                <template v-slot:item.netProfit="{item}">
                  <span :class="percentColorClass(item.netProfit)">
                    {{ item.netProfit | formatPercent }}
                  </span>
                </template>

                <template v-slot:item.share="{item}">
                  {{ item.share | formatPercentWithoutSign }}
                </template>
              </v-data-table>
            </v-tab-item>
            <v-tab-item>
              <v-data-table
                class="elevation-0 mt-4"
                :headers="transactionsHeaders"
                :items="transactions[currentPortfolio.id]"
                disable-sort
                hide-default-footer
              >
                <template v-slot:item.coin="{item}">
                  <router-link
                    :to="{name: 'coin', params: {id: item.coin.id}}"
                    class="d-flex"
                  >
                    <div class="d-flex align-center">
                      <v-img :src="item.coin.icon" width="2em" height="auto" />
                    </div>
                    <div class="ml-4">
                      <div class="symbol">
                        {{ item.coin.symbol.toUpperCase() }}
                      </div>
                      <small class="name">{{ item.coin.name }}</small>
                    </div>
                  </router-link>
                </template>

                <template v-slot:item.pricePerCoin="{item}">
                  {{ item.pricePerCoin | formatMarketValue }}
                </template>

                <template v-slot:item.type="{item}">
                  {{ item.type.charAt(0).toUpperCase() + item.type.slice(1) }}
                </template>

                <template v-slot:item.fee="{item}"> ${{ item.fee }} </template>

                <template v-slot:item.cost="{item}">
                  {{ item.cost | formatMarketValue }}
                </template>

                <template v-slot:item.currentValue="{item}">
                  {{ item.currentValue | formatMarketValue }}
                </template>

                <template v-slot:item.valueChange="{item}">
                  <span :class="percentColorClass(item.valueChange)">
                    {{ item.valueChange | formatPercent }}
                  </span>
                </template>

                <template v-slot:item.datetime="{item}">
                  {{ item.datetime | prettifyDate }}
                </template>

                <template v-slot:item.actions="{}">
                  <v-row>
                    <v-btn icon><v-icon>mdi-pencil</v-icon></v-btn>
                    <v-btn icon><v-icon>mdi-trash-can-outline</v-icon></v-btn>
                  </v-row>
                </template>
              </v-data-table>
            </v-tab-item>
          </v-tabs>
        </v-col>
      </v-row>
    </div>
    <v-row
      v-else-if="
        portfolios.length === 0 &&
        !(
          isPortfoliosLoading ||
          isPortfolioReportLoading ||
          isTransactionsLoading
        )
      "
      justify="center"
      class="align-self-center"
    >
      <v-dialog v-model="createPortfolioDialog" width="400">
        <template v-slot:activator="{on}">
          <v-btn color="surface" large v-on="on">
            Create New Portfolio
          </v-btn>
        </template>
        <v-card>
          <v-card-text class="py-3 px-4">
            <v-form>
              <v-text-field label="Portfolio name" flat />
            </v-form>
            <div class="d-flex justify-end">
              <v-btn
                class="mt-2"
                color="primary"
                block
                outlined
                @click="createPortfolioDialog = false"
              >
                Create
              </v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-row>
  </v-col>
</template>

<script>
import {
  getUserPortfolios,
  getPortfolioReport,
  getPortfolioTransactions,
} from '../api/portfolio';
import {
  prettifyDate,
  formatMarketValue,
  formatPercent,
  formatPercentWithoutSign,
} from '../filters';
import Chart from '../components/Chart';

export default {
  name: 'Portfolio',

  components: {
    Chart,
  },

  data() {
    return {
      isPortfoliosLoading: false,
      isPortfolioReportLoading: false,
      isTransactionsLoading: false,
      createPortfolioDialog: false,
      portfolios: [],
      currentPortfolio: {},
      reports: [],
      transactions: [],
      assetsHeaders: [
        {text: 'Asset', value: 'coin', width: '20%'},
        {text: 'Price', value: 'price'},
        {text: 'Change (24h)', value: 'priceChange24H'},
        {text: 'Holdings', value: 'holdings'},
        {text: 'Market Value', value: 'marketValue'},
        {text: 'Net Cost', value: 'netCost'},
        {text: 'Net Profit', value: 'netProfit'},
        {text: 'Share', value: 'share'},
      ],
      transactionsHeaders: [
        {text: 'Coin', value: 'coin'},
        {text: 'Price', value: 'pricePerCoin'},
        {text: 'Type', value: 'type'},
        {text: 'Quantity', value: 'quantity'},
        {text: 'Fee', value: 'fee'},
        {text: 'Cost', value: 'cost'},
        {text: 'Value', value: 'currentValue'},
        {text: 'Value Change', value: 'valueChange'},
        {text: 'Date', value: 'datetime'},
        {text: 'Actions', value: 'actions'},
      ],
      page: 1,
      perPage: 15,
      total: 0,
    };
  },

  created() {
    this.getUserPortfolios();
  },

  methods: {
    async getUserPortfolios() {
      this.isPortfoliosLoading = true;
      try {
        let result = await getUserPortfolios({
          page: this.page,
          perPage: this.perPage,
        });
        this.portfolios = result.data.portfolios;
        this.total = result.meta.total;
        this.page++;

        if (result.data.portfolios.length !== 0) {
          this.currentPortfolio = result.data.portfolios[0];
          await this.getPortfolioReport(this.currentPortfolio.id);
          await this.getTransactions(this.currentPortfolio.id);
        }
      } catch (e) {
        alert(e);
      }

      this.isPortfoliosLoading = false;
    },

    async getPortfolioReport(portfolioId) {
      this.isPortfolioReportLoading = true;
      try {
        let result = await getPortfolioReport(portfolioId);
        this.reports[portfolioId] = result.data;
      } catch (e) {
        alert(e);
      }
      this.isPortfolioReportLoading = false;
    },

    async getTransactions(portfolioId) {
      this.isTransactionsLoading = true;
      try {
        let result = await getPortfolioTransactions({portfolioId});
        this.transactions[portfolioId] = result.data.transactions;
      } catch (e) {
        alert(e);
      }
      this.isTransactionsLoading = false;
    },

    percentColorClass(percent) {
      return (percent > 0 ? 'green' : 'red') + '--text text--lighten-1';
    },
  },

  computed: {
    currentReport() {
      return this.reports[this.currentPortfolio.id];
    },

    chartItems() {
      return this.currentReport.valueByTime.map(item => Object.values(item));
    },
  },

  filters: {
    prettifyDate(date) {
      return prettifyDate(date);
    },

    formatPercent(percent) {
      return formatPercent(percent);
    },

    formatMarketValue(value) {
      return formatMarketValue(value);
    },

    formatPercentWithoutSign(percent) {
      return formatPercentWithoutSign(percent);
    },
  },
};
</script>

<style scoped lang="scss">
.portfolio-value {
  line-height: 3.125rem;
}
</style>
