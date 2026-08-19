import "@vitejs/plugin-react/preamble";
import { mountAuroraLogin } from "@/components/aurora-login";

const rootEl = document.getElementById("aurora-bg");
if (rootEl) {
  mountAuroraLogin(rootEl);
}
