import {createI18n} from 'vue-i18n'

import en from './locales/en'
import ru from './locales/ru'
import ge from './locales/ge'
import az from './locales/az'

export const i18n = createI18n({
  legacy: false,
  locale: 'ru',
  fallbackLocale: 'en',

  messages:{
    en,
    ru,
    ge,
    az,
  }
})
