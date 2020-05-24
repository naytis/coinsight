import {parseISO, format, formatDistanceToNow} from 'date-fns';

const formatMarketValue = value => {
  if (value === null || isNaN(value)) {
    return;
  }

  return value.toLocaleString('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: value > 999 ? 0 : 2,
    maximumFractionDigits: value > 999 ? 0 : 5,
  });
};

const prettifyDate = date => {
  return format(parseISO(date), 'MMMM dd, yyyy');
};

const dateFromNow = date => {
  return formatDistanceToNow(parseISO(date)) + ' ago';
};

const formatPercent = percent => {
  percent = percent.toFixed(2);
  return (percent > 0 ? '+' + percent : percent) + '%';
};

const formatPercentWithoutSign = percent => {
  return percent.toFixed(2) + '%';
};

const addFilters = Vue => {
  Vue.filter('formatMarketValue', formatMarketValue);
  Vue.filter('prettifyDate', prettifyDate);
  Vue.filter('dateFromNow', dateFromNow);
  Vue.filter('formatPercent', formatPercent);
  Vue.filter('formatPercentWithoutSign', formatPercentWithoutSign);
};

export {addFilters};
