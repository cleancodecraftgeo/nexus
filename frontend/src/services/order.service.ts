import { api } from "./api";

interface OrderItemPayload
{
  product_id:string;
  quantity:number;
}

interface CreateOrderPayload
{
  items: OrderItemPayload[];
}

export const createOrder = async (data: CreateOrderPayload)=>
{
  const response = await api.post('/orders',data);

  return response.data;
}

export const orderService = {
  myOrders(){
    return api.get('/my-orders')
  },
  getOrder(id:string){
    return api.get(`/orders/${id}`)
  },
}
