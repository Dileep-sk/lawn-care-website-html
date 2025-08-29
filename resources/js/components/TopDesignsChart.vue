<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'
import Chart from 'chart.js/auto'

const chartInstance = ref(null)
const canvasRef = ref(null)

const chartData = {
    labels: ['D1002', 'D1004', 'D1005', 'D1006', 'D1003'],
    datasets: [
        {
            data: [20, 25, 30, 15, 10],
            backgroundColor: ['#f1c40f', '#5D6DFF', '#2ecc71', '#FF5B91', '#E74C3C']
        }
    ]
}

const drawChart = async () => {
    await nextTick()

    const canvas = canvasRef.value
    if (!canvas || !canvas.isConnected) return

    const ctx = canvas.getContext('2d')
    if (!ctx) return

    if (chartInstance.value) {
        try {
            chartInstance.value.destroy()
        } catch (e) {
            console.error('Chart destroy error:', e)
        }
    }

    chartInstance.value = new Chart(ctx, {
        type: 'pie',
        data: chartData,
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

onMounted(drawChart)

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
    <div class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)]  rounded-[10px] p-[15px]">
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
