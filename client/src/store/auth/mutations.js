import {
  RESET_IS_USER_LOADING,
  SET_ACCESS_TOKEN,
  SET_CURRENT_USER,
  SET_IS_USER_LOADING,
  SET_REFRESH_TOKEN,
} from './types';

export default {
  [SET_ACCESS_TOKEN]: (state, token) => (state.auth.accessToken = token),

  [SET_REFRESH_TOKEN]: (state, token) => (state.auth.refreshToken = token),

  [SET_CURRENT_USER]: (state, user) => (state.user = user),

  [SET_IS_USER_LOADING]: state => (state.isUserLoading = true),

  [RESET_IS_USER_LOADING]: state => (state.isUserLoading = false),
};
