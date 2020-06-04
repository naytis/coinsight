import {
  RESET_IS_HISTORICAL_LOADING,
  RESET_IS_LATEST_LOADING,
  RESET_IS_PROFILE_LOADING,
  SET_HISTORICAL,
  SET_IS_HISTORICAL_LOADING,
  SET_IS_LATEST_LOADING,
  SET_IS_PROFILE_LOADING,
  SET_LATEST,
  SET_PROFILE,
} from './types';

export default {
  [SET_PROFILE]: (state, profile) => (state.profile = profile),

  [SET_LATEST]: (state, latest) => (state.latest = latest),

  [SET_HISTORICAL]: (state, historical) => (state.historical = historical),

  [SET_IS_PROFILE_LOADING]: state => (state.isProfileLoading = true),

  [SET_IS_LATEST_LOADING]: state => (state.isLatestLoading = true),

  [SET_IS_HISTORICAL_LOADING]: state => (state.isHistoricalLoading = true),

  [RESET_IS_PROFILE_LOADING]: state => (state.isProfileLoading = false),

  [RESET_IS_LATEST_LOADING]: state => (state.isLatestLoading = false),

  [RESET_IS_HISTORICAL_LOADING]: state => (state.isHistoricalLoading = false),
};
