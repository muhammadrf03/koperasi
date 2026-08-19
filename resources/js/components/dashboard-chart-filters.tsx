"use client"

import * as React from "react"

import {
  Combobox,
  ComboboxContent,
  ComboboxInput,
  ComboboxItem,
  ComboboxList,
} from "@/components/ui/combobox"

const MONTHS = [
  "Semua Bulan (Tahunan)",
  "Januari",
  "Februari",
  "Maret",
  "April",
  "Mei",
  "Juni",
  "Juli",
  "Agustus",
  "September",
  "Oktober",
  "November",
  "Desember",
]

const MONTH_LABELS_SHORT = [
  "Jan",
  "Feb",
  "Mar",
  "Apr",
  "Mei",
  "Jun",
  "Jul",
  "Agu",
  "Sep",
  "Okt",
  "Nov",
  "Des",
]

export type YearChartData = {
  in: number[]
  out: number[]
}

interface DashboardChartFiltersProps {
  chartData: Record<string, YearChartData>
  currentYear: number
}

export function DashboardChartFilters({
  chartData,
  currentYear,
}: DashboardChartFiltersProps) {
  const yearItems = React.useMemo(
    () => [currentYear, currentYear - 1, currentYear - 2].map(String),
    [currentYear]
  )

  const [year, setYear] = React.useState(String(currentYear))
  const [month, setMonth] = React.useState(MONTHS[0])

  const emitFilter = React.useCallback(
    (selectedYear: string, selectedMonth: string) => {
      const data = chartData[selectedYear]
      if (!data) return

      const monthIndex = MONTHS.indexOf(selectedMonth)
      const isAll = monthIndex <= 0

      window.dispatchEvent(
        new CustomEvent("dashboard:filter", {
          detail: {
            year: selectedYear,
            month: monthIndex,
            labels: isAll
              ? MONTH_LABELS_SHORT
              : [MONTH_LABELS_SHORT[monthIndex - 1]],
            inData: isAll ? data.in : [data.in[monthIndex - 1]],
            outData: isAll ? data.out : [data.out[monthIndex - 1]],
          },
        })
      )
    },
    [chartData]
  )

  React.useEffect(() => {
    emitFilter(year, month)
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [])

  return (
    <div className="flex items-center gap-2">
      <Combobox
        items={MONTHS}
        value={month}
        onValueChange={(value) => {
          const next = typeof value === "string" ? value : MONTHS[0]
          setMonth(next)
          emitFilter(year, next)
        }}
      >
        <ComboboxInput
          placeholder="Pilih bulan..."
          className="w-44"
          showTrigger
          readOnly
        />
        <ComboboxContent>
          <ComboboxList>
            {MONTHS.map((item) => (
              <ComboboxItem key={item} value={item}>
                {item}
              </ComboboxItem>
            ))}
          </ComboboxList>
        </ComboboxContent>
      </Combobox>

      <Combobox
        items={yearItems}
        value={year}
        onValueChange={(value) => {
          const next =
            typeof value === "string" && yearItems.includes(value)
              ? value
              : String(currentYear)
          setYear(next)
          emitFilter(next, month)
        }}
      >
        <ComboboxInput
          placeholder="Pilih tahun..."
          className="w-24"
          showTrigger
          readOnly
        />
        <ComboboxContent>
          <ComboboxList>
            {yearItems.map((item) => (
              <ComboboxItem key={item} value={item}>
                {item}
              </ComboboxItem>
            ))}
          </ComboboxList>
        </ComboboxContent>
      </Combobox>
    </div>
  )
}
