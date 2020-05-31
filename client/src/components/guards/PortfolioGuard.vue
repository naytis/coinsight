<template>
  <router-view />
</template>

<script>
import store from '../../store';
import {
  FETCH_PORTFOLIOS,
  GET_CURRENT_PORTFOLIO_ID,
  IS_PORTFOLIOS_FETCHED,
} from '../../store/portfolio/types';

export default {
  name: 'PortfolioGuard',

  async beforeRouteEnter(to, from, next) {
    if (!store.getters[`portfolio/${IS_PORTFOLIOS_FETCHED}`]) {
      await store.dispatch(`portfolio/${FETCH_PORTFOLIOS}`, {
        page: 1,
        perPage: 20,
      });
    }

    let currentPortfolioId =
      store.getters[`portfolio/${GET_CURRENT_PORTFOLIO_ID}`];

    if (
      currentPortfolioId !== -1 &&
      (to.name === 'create-portfolio' || to.name === 'portfolios')
    ) {
      next({
        name: 'portfolio-page',
        params: {id: currentPortfolioId},
      });
    } else if (
      (currentPortfolioId === -1 && to.name === 'portfolio-page') ||
      to.name === 'portfolios'
    ) {
      next({name: 'create-portfolio'});
    } else {
      next();
    }
  },
};
</script>
