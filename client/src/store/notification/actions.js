import {
  HIDE,
  HIDE_MESSAGE,
  SHOW,
  SHOW_ERROR_MESSAGE,
  SHOW_SUCCESS_MESSAGE,
} from './types';

export default {
  [SHOW_SUCCESS_MESSAGE]: ({commit}, text) =>
    commit(SHOW, {text, type: 'success'}),

  [SHOW_ERROR_MESSAGE]: ({commit}, text) => commit(SHOW, {text, type: 'error'}),

  [HIDE_MESSAGE]: ({commit}) => commit(HIDE),
};
