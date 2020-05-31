<template>
  <v-col>
    <v-tabs grow slider-color="primary">
      <v-tab>Holdings</v-tab>
      <v-tab>Transactions</v-tab>
      <v-tab-item>
        <holdings-tab />
      </v-tab-item>
      <v-tab-item>
        <transactions-tab />
      </v-tab-item>
    </v-tabs>
  </v-col>
</template>

<script>
import HoldingsTab from './HoldingsTab';
import TransactionsTab from './TransactionsTab';

export default {
  name: 'Tabs',

  components: {
    HoldingsTab,
    TransactionsTab,
  },
};
</script>
