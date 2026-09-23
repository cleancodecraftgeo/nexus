import { api, backendApi  } from "@/services/api"

export const authService = {
  csrf() {
    return backendApi.get("/sanctum/csrf-cookie")
  },

  login(email: string, password: string) {
    return api.post("/login", {
      email,
      password,
    })
  },

  register(
    name: string,
    email: string,
    password: string,
    passwordConfirmation: string
  ) {
    return api.post("/register", {
      name,
      email,
      password,
      password_confirmation: passwordConfirmation,
    })
  },

  logout() {
    return api.post("/logout")
  },

  user() {
    return api.get("/user")
  },
  updateProfile(name:string, email:string){
    return api.put('/profile',{
      name,
      email,
    })
  }
}
