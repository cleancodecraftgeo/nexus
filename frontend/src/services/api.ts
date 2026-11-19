import axios from "axios"


export const backendUrl= "http://localhost:8000"

export const api = axios.create({
  baseURL: `${backendUrl}/api`,
  withCredentials: true,
  withXSRFToken: true,
})

export const backendApi  = axios.create({
  baseURL: backendUrl,
  withCredentials: true,
  withXSRFToken: true,
})

api.interceptors.request.use((config) => {
  const locale = window.localStorage.getItem('locale') ?? 'en'
  const currency = localStorage.getItem('currency') ?? 'USD'

  config.headers['Accept-Language'] = locale
  config.headers['Accept-Currency'] = currency
  return config
})
