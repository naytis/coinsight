import httpClient from '../services/httpClient';

const getNews = params => httpClient.get('/news', {params});

const getNewsArticleById = id => httpClient.get(`/news/${id}`);

export {getNews, getNewsArticleById};
