import httpClient from '../services/httpClient';

const getUserPortfolios = params =>
  httpClient.get('/portfolios', {
    params,
    requestOptions: {useAccessToken: true},
  });

const createPortfolio = params =>
  httpClient.post('/portfolios', params, {
    requestOptions: {useAccessToken: true},
  });

const updatePortfolio = (id, params) =>
  httpClient.put(`/portfolios/${id}`, params, {
    requestOptions: {useAccessToken: true},
  });

const deletePortfolio = id =>
  httpClient.destroy(`/portfolios/${id}`, {
    requestOptions: {useAccessToken: true},
  });

const getPortfolioOverview = id =>
  httpClient.get(`/portfolios/${id}`, {requestOptions: {useAccessToken: true}});

const getPortfolioChart = id =>
  httpClient.get(`/portfolios/${id}/chart`, {
    requestOptions: {useAccessToken: true},
  });

const getPortfolioAssets = (id, params) =>
  httpClient.get(`/portfolios/${id}/assets`, {
    params,
    requestOptions: {useAccessToken: true},
  });

const getPortfolioTransactions = params =>
  httpClient.get('/transactions', {
    params,
    requestOptions: {useAccessToken: true},
  });

const createTransaction = params =>
  httpClient.post('/transactions', params, {
    requestOptions: {useAccessToken: true},
  });

const updateTransaction = (id, params) =>
  httpClient.put(`/transactions/${id}`, params, {
    requestOptions: {useAccessToken: true},
  });

const deleteTransaction = id =>
  httpClient.destroy(`/transactions/${id}`, {
    requestOptions: {useAccessToken: true},
  });

export {
  getUserPortfolios,
  createPortfolio,
  updatePortfolio,
  deletePortfolio,
  getPortfolioOverview,
  getPortfolioChart,
  getPortfolioAssets,
  getPortfolioTransactions,
  createTransaction,
  updateTransaction,
  deleteTransaction,
};
