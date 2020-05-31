import {
  createPortfolio,
  createTransaction,
  deletePortfolio,
  deleteTransaction,
  getPortfolioAssets,
  getPortfolioChart,
  getPortfolioOverview,
  getPortfolioTransactions,
  getUserPortfolios,
  updatePortfolio,
  updateTransaction,
} from '../../api/portfolio';
import {
  CREATE_TRANSACTION,
  CREATE_PORTFOLIO,
  DELETE_PORTFOLIO,
  FETCH_PORTFOLIOS,
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
  UPDATE_PORTFOLIO,
  UPDATE_TRANSACTION,
  FETCH_REPORT_BY_PORTFOLIO_ID,
  SET_REPORT_SKELETON,
  FETCH_ASSETS_BY_PORTFOLIO_ID,
  FETCH_TRANSACTIONS_BY_PORTFOLIO_ID,
  SET_CURRENT_PORTFOLIO_ID,
  SET_IS_PORTFOLIOS_FETCHED,
  UNSET_REPORT,
  UNSET_PORTFOLIO,
  DEC_TOTAL_PORTFOLIOS,
  INC_TOTAL_PORTFOLIOS,
  DELETE_TRANSACTION,
} from './types';

export default {
  async [FETCH_PORTFOLIOS]({commit}, {page, perPage}) {
    commit(SET_IS_PORTFOLIOS_LOADING);

    try {
      let result = await getUserPortfolios({
        page,
        perPage,
      });
      commit(SET_TOTAL_PORTFOLIOS, result.meta.total);

      commit(SET_PORTFOLIOS, result.data.portfolios);

      if (result.meta.total !== 0) {
        commit(SET_CURRENT_PORTFOLIO_ID, result.data.portfolios[0].id);
      }
    } finally {
      commit(RESET_IS_PORTFOLIOS_LOADING);
      commit(SET_IS_PORTFOLIOS_FETCHED);
    }
  },

  async [FETCH_REPORT_BY_PORTFOLIO_ID]({commit, dispatch}, {portfolioId}) {
    commit(SET_REPORT_SKELETON, portfolioId);

    commit(SET_IS_REPORT_LOADING);
    try {
      let overviewResult = await getPortfolioOverview(portfolioId);
      commit(SET_OVERVIEW, {portfolioId, overview: overviewResult.data});

      let chartResult = await getPortfolioChart(portfolioId);
      commit(SET_CHART, {portfolioId, chart: chartResult.data.chart});

      dispatch(FETCH_ASSETS_BY_PORTFOLIO_ID, {
        portfolioId,
        page: 1,
        perPage: 20,
      });
      dispatch(FETCH_TRANSACTIONS_BY_PORTFOLIO_ID, {
        portfolioId,
        page: 1,
        perPage: 20,
      });
      commit(SET_CURRENT_PORTFOLIO_ID, portfolioId);
    } finally {
      commit(RESET_IS_REPORT_LOADING);
    }
  },

  async [FETCH_ASSETS_BY_PORTFOLIO_ID]({commit}, {portfolioId, page, perPage}) {
    commit(SET_IS_ASSETS_LOADING);

    try {
      let result = await getPortfolioAssets(portfolioId, {
        page,
        perPage,
      });
      commit(SET_ASSETS, {portfolioId, assets: result.data.assets});
      commit(SET_TOTAL_ASSETS, {portfolioId, total: result.meta.total});
    } finally {
      commit(RESET_IS_ASSETS_LOADING);
    }
  },

  async [FETCH_TRANSACTIONS_BY_PORTFOLIO_ID](
    {commit},
    {portfolioId, page, perPage},
  ) {
    commit(SET_IS_TRANSACTIONS_LOADING);

    try {
      let result = await getPortfolioTransactions({
        portfolioId,
        page,
        perPage,
      });
      commit(SET_TRANSACTIONS, {
        portfolioId,
        transactions: result.data.transactions,
      });
      commit(SET_TOTAL_TRANSACTIONS, {portfolioId, total: result.meta.total});
    } finally {
      commit(RESET_IS_TRANSACTIONS_LOADING);
    }
  },

  async [CREATE_PORTFOLIO]({commit, dispatch}, {name}) {
    commit(SET_IS_REPORT_LOADING);
    try {
      let result = await createPortfolio({name});
      commit(SET_PORTFOLIO, result.data);

      await dispatch(FETCH_REPORT_BY_PORTFOLIO_ID, {
        portfolioId: result.data.id,
      });

      commit(INC_TOTAL_PORTFOLIOS);
    } finally {
      commit(RESET_IS_REPORT_LOADING);
    }
  },

  async [UPDATE_PORTFOLIO]({state, commit}, {id, name}) {
    commit(SET_IS_REPORT_LOADING);
    try {
      let result = await updatePortfolio(id, {name});
      commit(SET_PORTFOLIO, result.data);

      let overview = state.reports[result.data.id].overview;
      let portfolio = state.portfolios[result.data.id];
      commit(SET_OVERVIEW, {
        portfolioId: result.data.id,
        overview: Object.assign(overview, {portfolio}),
      });
    } finally {
      commit(RESET_IS_REPORT_LOADING);
    }
  },

  async [DELETE_PORTFOLIO]({state, commit}, id) {
    commit(SET_IS_REPORT_LOADING);
    try {
      let result = await deletePortfolio(id);
      commit(UNSET_REPORT, result.data.id);
      commit(UNSET_PORTFOLIO, result.data.id);
      commit(DEC_TOTAL_PORTFOLIOS);

      if (state.totalPortfolios !== 0) {
        commit(SET_CURRENT_PORTFOLIO_ID, Object.keys(state.portfolios)[0]);
      } else {
        commit(SET_CURRENT_PORTFOLIO_ID, -1);
      }
    } finally {
      commit(RESET_IS_REPORT_LOADING);
    }
  },

  async [CREATE_TRANSACTION]({commit}, transaction) {
    await createTransaction({
      portfolioId: transaction.portfolio.id,
      coinId: transaction.coinId,
      type: transaction.type,
      pricePerCoin: transaction.pricePerCoin,
      quantity: transaction.quantity,
      fee: transaction.fee,
      datetime: transaction.datetime,
    });

    commit(UNSET_REPORT, transaction.portfolio.id);
  },

  async [UPDATE_TRANSACTION]({state, dispatch}, transaction) {
    await updateTransaction(transaction.id, {
      portfolioId: transaction.portfolio.id,
      coinId: transaction.coinId,
      type: transaction.type,
      pricePerCoin: transaction.pricePerCoin,
      quantity: transaction.quantity,
      fee: transaction.fee,
      datetime: transaction.datetime,
    });

    dispatch(FETCH_REPORT_BY_PORTFOLIO_ID, {
      portfolioId: state.currentPortfolioId,
    });
  },

  async [DELETE_TRANSACTION]({state, dispatch}, id) {
    await deleteTransaction(id);

    dispatch(FETCH_REPORT_BY_PORTFOLIO_ID, {
      portfolioId: state.currentPortfolioId,
    });
  },
};
