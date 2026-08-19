import { createRoot, type Root } from "react-dom/client";
import Plasma from "@/components/Plasma";

export function mountAuroraLogin(rootEl: HTMLElement): void {
  const root: Root = createRoot(rootEl);
  root.render(
    <Plasma
      color="#38bdf8"
      speed={1.2}
      scale={0.9}
      opacity={0.9}
    />
  );
}
