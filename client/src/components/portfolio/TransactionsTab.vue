<template>
  <div>
    <v-data-table
      class="elevation-0 mt-4"
      :headers="headers"
      :items="transactionsAsArray"
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

      <template v-slot:item.pricePerCoin="{item}">
        {{ item.pricePerCoin | formatMarketValue }}
      </template>

      <template v-slot:item.type="{item}">
        {{ item.type.charAt(0).toUpperCase() + item.type.slice(1) }}
      </template>

      <template v-slot:item.fee="{item}"> ${{ item.fee }} </template>

      <template v-slot:item.cost="{item}">
        {{ item.cost | formatMarketValue }}
      </template>

      <template v-slot:item.currentValue="{item}">
        {{ item.currentValue | formatMarketValue }}
      </template>

      <template v-slot:item.valueChange="{item}">
        <span :class="percentColorClass(item.valueChange)">
          {{ item.valueChange | formatPercent }}
        </span>
      </template>

      <template v-slot:item.datetime="{item}">
        {{ item.datetime | prettifyDate }}
      </template>

      <template v-slot:item.actions="{item}">
        <v-icon class="mr-2" @click="onOpenEditTransactionDialog(item)">
          mdi-pencil
        </v-icon>
        <v-icon @click="onOpenDeleteTransactionDialog(item)">
          mdi-trash-can-outline
        </v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="editTransactionDialog" width="400">
      <v-card>
        <v-card-title>Edit Transaction</v-card-title>
        <v-card-text>
          <v-form v-model="isFormValid">
            <v-select
              :items="Object.values(portfolios)"
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
              :value="transaction.pricePerCoin"
              flat
              suffix="$"
              :rules="[rules.greaterThanOrEquals0]"
            />
            <v-text-field
              label="Quantity"
              v-model="transaction.quantity"
              :value="transaction.quantity"
              :suffix="transaction.coin.symbol.toUpperCase()"
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
              @click="editTransactionDialog = false"
            >
              Cancel
            </v-btn>
            <v-btn
              class="mt-4 ml-2"
              color="primary"
              :disabled="!isFormValid"
              @click="onEditTransaction"
            >
              Update
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="deleteTransactionDialog" width="400">
      <v-card>
        <v-card-title>Delete this transaction?</v-card-title>
        <v-card-text>
          <div class="d-flex justify-end">
            <v-btn
              class="mt-4 mr-2"
              color="primary"
              outlined
              @click="deleteTransactionDialog = false"
            >
              Cancel
            </v-btn>
            <v-btn
              class="mt-4 ml-2"
              color="primary"
              @click="onDeleteTransaction"
            >
              Delete
            </v-btn>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import {mapActions, mapGetters} from 'vuex';
import {
  DELETE_TRANSACTION,
  FETCH_TRANSACTIONS_BY_PORTFOLIO_ID,
  GET_CURRENT_REPORT,
  GET_PORTFOLIOS,
  UPDATE_TRANSACTION,
} from '../../store/portfolio/types';
import percentColorClass from '../../mixins/percentColorClass';
import {format, parseISO} from 'date-fns';
import rules from '../../mixins/rules';
import {
  SHOW_ERROR_MESSAGE,
  SHOW_SUCCESS_MESSAGE,
} from '../../store/notification/types';

export default {
  name: 'TransactionsTab',

  mixins: [percentColorClass, rules],

  data() {
    return {
      headers: [
        {text: 'Coin', value: 'coin'},
        {text: 'Price', value: 'pricePerCoin'},
        {text: 'Type', value: 'type'},
        {text: 'Quantity', value: 'quantity'},
        {text: 'Fee', value: 'fee'},
        {text: 'Cost', value: 'cost'},
        {text: 'Value', value: 'currentValue'},
        {text: 'Value Change', value: 'valueChange'},
        {text: 'Date', value: 'datetime'},
        {text: 'Actions', value: 'actions'},
      ],
      editTransactionDialog: false,
      deleteTransactionDialog: false,
      isFormValid: false,
      transactionTypes: ['buy', 'sell'],
      transaction: {
        id: null,
        portfolio: null,
        coin: {
          symbol: '',
        },
        price: null,
        quantity: null,
        fee: null,
        datetime: new Date().toISOString(),
        type: 'buy',
      },
      page: 2,
      perPage: 20,
    };
  },

  methods: {
    ...mapActions('portfolio', {
      fetchTransactions: FETCH_TRANSACTIONS_BY_PORTFOLIO_ID,
      updateTransaction: UPDATE_TRANSACTION,
      deleteTransaction: DELETE_TRANSACTION,
    }),

    ...mapActions('notification', {
      showErrorMessage: SHOW_ERROR_MESSAGE,
      showSuccessMessage: SHOW_SUCCESS_MESSAGE,
    }),

    onOpenEditTransactionDialog(selectedTransaction) {
      this.transaction = Object.assign({}, selectedTransaction);
      this.transaction.portfolio = this.currentReport.overview.portfolio;
      this.editTransactionDialog = true;
    },

    onOpenDeleteTransactionDialog(selectedTransaction) {
      this.transaction = Object.assign({}, selectedTransaction);
      this.deleteTransactionDialog = true;
    },

    onEditTransaction() {
      this.editTransactionDialog = false;
      try {
        this.updateTransaction(this.transaction);
        this.showSuccessMessage('Transaction updated');
      } catch (e) {
        this.showErrorMessage(e);
      }
    },

    onDeleteTransaction() {
      this.editTransactionDialog = false;
      try {
        this.deleteTransaction(this.transaction.id);
        this.showSuccessMessage('Transaction deleted');
      } catch (e) {
        this.showErrorMessage(e);
      }
    },
  },

  computed: {
    ...mapGetters('portfolio', {
      currentReport: GET_CURRENT_REPORT,
      portfolios: GET_PORTFOLIOS,
    }),

    formattedTransactionDatetime: {
      get() {
        return format(parseISO(this.transaction.datetime), 'MMMM dd, yyyy');
      },
      set(value) {
        this.transaction.datetime = value;
      },
    },

    transactionsAsArray() {
      if (this.currentReport) {
        return Object.values(this.currentReport.transactions);
      }

      return [];
    },
  },
};
</script>
