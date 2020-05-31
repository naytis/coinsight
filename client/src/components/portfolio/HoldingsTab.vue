<template>
  <v-data-table
    class="elevation-0 mt-4"
    :headers="headers"
    :items="currentReport.assets"
    :items-per-page="perPage"
    disable-sort
    hide-default-footer
    no-data-text="Portfolio is empty"
  >
    <template v-slot:item.coin="{item}">
      <router-link
        :to="{name: 'coin', params: {id: item.coin.id}}"
        class="d-flex"
      >
        <div class="d-flex align-center">
          <v-img :src="item.coin.icon" width="2em" height="auto" />
        </div>
        <div class="ml-4">
          <div class="symbol">
            {{ item.coin.symbol.toUpperCase() }}
          </div>
          <small class="name">{{ item.coin.name }}</small>
        </div>
      </router-link>
    </template>

    <template v-slot:item.price="{item}">
      {{ item.price | formatMarketValue }}
    </template>

    <template v-slot:item.priceChange24H="{item}">
      <span :class="percentColorClass(item.priceChange24H)">
        {{ item.priceChange24H | formatPercent }}
      </span>
    </template>

    <template v-slot:item.marketValue="{item}">
      {{ item.marketValue | formatMarketValue }}
    </template>

    <template v-slot:item.netCost="{item}">
      {{ item.netCost | formatMarketValue }}
    </template>

    <template v-slot:item.netProfit="{item}">
      {{ item.netProfit | formatMarketValue }}
    </template>
    <template v-slot:item.percentChange="{item}">
      <span :class="percentColorClass(item.percentChange)">
        {{ item.percentChange | formatPercent }}
      </span>
    </template>

    <template v-slot:item.share="{item}">
      {{ item.share | formatPercentWithoutSign }}
    </template>
  </v-data-table>
</template>

<script>
import {mapGetters} from 'vuex';
import {GET_CURRENT_REPORT} from '../../store/portfolio/types';
import percentColorClass from '../../mixins/percentColorClass';

export default {
  name: 'HoldingsTab',

  mixins: [percentColorClass],

  data() {
    return {
      headers: [
        {text: 'Asset', value: 'coin', width: '12%'},
        {text: 'Price', value: 'price'},
        {text: 'Change (24h)', value: 'priceChange24H'},
        {text: 'Holdings', value: 'holdings'},
        {text: 'Market Value', value: 'marketValue'},
        {text: 'Net Cost', value: 'netCost'},
        {text: 'Net Profit', value: 'netProfit'},
        {text: 'Percent Change', value: 'percentChange'},
        {text: 'Share', value: 'share'},
      ],
      page: 2,
      perPage: 20,
    };
  },

  computed: {
    ...mapGetters('portfolio', {
      currentReport: GET_CURRENT_REPORT,
    }),
  },
};
</script>
