<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick, watch } from 'vue'
import Chart from 'chart.js/auto'
import { useDashboard } from '@/composables/useDashboard'

const chartInstance = ref(null)
const canvasRef = ref(null)
const { topDesigns, fetchTopDesigns } = useDashboard()

const chartData = ref({
    labels: [],
    datasets: [{
        data: [],
        backgroundColor: ['#f1c40f', '#5D6DFF', '#2ecc71', '#FF5B91', '#E74C3C']
    }]
})

const drawChart = async () => {
    await nextTick()

    const canvas = canvasRef.value
    if (!canvas || !canvas.isConnected) return

    const ctx = canvas.getContext('2d')
    if (!ctx) return

    // Destroy previous instance
    if (chartInstance.value) {
        try {
            chartInstance.value.destroy()
        } catch (e) {
            console.error('Chart destroy error:', e)
        }
    }

    chartInstance.value = new Chart(ctx, {
        type: 'pie',
        data: chartData.value,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
                }
            }
        }
    })
}

// Watch for data change and update chart
watch(topDesigns, (designs) => {
    if (!designs.length) return

    chartData.value.labels = designs.map(d => d.design_no_name)
    chartData.value.datasets[0].data = designs.map(d => d.total_quantity)

    drawChart()
})

onMounted(async () => {
    await fetchTopDesigns()
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
</script>

<template>
    <div class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] rounded-[10px] p-[15px]">
        <h1 class="text-[18px] font-[600] text-[#000] mb-4">Top Designs</h1>
        <div class="chart-container !w-[280px] !h-[280px] mx-auto">
            <canvas ref="canvasRef" class="w-[250px] h-[250px]"></canvas>
        </div>
    </div>
</template>

<style scoped>
canvas {
    max-width: 100%;
    height: auto;
}
</style>
