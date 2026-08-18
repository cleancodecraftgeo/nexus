// types/product.ts
export interface Product {
  id: string
  name: string;
  price: string;
  slug:string;
  thumbnail:string;
  brand:string | null;
  description: string|null;
  attributes?:Attribute[]|null;
}

export interface PaginationLink {
  url: string | null
  label: string
  page: number | null
  active: boolean
}

export interface PaginationMeta {
  current_page: number
  from: number
  last_page: number
  links: PaginationLink[]
  path: string
  per_page: number
  to: number
  total: number
}

export interface ProductResponse {
  data: Product[]
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
  meta: PaginationMeta
}

export interface AttributeValue {
  id:string
  value: string
}

export interface Attribute{
   id:string
  name:string;
  values:AttributeValue[]
}
