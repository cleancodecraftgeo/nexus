import { ProductService } from "@/services/product.service";
import type { Product } from "@/types/product";
import { defineStore } from "pinia";






export const useProductStore = defineStore("products",{


  state: ()=> ({
      products: [] as Product[] ,
      product:null as Product |null,
      meta: null,
      links: null,
      loading:true,
      error: String

  }),

  actions: {
        async fetchProducts()
        {

          const response  = await ProductService.getProducts();
          this.products = response.data
          this.meta = response.meta
          this.loading= true
        },

        async fetchProduct(slug: string) {

          const response = await ProductService.getProduct(slug)
          this.product = response.data
          this.meta = response.meta
          this.loading= true
          return response;
}

  }
});
