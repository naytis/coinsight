import {
  RESET_IS_ASSETS_LOADING,
  RESET_IS_REPORT_LOADING,
  RESET_IS_PORTFOLIOS_LOADING,
  RESET_IS_TRANSACTIONS_LOADING,
  SET_ASSETS,
  SET_CHART,
  SET_IS_ASSETS_LOADING,
  SET_IS_REPORT_LOADING,
  SET_IS_PORTFOLIOS_LOADING,
  SET_IS_TRANSACTIONS_LOADING,
  SET_OVERVIEW,
  SET_PORTFOLIO,
  SET_PORTFOLIOS,
  SET_TOTAL_ASSETS,
  SET_TOTAL_PORTFOLIOS,
  SET_TOTAL_TRANSACTIONS,
  SET_TRANSACTIONS,
  SET_REPORT_SKELETON,
  SET_CURRENT_PORTFOLIO_ID,
  SET_IS_PORTFOLIOS_FETCHED,
  UNSET_PORTFOLIO,
  UNSET_REPORT,
  INC_TOTAL_PORTFOLIOS,
  DEC_TOTAL_PORTFOLIOS,
} from './types';

export default {
  [SET_PORTFOLIOS](state, portfolios) {
    state.portfolios = {
      ...portfolios.reduce(
        (prev, portfolio) => ({...prev, [portfolio.id]: portfolio}),
        {},
      ),
    };
  },

  [SET_PORTFOLIO](state, portfolio) {
    state.portfolios[portfolio.id] = portfolio;
  },

  [UNSET_PORTFOLIO](state, id) {
    delete state.portfolios[id];
  },

  [SET_CURRENT_PORTFOLIO_ID](state, portfolioId) {
    state.currentPortfolioId = portfolioId;
  },

  [SET_REPORT_SKELETON](state, portfolioId) {
    state.reports[portfolioId] = {
      overview: {},
      chart: {},
      assets: [],
      transactions: [],
      meta: {
        totalAssets: 0,
        totalTransactions: 0,
      },
    };
  },

  [UNSET_REPORT](state, portfolioId) {
    delete state.reports[portfolioId];
  },

  [SET_TOTAL_PORTFOLIOS](state, total) {
    state.totalPortfolios = total;
  },

  [INC_TOTAL_PORTFOLIOS](state) {
    state.totalPortfolios++;
  },

  [DEC_TOTAL_PORTFOLIOS](state) {
    state.totalPortfolios--;
  },

  [SET_TOTAL_ASSETS](state, {portfolioId, total}) {
    state.reports[portfolioId].meta.totalAssets = total;
  },

  [SET_TOTAL_TRANSACTIONS](state, {portfolioId, total}) {
    state.reports[portfolioId].meta.totalTransactions = total;
  },

  [SET_IS_PORTFOLIOS_LOADING](state) {
    state.isPortfoliosLoading = true;
  },

  [RESET_IS_PORTFOLIOS_LOADING](state) {
    state.isPortfoliosLoading = false;
  },

  [SET_IS_PORTFOLIOS_FETCHED](state) {
    state.isPortfoliosFetched = true;
  },

  [SET_IS_REPORT_LOADING](state) {
    state.isReportLoading = true;
  },

  [RESET_IS_REPORT_LOADING](state) {
    state.isReportLoading = false;
  },

  [SET_IS_ASSETS_LOADING](state) {
    state.isAssetsLoading = true;
  },

  [RESET_IS_ASSETS_LOADING](state) {
    state.isAssetsLoading = false;
  },

  [SET_IS_TRANSACTIONS_LOADING](state) {
    state.isTransactionsLoading = true;
  },

  [RESET_IS_TRANSACTIONS_LOADING](state) {
    state.isTransactionsLoading = false;
  },

  [SET_OVERVIEW](state, {portfolioId, overview}) {
    state.reports[portfolioId].overview = overview;
  },

  [SET_CHART](state, {portfolioId, chart}) {
    state.reports[portfolioId].chart = chart;
  },

  [SET_ASSETS](state, {portfolioId, assets}) {
    state.reports[portfolioId].assets = [
      ...state.reports[portfolioId].assets,
      ...assets,
    ];
  },

  [SET_TRANSACTIONS](state, {portfolioId, transactions}) {
    state.reports[portfolioId].transactions = {
      ...state.reports[portfolioId].transactions,
      ...transactions.reduce(
        (prev, transaction) => ({...prev, [transaction.id]: transaction}),
        {},
      ),
    };
  },
};
