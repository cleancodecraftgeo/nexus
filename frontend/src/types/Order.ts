export interface Order {
  id:string
  user_id:number
  total:string
  status:string
  items:OrderItem[]
  thumbnail: string | null
  created_at:string
}

interface OrderItem {
  id:string,
  quantity:number,
  price:string
  product:Product,
  variant:ProductVariant
}

interface Product {
  id:string,
  name:string,
  slug:string,
  thumbnail:string|null
}



interface Attribute {
  id:string,
  name:string,
  slug:string
}
interface AttributeValue {
  id:string,
  value:string,
  attribute:Attribute
}

interface ProductVariant {
  id:string,
  sku:string,
  stock:number,
  price:string,
  is_active:number,
  is_default:number,
  attribute_values:AttributeValue[]

}
