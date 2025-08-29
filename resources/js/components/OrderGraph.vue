<script setup>
import { ref, onMounted, watch, onBeforeUnmount, nextTick } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    defaultRange: {
        type: String,
        default: 'D'
    }
})

const selectedRange = ref(props.defaultRange)
const chartInstance = ref(null)
const canvasRef = ref(null)

// Simulated data based on range (can be customized)
const getDummyGroupedBarData = (range) => {
    let labels = []
    let datasets = []

    switch (range) {
        case 'D': // Daily (7 days)
            labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            datasets = [
                {
                    label: 'Product A',
                    backgroundColor: '#2ecc71',
                    data: [10, 130, 20, 60, 50, 20, 40]
                },
                {
                    label: 'Product B',
                    backgroundColor: '#e74c3c',
                    data: [150, 10, 40, 50, 30, 70, 20]
                },
                {
                    label: 'Product C',
                    backgroundColor: '#3498db',
                    data: [30, 90, 80, 20, 110, 90, 90]
                },
                {
                    label: 'Product D',
                    backgroundColor: '#f1c40f',
                    data: [60, 20, 70, 130, 10, 30, 60]
                }
            ]
            break

        case 'W': // Weekly (4 weeks)
            labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4']
            datasets = [
                {
                    label: 'Product A',
                    backgroundColor: '#2ecc71',
                    data: [400, 420, 450, 460]
                },
                {
                    label: 'Product B',
                    backgroundColor: '#e74c3c',
                    data: [380, 390, 420, 430]
                },
                {
                    label: 'Product C',
                    backgroundColor: '#3498db',
                    data: [300, 310, 330, 350]
                },
                {
                    label: 'Product D',
                    backgroundColor: '#f1c40f',
                    data: [200, 220, 250, 270]
                }
            ]
            break

        case 'M': // Monthly (12 months)
            labels = [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ]
            datasets = [
                {
                    label: 'Product A',
                    backgroundColor: '#2ecc71',
                    data: [300, 320, 310, 290, 330, 340, 360, 380, 400, 420, 440, 460]
                },
                {
                    label: 'Product B',
                    backgroundColor: '#e74c3c',
                    data: [280, 300, 290, 310, 300, 320, 330, 350, 360, 370, 390, 410]
                },
                {
                    label: 'Product C',
                    backgroundColor: '#3498db',
                    data: [260, 280, 270, 290, 310, 330, 350, 370, 390, 410, 430, 450]
                },
                {
                    label: 'Product D',
                    backgroundColor: '#f1c40f',
                    data: [240, 260, 250, 270, 290, 310, 330, 350, 370, 390, 410, 430]
                }
            ]
            break

        case 'Y': // Yearly (5 years)
            labels = ['2020', '2021', '2022', '2023', '2024']
            datasets = [
                {
                    label: 'Product A',
                    backgroundColor: '#2ecc71',
                    data: [1200, 1300, 1400, 1500, 1600]
                },
                {
                    label: 'Product B',
                    backgroundColor: '#e74c3c',
                    data: [1000, 1100, 1200, 1300, 1400]
                },
                {
                    label: 'Product C',
                    backgroundColor: '#3498db',
                    data: [800, 900, 1000, 1100, 1200]
                },
                {
                    label: 'Product D',
                    backgroundColor: '#f1c40f',
                    data: [600, 700, 800, 900, 1000]
                }
            ]
            break

        default:
            console.warn('Invalid range:', range)
    }

    return { labels, datasets }
}

// Draw chart safely
const drawChart = async (range) => {
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

    // Safely destroy existing chart
    if (chartInstance.value) {
        try {
            chartInstance.value.destroy()
        } catch (e) {
            console.error('Chart destroy error:', e)
        }
        chartInstance.value = null
    }

    const { labels, datasets } = getDummyGroupedBarData(range)

    try {
        chartInstance.value = new Chart(ctx, {
            type: 'bar',
            data: {
                labels,
                datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0 // Disable animation to prevent mid-animation state errors
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    x: {
                        stacked: false,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true
                        }
                    }
                }
            }
        })
    } catch (e) {
        console.error('Chart init error:', e)
    }
}

watch(selectedRange, async (newRange) => {
    await drawChart(newRange)
})

onMounted(async () => {
    await drawChart(selectedRange.value)
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

const setRange = (range) => {
    selectedRange.value = range
}
</script>

<template>
    <div class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)]  rounded-[10px] p-[15px]">
        <div class="flex justify-between items-center">
            <h1 class="text-[18px] font-[600] text-[#000]">Sales Trends</h1>
            <div class="flex gap-[10px]">
                <div v-for="range in ['D', 'W', 'M', 'Y']" :key="range" @click="setRange(range)"
                    class="w-[30px] h-[26px] cursor-pointer rounded-[5px] flex justify-center items-center text-[12px]"
                    :class="[
                        selectedRange === range
                            ? 'bg-[#353B48] text-white'
                            : 'border border-[#353B48] text-[#000]'
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

<style scoped>
canvas {
    width: 100% !important;
    height: 100% !important;
}
</style>
