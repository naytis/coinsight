<template>
  <div id="chart-container"></div>
</template>

<script>
import {stockChart} from 'highcharts/highstock';

export default {
  name: 'Chart',

  props: {
    lineItems: {
      type: Array,
      required: true,
    },
    lineLabel: {
      type: String,
      required: true,
    },
    columnsItems: {
      type: Array,
      required: false,
    },
    columnsLabel: {
      type: String,
      required: false,
    },
  },

  mounted() {
    this.drawChart();
  },

  watch: {
    lineItems() {
      this.drawChart();
    },

    columnsItems() {
      this.drawChart();
    },
  },

  methods: {
    drawChart() {
      stockChart('chart-container', {
        chart: {
          backgroundColor: 'transparent',
        },

        credits: {
          enabled: false,
        },

        navigator: {
          enabled: false,
        },

        scrollbar: {
          enabled: false,
        },

        plotOptions: {
          area: {
            lineWidth: 3,
          },
        },

        rangeSelector: {
          enabled: false,
        },

        series: [
          {
            name: this.lineLabel,
            data: this.lineItems,
            type: 'area',
            index: 1,
            tooltip: {
              valueDecimals: 2,
            },
            color: {
              linearGradient: {x1: 0, x2: 1, y1: 0, y2: 0},
              stops: [
                [0, '#f869d5'],
                [1, '#5650de'],
              ],
            },
            fillColor: {
              linearGradient: {x1: 0, x2: 1, y1: 0, y2: 0},
              stops: [
                [0, 'rgba(248,105,213,0.1)'],
                [1, 'rgba(86,80,222,0.1)'],
              ],
            },
          },
          {
            name: this.columnsLabel,
            data: this.columnsItems,
            type: 'column',
            yAxis: 1,
            index: 0,
            color: '#313051',
          },
        ],

        tooltip: {
          backgroundColor: '#313051',
          borderWidth: 0,
          hideDelay: 50,
          shared: true,
          followPointer: true,
          split: false,
          style: {
            color: '#dcdbf2',
          },
          valuePrefix: '$',
        },

        xAxis: {
          crosshair: {
            color: '#4e4e7c',
          },
          lineColor: 'transparent',
          tickColor: 'transparent',
          labels: {
            style: {
              color: '#dcdbf2',
            },
          },
        },

        yAxis: [
          {
            labels: {
              format: '${value}',
              style: {
                color: '#dcdbf2',
              },
            },
            gridLineWidth: 1,
            gridLineColor: '#282941',
            min: this.lowestValueForPeriod,
            max: this.highestValueForPeriod,
          },
          {
            labels: {
              align: 'left',
            },
            height: '20%',
            top: '80%',
            offset: 0,
            visible: false,
            gridLineWidth: 0,
          },
        ],
      });
    },
  },

  computed: {
    lowestValueForPeriod() {
      return this.lineItems.reduce(
        (min, item) => (item[1] < min ? item[1] : min),
        this.lineItems[0][1],
      );
    },

    highestValueForPeriod() {
      return this.lineItems.reduce(
        (max, item) => (item[1] > max ? item[1] : max),
        this.lineItems[0][1],
      );
    },
  },
};
</script>

<style scoped lang="scss">
#chart-container {
  width: 100%;
}
</style>
