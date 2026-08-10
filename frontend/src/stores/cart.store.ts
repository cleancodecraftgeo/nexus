import type { Product } from "@/types/product";
import { defineStore } from "pinia";


interface CartItem<T> {
    product: T
    quantity: number
}


export const useCartStore = defineStore("cart",
  {


    state: ()=>({
      items: [] as CartItem<Product>[],

    }),

    getters:
      {
        cartCount: (state)=>
          state.items.reduce((total,item)=>total+item.quantity,0),

        cartTotal:(state)=>

          state.items.reduce((total, item)=>

             total + Number(item.product.price) * item.quantity,0
          )




      },

      actions:
      {
        addToCart(product: Product)
        {
          const existingItem = this.items.find(
            item => item.product.id === product.id
          );

    if (existingItem) {
      existingItem.quantity++;
    } else {
      this.items.push({ product, quantity: 1 });
    }

    console.log("Cart items:", this.items);      // ← bura
    console.log("Cart count:", this.items.length);
        },



        increaseQuantity(productID:string){
            const item = this.items.find(
              item=>item.product.id ===productID
            );

            if(item){
              item.quantity++;
            }
        },

        decreaseQuantity(productID:string){

          const item = this.items.find(
            item=>item.product.id===productID
          );
          if(item&&item.quantity>1){
            item.quantity--
          }
        },

        removeFromCart(productID:string){
          this.items = this.items.filter(
            item=>item.product.id !== productID
          );
        }
      },


      persist:true,
            },


);


