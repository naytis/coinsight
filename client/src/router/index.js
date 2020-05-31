import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from '../views/Login';
import AuthGuard from '../components/guards/AuthGuard';
import Register from '../views/Register';
import Markets from '../views/Markets';
import Coin from '../views/Coin';
import Portfolio from '../views/Portfolio';
import News from '../views/News';
import NewsArticle from '../views/NewsArticle';
import CreatePortfolio from '../views/CreatePortfolio';
import PortfolioGuard from '../components/guards/PortfolioGuard';
import PageNotFound from '../views/PageNotFound';

Vue.use(VueRouter);

const routes = [
  {
    path: '/',
    redirect: 'portfolios',
  },
  {
    path: '/portfolios',
    name: 'portfolios',
    component: PortfolioGuard,
    children: [
      {
        path: '/portfolio/create',
        name: 'create-portfolio',
        component: CreatePortfolio,
        meta: {
          requiresAuth: true,
        },
      },
      {
        path: '/portfolios/:id',
        name: 'portfolio-page',
        component: Portfolio,
        props: true,
        meta: {
          requiresAuth: true,
        },
      },
    ],
  },
  {
    path: '/markets',
    name: 'markets',
    component: Markets,
  },
  {
    path: '/news',
    name: 'news',
    component: News,
  },
  {
    path: '/news/:id',
    name: 'news-article',
    component: NewsArticle,
  },
  {
    path: '/coins/:id',
    name: 'coin',
    component: Coin,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: {
      handleAuth: true,
    },
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      handleAuth: true,
    },
  },
  {
    path: '*',
    component: PageNotFound,
  },
];

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: [
    {
      path: '',
      component: AuthGuard,
      children: routes,
    },
  ],
});

export default router;
