<script setup>
import { watch } from 'vue'
import DashboardCard from '@/components/DashboardCard.vue'
import { useDashboard } from '@/composables/useDashboard'

const props = defineProps({
    startDate: String,
    endDate: String,
    perPage: {
        type: Number,
        default: 10
    },
    page: {
        type: Number,
        default: 1
    }
})

const { fetchDashboardDetails, dashboardData, isLoading, error } = useDashboard()

watch(
    () => [props.startDate, props.endDate, props.perPage, props.page],
    ([newStart, newEnd]) => {
        if (newStart && newEnd) {
            fetchDashboardDetails({
                start_date: newStart,
                end_date: newEnd,
            })
        }
    },
    { immediate: true }
)
</script>
<template>
    <div class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] h-[calc(50vh_-_132px)] rounded-[10px] p-[15px]">
        <div class="grid grid-cols-3 gap-[15px]">
            <DashboardCard title="Today's Orders" :value="dashboardData.todays_orders" bgColor="bg-[#E6FAF1]"
            textColor="text-[#05C46B]" />
            <DashboardCard title="Pending Shipments" :value="dashboardData.pending_shipments"
                bgColor="bg-[rgba(255,185,0,0.1)]" textColor="text-[#FFB900]" />
            <DashboardCard title="Total Sales" :value="dashboardData.total_sales" bgColor="bg-[#575FCF1A]"
                textColor="text-[#575FCF]" />
            <DashboardCard title="Out of Stock" :value="dashboardData.out_of_stock" bgColor="bg-[#FEEFF2]"
                textColor="text-[#EF5777]" />
            <DashboardCard title="Total Stock" :value="dashboardData.available_stock"
                bgColor="bg-[rgba(43,203,186,0.1)]" textColor="text-[#2bcbba]" />
            <DashboardCard title="Least Design Sold" :value="dashboardData.least_design_sold"
                bgColor="bg-[rgba(165,94,234,0.1)]" textColor="text-[#a55eea]" />
        </div>
    </div>
</template>
