import {parseISO, format, formatDistanceToNow} from 'date-fns';

const formatMarketValue = value => {
  if (value === null || isNaN(value)) {
    return;
  }

  return value.toLocaleString('en-US', {style: 'currency', currency: 'USD'});
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

export {
  formatMarketValue,
  dateFromNow,
  prettifyDate,
  formatPercent,
  formatPercentWithoutSign,
};
