import { useSnackbar } from "vue3-snackbar";

export function useNotify() {
  const snackbar = useSnackbar();

  return {
    success: (text: string) => snackbar.add({ type: "success", text }),
    error: (text: string) => snackbar.add({ type: "error", text }),
    warning: (text: string) => snackbar.add({ type: "warning", text }),
    info: (text: string) => snackbar.add({ type: "info", text }),
  };
}
