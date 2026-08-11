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
