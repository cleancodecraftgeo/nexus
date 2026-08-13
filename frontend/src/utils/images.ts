export const storageUrl = (path: string): string => {
  return `${import.meta.env.VITE_API_URL}/storage/${path}`
}
