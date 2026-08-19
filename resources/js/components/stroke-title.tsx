import { createRoot, type Root } from "react-dom/client";
import StrokeText from "@/components/StrokeText";

const FONT_FAMILY = "'Bricolage Grotesque', system-ui, sans-serif";
const LIGHT_THEME = { strokeColor: "#2563eb", fillColor: "#1f2937" };
const DARK_THEME = { strokeColor: "#818cf8", fillColor: "#f1f5f9" };

const REPLAY_INTERVAL_MS = 5_000;

export function mountStrokeTitle(rootEl: HTMLElement): void {
  const title = rootEl.dataset.title || "Manajemen Koperasi";
  const root: Root = createRoot(rootEl);

  let replayKey = 0;
  let replayTimer: number | undefined;

  const render = () => {
    const isDark = document.documentElement.classList.contains("dark");
    const theme = isDark ? DARK_THEME : LIGHT_THEME;
    root.render(
      <StrokeText
        key={replayKey}
        text={title}
        fontSize={22}
        fontWeight={800}
        letterSpacing={-0.2}
        strokeWidth={1.2}
        drawDuration={0.9}
        fillDelay={0.12}
        stagger={0.04}
        trigger="mount"
        fillMode="wipe"
        {...theme}
        style={{
          width: "auto",
          display: "inline-block",
          verticalAlign: "middle",
          fontFamily: FONT_FAMILY,
        }}
      />
    );
  };

  const scheduleReplay = () => {
    window.clearTimeout(replayTimer);
    replayTimer = window.setTimeout(() => {
      replayKey += 1;
      render();
      scheduleReplay();
    }, REPLAY_INTERVAL_MS);
  };

  render();
  scheduleReplay();

  new MutationObserver(() => {
    render();
  }).observe(document.documentElement, { attributes: true, attributeFilter: ["class"] });
}
