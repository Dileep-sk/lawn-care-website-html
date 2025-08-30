<script setup>
import { ref, onMounted, watch, onBeforeUnmount, nextTick } from 'vue'
import Chart from 'chart.js/auto'
import { useDashboard } from '@/composables/useDashboard'

const props = defineProps({
    defaultRange: {
        type: String,
        default: 'D'
    }
})

const selectedRange = ref(props.defaultRange)
const chartInstance = ref(null)
const canvasRef = ref(null)

const statusLabels = {
    '0': 'Pending',
    '1': 'In Progress',
    '2': 'Ready',
    '3': 'Done',
    '4': 'Cancelled'
}

const statusColors = {
    '0': '#f39c12',
    '1': '#2980b9',
    '2': '#27ae60',
    '3': '#8e44ad',
    '4': '#e74c3c'
}

const chartLabels = ref([])
const chartDatasets = ref([])

const { error, isLoading, fetchSalesTrends } = useDashboard()

function prepareChartData(data) {
    // Sort labels to ensure chronological order
    // Handles both date strings (YYYY-MM-DD) and week strings (e.g., 2025-W35)
    const labels = Object.keys(data).sort((a, b) => {
        // Week format
        if (a.includes('W') && b.includes('W')) {
            const [aYear, aWeek] = a.split('-W').map(Number)
            const [bYear, bWeek] = b.split('-W').map(Number)
            return aYear === bYear ? aWeek - bWeek : aYear - bYear
        }
        // Date format
        return a.localeCompare(b)
    })

    const statusData = {
        '0': [],
        '1': [],
        '2': [],
        '3': [],
        '4': []
    }

    labels.forEach(label => {
        const item = data[label] || {}
        Object.keys(statusData).forEach(status => {
            statusData[status].push(item[status] || 0)
        })
    })

    const datasets = Object.keys(statusData).map(status => ({
        label: statusLabels[status],
        backgroundColor: statusColors[status],
        data: statusData[status]
    }))

    return { labels, datasets }
}

async function loadSalesTrendData(range) {
    const typeMap = {
        D: 'day',
        W: 'week',
        M: 'month',
        Y: 'year'
    }
    const type = typeMap[range] || 'day'

    try {
        const salesDataRaw = await fetchSalesTrends({ type })
        console.log('Raw sales data:', salesDataRaw)

        const salesData = salesDataRaw?.data || salesDataRaw || {}

        if (salesData && Object.keys(salesData).length) {
            const { labels, datasets } = prepareChartData(salesData)
            if (!labels.length || !datasets.length) {
                console.warn('Prepared chart data is empty')
                return
            }
            chartLabels.value = labels
            chartDatasets.value = datasets
            await drawChart()
        } else {
            console.warn('No sales trend data found')
        }
    } catch (err) {
        console.error('Failed to fetch sales trends:', err)
    }
}

async function drawChart() {
    await nextTick()
    const canvas = canvasRef.value
    if (!canvas || !canvas.isConnected) {
        console.warn('Canvas not ready or detached. Skipping draw.')
        return
    }
    const ctx = canvas.getContext('2d')
    if (!ctx) {
        console.warn('Context not found. Skipping draw.')
        return
    }

    if (chartInstance.value) {
        try {
            chartInstance.value.destroy()
        } catch (e) {
            console.error('Chart destroy error:', e)
        }
        chartInstance.value = null
    }

    try {
        chartInstance.value = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels.value,
                datasets: chartDatasets.value
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 0 },
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: {
                        stacked: false,
                        grid: { display: false },
                        offset: true,
                        barPercentage: 0.6,
                        categoryPercentage: 0.7,
                        maxBarThickness: 30
                    },
                    y: {
                        beginAtZero: true,
                        grid: { display: true }
                    }
                }
            }
        })
    } catch (e) {
        console.error('Chart init error:', e)
    }
}

watch(selectedRange, async (newRange) => {
    await loadSalesTrendData(newRange)
})

onMounted(async () => {
    await loadSalesTrendData(selectedRange.value)
})

onBeforeUnmount(() => {
    if (chartInstance.value) {
        try {
            chartInstance.value.destroy()
        } catch (e) {
            console.error('Chart destroy error on unmount:', e)
        }
        chartInstance.value = null
    }
})

function setRange(range) {
    selectedRange.value = range
}
</script>

<template>
    <div class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] rounded-[10px] p-[15px]">
        <div class="flex justify-between items-center">
            <h1 class="text-[18px] font-[600] text-[#000]">Sales Trends</h1>
            <div class="flex gap-[10px]">
                <div v-for="range in ['D', 'W', 'M', 'Y']" :key="range" @click="setRange(range)"
                    class="w-[30px] h-[26px] cursor-pointer rounded-[5px] flex justify-center items-center text-[12px]"
                    :class="[
                        selectedRange === range ? 'bg-[#353B48] text-white' : 'border border-[#353B48] text-[#000]'
                    ]">
                    {{ range }}
                </div>
            </div>
        </div>
        <div class="w-full h-[250px] mt-[10px]">
            <canvas ref="canvasRef"></canvas>
        </div>
    </div>
</template>
