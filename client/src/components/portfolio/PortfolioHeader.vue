<template>
  <v-row justify="space-between" class="px-4">
    <v-col class="d-flex align-center">
      <v-menu bottom offset-y>
        <template v-slot:activator="{on}">
          <v-icon v-on="on" large>
            mdi-chevron-down
          </v-icon>
        </template>
        <v-list>
          <v-list-item
            v-for="portfolio in portfolios"
            :key="portfolio.id"
            @click="onChangeCurrentReport(portfolio.id)"
          >
            {{ portfolio.name }}
          </v-list-item>
          <v-divider />
          <v-list-item @click="onOpenCreateDialog">
            <v-icon class="mr-2">mdi-plus</v-icon>
            Create new portfolio
          </v-list-item>
        </v-list>
      </v-menu>
      <div class="display-2 mx-4">
        {{ currentReport.overview.portfolio.name }}
      </div>
      <v-menu bottom offset-y>
        <template v-slot:activator="{on}">
          <v-icon v-on="on">
            mdi-dots-horizontal
          </v-icon>
        </template>
        <v-list>
          <v-list-item @click="onOpenEditDialog">
            <v-icon class="mr-2">mdi-pencil</v-icon>
            Edit portfolio
          </v-list-item>
          <v-list-item @click="deletePortfolioDialog = true">
            <v-icon class="mr-2">
              mdi-trash-can-outline
            </v-icon>
            Delete portfolio
          </v-list-item>
        </v-list>
      </v-menu>
      <portfolio-dialog
        :show="createOrEditPortfolioDialog"
        :type="actionType"
        @cancel="createOrEditPortfolioDialog = false"
        @submit="onSubmitPortfolioAction"
        :name.sync="name"
      />
      <v-dialog v-model="deletePortfolioDialog" width="400">
        <v-card>
          <v-card-title>Delete this portfolio?</v-card-title>
          <v-card-text>
            <div class="d-flex justify-end">
              <v-btn
                class="mt-4 mr-2"
                color="primary"
                outlined
                @click="deletePortfolioDialog = false"
              >
                Cancel
              </v-btn>
              <v-btn
                class="mt-4 ml-2"
                color="primary"
                @click="onDeletePortfolio"
              >
                Delete
              </v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-col>
    <v-col cols="5">
      <div class="d-flex justify-space-between">
        <div>
          <div>Total Value:</div>
          <div class="display-1">
            <span v-if="currentReport.overview.totalValue !== null">
              {{ currentReport.overview.totalValue | formatMarketValue }}
            </span>
            <span v-else>—</span>
          </div>
        </div>
        <div>
          <div>Net Profit:</div>
          <div class="display-1">
            <span
              v-if="currentReport.overview.totalValueChange !== null"
              :class="
                percentColorClass(currentReport.overview.totalValueChange)
              "
            >
              {{ currentReport.overview.totalValueChange | formatPercent }}
            </span>
            <span v-else>—</span>
          </div>
        </div>
      </div>
    </v-col>
  </v-row>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import percentColorClass from '../../mixins/percentColorClass';
import {
  GET_CURRENT_REPORT,
  GET_PORTFOLIOS,
  CREATE_PORTFOLIO,
  UPDATE_PORTFOLIO,
  DELETE_PORTFOLIO,
  GET_CURRENT_PORTFOLIO_ID,
} from '../../store/portfolio/types';
import PortfolioDialog from './PortfolioDialog';
import {
  SHOW_ERROR_MESSAGE,
  SHOW_SUCCESS_MESSAGE,
} from '../../store/notification/types';

export default {
  name: 'PortfolioHeader',

  mixins: [percentColorClass],

  components: {
    PortfolioDialog,
  },

  data() {
    return {
      createOrEditPortfolioDialog: false,
      deletePortfolioDialog: false,
      actionType: 'create',
      name: '',
    };
  },

  methods: {
    ...mapActions('portfolio', {
      createPortfolio: CREATE_PORTFOLIO,
      updatePortfolio: UPDATE_PORTFOLIO,
      deletePortfolio: DELETE_PORTFOLIO,
    }),

    ...mapActions('notification', {
      showErrorMessage: SHOW_ERROR_MESSAGE,
      showSuccessMessage: SHOW_SUCCESS_MESSAGE,
    }),

    onChangeCurrentReport(portfolioId) {
      if (portfolioId === this.currentReport.overview.portfolio.id) {
        return;
      }

      this.$router.replace({params: {id: portfolioId}});
    },

    onOpenCreateDialog() {
      this.actionType = 'create';
      this.createOrEditPortfolioDialog = true;
    },

    onOpenEditDialog() {
      this.actionType = 'update';
      this.createOrEditPortfolioDialog = true;
      this.name = this.currentReport.overview.portfolio.name;
    },

    async onSubmitPortfolioAction() {
      this.createOrEditPortfolioDialog = false;

      try {
        if (this.actionType === 'create') {
          await this.createPortfolio({name: this.name});
          this.showSuccessMessage('Portfolio created');
        } else {
          await this.updatePortfolio({
            id: this.currentReport.overview.portfolio.id,
            name: this.name,
          });
          this.showSuccessMessage('Portfolio updated');
        }
      } catch (e) {
        this.showErrorMessage(e);
      }

      this.name = '';
    },

    async onDeletePortfolio() {
      this.deletePortfolioDialog = false;
      try {
        await this.deletePortfolio(this.currentReport.overview.portfolio.id);
        this.showSuccessMessage('Portfolio deleted');

        if (this.currentPortfolioId === -1) {
          await this.$router.replace({name: 'create-portfolio'});
        }

        await this.$router.replace(
          {params: {id: this.currentPortfolioId}},
          () => {},
          () => {},
        );
      } catch (e) {
        this.showErrorMessage(e);
      }
    },
  },

  computed: {
    ...mapGetters('portfolio', {
      currentReport: GET_CURRENT_REPORT,
      currentPortfolioId: GET_CURRENT_PORTFOLIO_ID,
      portfolios: GET_PORTFOLIOS,
    }),
  },
};
</script>
