import "@vitejs/plugin-react/preamble";
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { NotFoundGlitch } from "@/components/ui/be-ui-404-not-found";

const config = window.__ERROR_PAGE_CONFIG ?? {};

const container = document.getElementById("root");

if (container) {
  createRoot(container).render(
    <StrictMode>
      <NotFoundGlitch {...config} />
    </StrictMode>,
  );
}
