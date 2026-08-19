import "@vitejs/plugin-react/preamble";
import { createRoot, type Root } from "react-dom/client";
import {
  DashboardChartFilters,
  type YearChartData,
} from "@/components/dashboard-chart-filters";

const rootEl = document.getElementById("dashboard-filters-root");

if (rootEl) {
  const chartData = JSON.parse(
    rootEl.dataset.chartData || "{}"
  ) as Record<string, YearChartData>;
  const currentYear = Number(
    rootEl.dataset.currentYear || new Date().getFullYear()
  );

  const root: Root = createRoot(rootEl);
  root.render(
    <DashboardChartFilters
      chartData={chartData}
      currentYear={currentYear}
    />
  );
}
