export interface Order {
  id:string;
  user_id:number;
  first_name: string;
  last_name: string;
  phone: string;
  email: string;
  city: string;
  address: string;
  postal_code: string | null;
  notes: string | null;
  total: string
  status: string

  currency_code: 'USD' | 'GEL' | 'EUR';
  exchange_rate: string;

  items: OrderItem[]
  created_at: string
  updated_at: string
}

interface OrderItem {
  id: string;
  product_id: string;
  product_variant_id: string | null;
  quantity: number;
  price: string;
  product: Product;
  variant: ProductVariant | null;
}

interface Product {
  id: string;
  name: string;
  slug: string;
  thumbnail: string|null;
}



interface Attribute {
  id: string;
  name: string;
  slug: string;
  thumbnail: string | null;
}
interface AttributeValue {
  id: string;
  value: string;
  attribute: Attribute
}

interface ProductVariant {
  id: string;
  sku: string;
  stock: number;
  price: string;
  is_active: number;
  is_default: number;
  attribute_values: AttributeValue[]

}
