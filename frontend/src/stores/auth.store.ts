import { defineStore } from "pinia"
import { authService } from "@/services/auth.service"

interface User {
  id: number
  name: string
  email: string
  role: string
}

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null as User | null,
    authInitialized:false,
  }),

  getters: {
    isAuthenticated: (state) => state.user !== null,
  },

  actions: {
    async fetchUser() {
  try {
    const response = await authService.user()
    this.user = response.data.user

  } catch  {

    this.user = null
  }finally{
    this.authInitialized=true
  }
},

    async login(email: string, password: string) {
      await authService.csrf()

      const response = await authService.login(email, password)

      this.user = response.data.user
    },

    async register(
      name: string,
      email: string,
      password: string,
      passwordConfirmation: string
    ) {
      await authService.csrf()

      const response = await authService.register(
        name,
        email,
        password,
        passwordConfirmation
      )

      this.user = response.data.user
    },

    async logout() {
      await authService.logout()

      this.user = null
    },
  },
})
