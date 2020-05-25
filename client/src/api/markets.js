import httpClient from '../services/httpClient';

const globalStats = () => httpClient.get('/global');

const coins = params =>
  httpClient.get('/coins', {
    params,
  });

export {globalStats, coins};
