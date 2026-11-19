import { defineStore } from "pinia";

export type Currency = 'USD' | 'EUR' | 'GEL'

export const useCurrencyStore = defineStore('currency', {

  state: () => ({
    currency: (localStorage.getItem('currency') as Currency) ?? 'USD',
  }),

  actions: {
      setCurrency(currency: Currency) {
        this.currency = currency
        localStorage.setItem('currency', currency)
      }
  },
})
