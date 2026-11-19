


import { defineStore } from "pinia";
import type { Product, ProductVariant } from "../types/Product";
import { ProductService } from '@/services/product.service'


interface CartItem {
    product: Product
    variant:ProductVariant
    quantity: number
}


export const useCartStore = defineStore("cart",
  {


    state: ()=>({
      items: [] as CartItem[],

    }),

    getters:
      {
        cartCount: (state)=>
          state.items.reduce((total,item)=>total+item.quantity,0),

        cartTotal:(state)=>

          state.items.reduce((total, item)=>

             total + Number(item.variant.price) * item.quantity,0
          )




      },

      actions:
      {
        addToCart(product: Product, variant: ProductVariant, quantity: number)
        {
          const existingItem = this.items.find(
            item => item.variant.id === variant.id
          );

    if (existingItem) {
      existingItem.quantity+= quantity;
    } else {
      this.items.push({ product,variant, quantity });
    }


        },

        async refreshPrices(): Promise<void> {

          if(this.items.length === 0) {return}

          const productSlugs = [
            ...new Set(
              this.items.map((item)=> item.product.slug)
            ),
          ]
          const responses = await Promise.all(
            productSlugs.map((slug) =>
            ProductService.getProduct(slug))
          )
        const freshProducts = responses.map(
    (response) => response.data)
            this.items.forEach((item) => {
              const freshProduct = freshProducts.find(
                (product) => product.id === item.product.id
              )

              if(!freshProduct) {
                return
              }
          const freshVariant = freshProduct.variants.find((variant: { id: string; }) => variant.id === item.variant.id)

                if (!freshVariant) {
                      return}

                      item.product = freshProduct
                      item.variant = freshVariant
            })
        },



        increaseQuantity(variantID:string){
            const item = this.items.find(
              item=>item.variant.id ===variantID
            );
            if (!item) return;
            if(item.quantity<item.variant.stock){
              item.quantity++;
            }
            else{
              console.warn('There are not enough products in stock ');
            }
        },

        decreaseQuantity(variantID:string){

          const item = this.items.find(
            item=>item.variant.id===variantID
          );
          if(item&&item.quantity>1){
            item.quantity--
          }
        },

        removeFromCart(variantID:string){
          this.items = this.items.filter(
            item=>item.variant.id !== variantID
          );
        },

        clearCart():void{
          this.items = [];
        }
      },


      persist:true,
            },


);


