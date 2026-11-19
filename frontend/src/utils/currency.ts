
export type Currency = 'USD' | 'EUR' | 'GEL'

const symbols: Record<Currency, string> = {
    USD: '$',
    EUR: '€',
    GEL: '₾',
}

export function formatCurrency(
  amount: number,
  currency: Currency,
): string {
  const formattedAmount = new Intl.NumberFormat('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount)

      return `${symbols[currency]} ${formattedAmount}`
}
