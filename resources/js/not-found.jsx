import "@vitejs/plugin-react/preamble";
import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import NotFoundGlitchPreview from "@/components/ui/demo";

const container = document.getElementById("root");

if (container) {
  createRoot(container).render(
    <StrictMode>
      <NotFoundGlitchPreview />
    </StrictMode>,
  );
}
