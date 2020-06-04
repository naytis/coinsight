import {
  FETCH_HISTORICAL,
  FETCH_LATEST,
  FETCH_PROFILE,
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
import {historicalData, marketData, profile} from '../../api/coin';

export default {
  async [FETCH_PROFILE]({commit}, {id}) {
    commit(SET_IS_PROFILE_LOADING);

    try {
      let result = await profile(id);
      commit(SET_PROFILE, result.data);
    } finally {
      commit(RESET_IS_PROFILE_LOADING);
    }
  },

  async [FETCH_LATEST]({commit}, {id}) {
    commit(SET_IS_LATEST_LOADING);

    try {
      let result = await marketData(id);
      commit(SET_LATEST, result.data);
    } finally {
      commit(RESET_IS_LATEST_LOADING);
    }
  },

  async [FETCH_HISTORICAL]({commit}, {id, period}) {
    commit(SET_IS_HISTORICAL_LOADING);

    try {
      let result = await historicalData(id, {period});
      commit(SET_HISTORICAL, result.data.historicalData);
    } finally {
      commit(RESET_IS_HISTORICAL_LOADING);
    }
  },
};
