<template>
  <div>
    <spinner v-if="isLoading" />
    <div v-if="currentReport && !isLoading">
      <v-row>
        <portfolio-header />
      </v-row>
      <v-row>
        <chart :line-items="chartItems" line-label="Value" />
      </v-row>
      <v-row>
        <tabs />
      </v-row>
    </div>
  </div>
</template>

<script>
import {mapActions, mapGetters, mapMutations} from 'vuex';
import Chart from '../components/common/Chart';
import PortfolioHeader from '../components/portfolio/PortfolioHeader';
import Spinner from '../components/common/Spinner';
import Tabs from '../components/portfolio/Tabs';
import {
  FETCH_REPORT_BY_PORTFOLIO_ID,
  GET_CURRENT_PORTFOLIO_ID,
  GET_CURRENT_REPORT,
  GET_PORTFOLIOS,
  HAS_CURRENT_REPORT,
  IS_ASSETS_LOADING,
  IS_REPORT_LOADING,
  IS_TRANSACTIONS_LOADING,
  SET_CURRENT_PORTFOLIO_ID,
} from '../store/portfolio/types';

export default {
  name: 'Portfolio',

  components: {
    Chart,
    PortfolioHeader,
    Spinner,
    Tabs,
  },

  props: {
    id: {
      type: [String, Number],
      required: true,
    },
  },

  watch: {
    async id() {
      this.setPortfolioId(this.id);
      await this.loadPortfolio();
    },

    currentPortfolioId() {
      if (this.currentPortfolioId !== -1) {
        this.$router.replace({params: {id: this.currentPortfolioId}});
      }
    },
  },

  async created() {
    await this.loadPortfolio();
  },

  methods: {
    ...mapActions('portfolio', {
      fetchReportByPortfolioId: FETCH_REPORT_BY_PORTFOLIO_ID,
    }),

    ...mapMutations('portfolio', {
      setPortfolioId: SET_CURRENT_PORTFOLIO_ID,
    }),

    async loadPortfolio() {
      if (this.hasCurrentReport) {
        return;
      }

      try {
        await this.fetchReportByPortfolioId({
          portfolioId: this.id,
        });
      } catch (e) {
        alert(e);
      }
    },
  },

  computed: {
    ...mapGetters('portfolio', {
      isReportLoading: IS_REPORT_LOADING,
      portfolios: GET_PORTFOLIOS,
      currentReport: GET_CURRENT_REPORT,
      hasCurrentReport: HAS_CURRENT_REPORT,
      isAssetsLoading: IS_ASSETS_LOADING,
      isTransactionsLoading: IS_TRANSACTIONS_LOADING,
      currentPortfolioId: GET_CURRENT_PORTFOLIO_ID,
    }),

    isLoading() {
      return (
        this.isReportLoading ||
        this.isAssetsLoading ||
        this.isTransactionsLoading
      );
    },

    chartItems() {
      if (
        this.hasCurrentReport &&
        Object.keys(this.currentReport.chart).length !== 0
      ) {
        return this.currentReport.chart.map(item => Object.values(item));
      }
      return [];
    },
  },
};
</script>

<style scoped lang="scss">
.portfolio-value {
  line-height: 3.125rem;
}
</style>
