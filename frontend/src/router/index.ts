import { createRouter, createWebHistory } from "vue-router";
import HomePage from "../pages/HomePage.vue";
import ProductsPage from "@/pages/ProductPage.vue";
import ProductDetailsPage from "../pages/ProductDetailsPage.vue";
import ProductsLayout from "../layouts/ProductsLayout.vue";
import CartPage from "@/pages/CartPage.vue";
import CheckoutPage from "@/pages/CheckoutPage.vue";
import OrderSuccessView from "@/pages/OrderSuccessView.vue";
import LoginPage from "@/pages/Auth/loginPage.vue";
import RegisterPage from "@/pages/Auth/RegisterPage.vue";
import OrderHistoryPage from "@/pages/OrderHistoryPage.vue";
import OrderDetailsPage from "@/pages/OrderDetailsPage.vue";
import { useAuthStore } from "@/stores/auth.store.ts";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),

    routes: [
        {
            path: "/",
            name: "home",
            component: HomePage,
        },
        {
          path:"/login",
          name:"login",
          component:LoginPage
        },
        {
          path:"/register",
          name:'register',
          component:RegisterPage
        },


        {
    path: "/products",
    name: "products-layout",
    component: ProductsLayout,

    meta: {
        breadcrumb: "Products",
    },


    children: [
        {
            path: "",
            name: "products",
            component: ProductsPage,
        },

        {
            path: ":slug",
            name: "product-details",
            component: ProductDetailsPage,

            meta: {
                breadcrumb: "Product",
            },
        },
    ],
},
{
  path:"/cart",
  name:"cart",
  component:CartPage
},

{
  path:'/checkout',
  name:"checkout",
  component:CheckoutPage
},

{
  path:'/orders',
  component:OrderHistoryPage,
  meta:{
    requiresAuth:true
  }
},

{
  path:'/orders/:id',
  component:OrderDetailsPage,
  meta:{
    requiresAuth:true
  }
},


{
  path: "/order-success/:id",
  name: "order-success",
  component: OrderSuccessView,
},

    ],
});

router.beforeEach(async (to)=>{
  const AuthStore = useAuthStore()

  if(!AuthStore.authInitialized){
    await AuthStore.fetchUser()
  }
  if(to.meta.requiredAuth && !AuthStore.isAuthenticated){
    return '/login'
  }
})

export default router;
