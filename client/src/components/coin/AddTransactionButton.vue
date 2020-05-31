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
        <v-form v-model="isFormValid">
          <v-select
            :items="portfoliosAsArray"
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
            v-model="transaction.pricePerCoin"
            :value="coinPrice"
            flat
            suffix="$"
            :rules="[rules.greaterThanOrEquals0]"
          />
          <v-text-field
            label="Quantity"
            v-model="transaction.quantity"
            :value="transaction.quantity"
            :suffix="coinSymbol.toUpperCase()"
            flat
            :rules="[rules.greaterThan0]"
          />
          <v-text-field
            label="Fee"
            v-model="transaction.fee"
            :value="transaction.fee"
            suffix="$"
            flat
            :rules="[rules.greaterThanOrEquals0]"
          />
          <v-menu
            min-width="0"
            transition="scale-transition"
            offset-y
            :rules="[rules.required]"
          >
            <template v-slot:activator="{on}">
              <v-text-field
                v-model="formattedTransactionDatetime"
                label="Datetime"
                append-icon="mdi-calendar"
                readonly
                v-on="on"
              />
            </template>
            <v-date-picker
              v-model="transaction.datetime"
              :show-current="false"
              no-title
              scrollable
            />
          </v-menu>
        </v-form>
        <div class="d-flex justify-end">
          <v-btn
            class="mt-4 mr-2"
            color="primary"
            outlined
            @click="addTransactionDialog = false"
          >
            Cancel
          </v-btn>
          <v-btn
            class="mt-4 ml-2"
            color="primary"
            :disabled="!isFormValid"
            @click="onAddTransaction"
          >
            Save
          </v-btn>
        </div>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import {
  CREATE_TRANSACTION,
  FETCH_PORTFOLIOS,
  GET_PORTFOLIOS,
  IS_PORTFOLIOS_FETCHED,
} from '../../store/portfolio/types';
import {format, parseISO} from 'date-fns';

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
      isFormValid: false,
      rules: {
        required: v => !!v || 'This field is required',
        greaterThanOrEquals0: v =>
          v >= 0 || 'Value must be greater than or equals to 0',
        greaterThan0: v => v > 0 || 'Value must be greater than 0',
      },
      transactionTypes: ['buy', 'sell'],
      transaction: {
        portfolio: {},
        coinId: this.$route.params.id,
        pricePerCoin: this.coinPrice,
        quantity: 0,
        fee: 0,
        datetime: new Date().toISOString().slice(0, 10),
        type: 'buy',
      },
      portfoliosPage: 1,
      portfoliosPerPage: 20,
    };
  },

  methods: {
    ...mapActions('portfolio', {
      fetchPortfolios: FETCH_PORTFOLIOS,
      createTransaction: CREATE_TRANSACTION,
    }),

    async getUserPortfolios() {
      if (this.portfoliosAsArray.length !== 0 && this.isPortfoliosFetched) {
        this.transaction.portfolio = this.portfoliosAsArray[0];
        return;
      }

      try {
        await this.fetchPortfolios({
          page: this.portfoliosPage,
          perPage: this.portfoliosPerPage,
        });
        this.portfoliosPage++;
        this.transaction.portfolio = this.portfoliosAsArray[0];
      } catch (e) {
        alert(e);
      }
    },

    onAddTransaction() {
      try {
        this.createTransaction(this.transaction);
      } catch (e) {
        alert(e);
      } finally {
        alert('Transaction saved');
      }
      this.addTransactionDialog = false;
    },
  },

  computed: {
    ...mapGetters('portfolio', {
      portfolios: GET_PORTFOLIOS,
      isPortfoliosFetched: IS_PORTFOLIOS_FETCHED,
    }),

    formattedTransactionDatetime: {
      get() {
        return format(parseISO(this.transaction.datetime), 'MMMM dd, yyyy');
      },
      set(value) {
        this.transaction.datetime = value;
      },
    },

    portfoliosAsArray() {
      return Object.values(this.portfolios);
    },
  },
};
</script>

<style scoped></style>
