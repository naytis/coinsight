export default {
  methods: {
    percentColorClass(percent) {
      let colorClass = '';

      if (percent > 0) {
        colorClass = 'green';
      } else if (percent < 0) {
        colorClass = 'red';
      }

      return (colorClass += '--text text--lighten-1');
    },
  },
};
