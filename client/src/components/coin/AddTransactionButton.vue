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
            @click="onCreateTransaction"
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
import rules from '../../mixins/rules';
import {
  SHOW_ERROR_MESSAGE,
  SHOW_SUCCESS_MESSAGE,
} from '../../store/notification/types';

export default {
  name: 'AddTransactionButton',

  mixins: [rules],

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

    ...mapActions('notification', {
      showErrorMessage: SHOW_ERROR_MESSAGE,
      showSuccessMessage: SHOW_SUCCESS_MESSAGE,
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
        this.showErrorMessage(e);
      }
    },

    onCreateTransaction() {
      this.addTransactionDialog = false;
      try {
        this.createTransaction(this.transaction);
        this.showSuccessMessage('Transaction saved');
      } catch (e) {
        this.showErrorMessage(e);
      }
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
