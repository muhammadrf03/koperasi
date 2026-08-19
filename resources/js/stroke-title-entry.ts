import "@vitejs/plugin-react/preamble";
import { mountStrokeTitle } from "@/components/stroke-title";

const rootEl = document.getElementById("stroke-title-root");
if (rootEl) {
  mountStrokeTitle(rootEl);
}
