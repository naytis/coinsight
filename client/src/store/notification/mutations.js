import {HIDE, SHOW} from './types';

export default {
  [SHOW]: (state, {text, type}) => {
    state.active = true;
    state.text = text;
    state.type = type;
  },

  [HIDE]: state => {
    state.active = false;
  },
};
