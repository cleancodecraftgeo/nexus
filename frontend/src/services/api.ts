import axios from "axios";

export const api = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    
});

// // api.ts — mərkəzi interceptor
// api.interceptors.response.use(
//   (response) => response,
//   (error) => {
//     if (error.response.status === 401) {
//       // logout et
//     }
//     if (error.response.status === 403) {
//       // forbidden səhifəsinə yönləndir
//     }
//     if (error.response.status === 500) {
//       // "Server xətası" notification göstər
//     }
//     return Promise.reject(error)
//   }
// )
