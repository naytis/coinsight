import {
  GET_PORTFOLIOS,
  IS_ASSETS_LOADING,
  IS_REPORT_LOADING,
  IS_PORTFOLIOS_LOADING,
  IS_TRANSACTIONS_LOADING,
  GET_CURRENT_PORTFOLIO_ID,
  GET_CURRENT_REPORT,
  IS_PORTFOLIOS_FETCHED,
  HAS_CURRENT_REPORT,
} from './types';

export default {
  [IS_PORTFOLIOS_LOADING]: state => state.isPortfoliosLoading,

  [IS_REPORT_LOADING]: state => state.isReportLoading,

  [HAS_CURRENT_REPORT]: state => !!state.reports[state.currentPortfolioId],

  [GET_CURRENT_REPORT]: state => {
    return state.isReportLoading ? {} : state.reports[state.currentPortfolioId];
  },

  [GET_PORTFOLIOS]: state => state.portfolios,

  [IS_ASSETS_LOADING]: state => state.isAssetsLoading,

  [IS_TRANSACTIONS_LOADING]: state => state.isTransactionsLoading,

  [GET_CURRENT_PORTFOLIO_ID]: state => state.currentPortfolioId,

  [IS_PORTFOLIOS_FETCHED]: state => state.isPortfoliosFetched,
};
