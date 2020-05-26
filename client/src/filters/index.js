import {parseISO, format, formatDistanceToNow} from 'date-fns';

const formatMarketValue = value => {
  if (value === null || isNaN(value)) {
    return;
  }

  let maximumFractionDigits, minimumFractionDigits;
  if (value > 999 || value < -999) {
    maximumFractionDigits = 0;
    minimumFractionDigits = 0;
  } else if ((value < 999 && value > 0.01) || (value > -999 && value < -0.01)) {
    maximumFractionDigits = 2;
    minimumFractionDigits = 2;
  } else {
    maximumFractionDigits = 5;
    minimumFractionDigits = 2;
  }

  return value.toLocaleString('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits,
    maximumFractionDigits,
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
