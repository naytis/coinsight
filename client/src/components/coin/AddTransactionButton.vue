<template>
  <v-dialog v-model="addTransactionDialog" width="500">
    <template v-slot:activator="{on}">
      <v-btn
        @click="getUserPortfolios"
        small
        block
        color="text"
        outlined
        v-on="on"
      >
        Add transaction
      </v-btn>
    </template>
    <v-card>
      <v-card-title>Add transaction</v-card-title>
      <v-card-text>
        <v-form>
          <v-select
            :items="portfolios"
            v-model="transaction.portfolio"
            item-text="name"
            item-value="id"
            label="Portfolio"
          />
          <v-select
            :items="transactionTypes"
            v-model="transaction.type"
            label="Type"
          />
          <v-text-field
            label="Price per coin"
            v-model="transaction.price"
            :value="transaction.price"
            flat
            suffix="$"
            :rules="[rules.required, rules.greaterThanOrEquals0]"
          />
          <v-text-field
            label="Quantity"
            v-model="transaction.quantity"
            :value="transaction.quantity"
            flat
            :suffix="coinSymbol.toUpperCase()"
            :rules="[rules.required, rules.greaterThanOrEquals0]"
          />
          <v-text-field
            label="Fee"
            v-model="transaction.fee"
            :value="transaction.fee"
            suffix="$"
            flat
            :rules="[rules.required, rules.greaterThanOrEquals0]"
          />
          <v-menu
            v-model="datePickerMenu"
            min-width="0"
            transition="scale-transition"
            offset-y
            :rules="[rules.required]"
          >
            <template v-slot:activator="{on}">
              <v-text-field
                v-model="transaction.datetime"
                label="Datetime"
                append-icon="mdi-calendar"
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="transaction.datetime" no-title scrollable />
          </v-menu>
        </v-form>
        <v-btn class="mt-4" block color="primary" @click="saveTransaction">
          Save transaction
        </v-btn>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import {addTransaction, getUserPortfolios} from '../../api/portfolio';

export default {
  name: 'AddTransactionButton',

  props: {
    coinPrice: {
      type: Number,
      required: true,
    },
    coinSymbol: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      addTransactionDialog: false,
      datePickerMenu: false,
      portfolios: [],
      rules: {
        required: v => !!v || 'This field is required',
        greaterThanOrEquals0: v =>
          v >= 0 || 'Value must be greater than or equals to 0',
      },
      transactionTypes: ['buy', 'sell'],
      transaction: {
        portfolio: {},
        price: this.coinPrice,
        quantity: 0,
        fee: 0,
        datetime: new Date().toISOString().slice(0, 10),
        type: 'buy',
      },
    };
  },

  methods: {
    async getUserPortfolios() {
      try {
        let result = await getUserPortfolios();
        this.portfolios = result.data.portfolios;
        this.transaction.portfolio = this.portfolios[0];
      } catch (e) {
        alert(e);
      }
    },

    saveTransaction() {
      try {
        addTransaction({
          portfolioId: this.transaction.portfolio.id,
          coinId: this.$route.params.id,
          type: this.transaction.type,
          pricePerCoin: this.transaction.price,
          quantity: this.transaction.quantity,
          fee: this.transaction.fee,
          datetime: this.transaction.datetime,
        });
      } catch (e) {
        alert(e);
      } finally {
        alert('Transaction saved');
      }
      this.addTransactionDialog = false;
    },
  },
};
</script>

<style scoped></style>
