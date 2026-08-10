import { createRouter, createWebHistory } from "vue-router";
import HomePage from "../pages/HomePage.vue";
import ProductsPage from "@/pages/ProductPage.vue";
import ProductDetailsPage from "../pages/ProductDetailsPage.vue";
import ProductsLayout from "../layouts/ProductsLayout.vue";
import CartPage from "@/pages/CartPage.vue";
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),

    routes: [
        {
            path: "/",
            name: "home",
            component: HomePage,
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
}
    ],
});

export default router;
