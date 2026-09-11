


import type { Product, ProductVariant } from "../types/Product";
import { defineStore } from "pinia";


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


