import {
  GET_HISTORICAL,
  GET_LATEST,
  GET_PROFILE,
  IS_HISTORICAL_LOADING,
  IS_LATEST_LOADING,
  IS_PROFILE_LOADING,
} from './types';

export default {
  [GET_PROFILE]: state => state.profile,

  [GET_LATEST]: state => state.latest,

  [GET_HISTORICAL]: state => state.historical,

  [IS_PROFILE_LOADING]: state => state.isProfileLoading,

  [IS_LATEST_LOADING]: state => state.isLatestLoading,

  [IS_HISTORICAL_LOADING]: state => state.isHistoricalLoading,
};
