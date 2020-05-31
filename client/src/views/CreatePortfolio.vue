<template>
  <v-row justify="center" align="center" class="fill-height">
    <spinner v-if="isReportLoading" />
    <div v-if="!isReportLoading">
      <v-btn color="primary" large @click="portfolioDialog = true">
        Create New Portfolio
      </v-btn>
      <portfolio-dialog
        :show="portfolioDialog"
        type="create"
        @cancel="portfolioDialog = false"
        @submit="onCreatePortfolio"
        :name.sync="name"
      />
    </div>
  </v-row>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import {
  CREATE_PORTFOLIO,
  GET_CURRENT_PORTFOLIO_ID,
  IS_REPORT_LOADING,
} from '../store/portfolio/types';
import Spinner from '../components/common/Spinner';
import PortfolioDialog from '../components/portfolio/PortfolioDialog';

export default {
  name: 'CreatePortfolio',

  components: {
    PortfolioDialog,
    Spinner,
  },

  data() {
    return {
      portfolioDialog: false,
      name: '',
    };
  },

  methods: {
    ...mapActions('portfolio', {
      createPortfolio: CREATE_PORTFOLIO,
    }),

    async onCreatePortfolio() {
      this.portfolioDialog = false;

      try {
        await this.createPortfolio({name: this.name});
        await this.$router.replace({
          name: 'portfolio-page',
          params: {id: this.currentPortfolioId},
        });
      } catch (e) {
        alert(e);
      }
    },
  },

  computed: {
    ...mapGetters('portfolio', {
      currentPortfolioId: GET_CURRENT_PORTFOLIO_ID,
      isReportLoading: IS_REPORT_LOADING,
    }),
  },
};
</script>
