<script setup>
import { ref, onMounted } from 'vue'
import AuthLayout from '../layouts/AuthLayout.vue'
import calender from '@/assets/icons/calender.svg'
import OutOfStockTable from "../components/OutOfStockTable.vue"
import DashboardDetails from "../components/DashboardDetails.vue"
import { initCalendar } from "@/utils/calendar"
import OrderGraph from '@/components/OrderGraph.vue'
import TopDesignsChart from '@/components/TopDesignsChart.vue'

const selectedStartDate = ref(null)
const selectedEndDate = ref(null)

function handleDateChange(start, end) {
    selectedStartDate.value = start.format('YYYY-MM-DD')
    selectedEndDate.value = end.format('YYYY-MM-DD')
}

onMounted(() => {
    initCalendar(handleDateChange)
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px]  border-b-[#E8F0E2] border border-transparent flex justify-between items-center">

                    <h1 class="text-[20px] text-[#000] font-[700]">Dashboard</h1>

                    <div class="flex gap-[15px] items-center">
                        <div class="relative">
                            <img :src="calender" class="absolute top-[17px] left-[10px]" alt="">
                            <input
                                class="form-control input !border-0 !w-[300px] rounded-[5px] !px-[55px] !h-[45px] !bg-[rgba(23,23,23,0.05)]"
                                placeholder="Pick date range" id="kt_daterangepicker_1" />
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-[20px] p-[20px]">

                    <DashboardDetails :start-date="selectedStartDate" :end-date="selectedEndDate" />
                    <OutOfStockTable :start-date="selectedStartDate" :end-date="selectedEndDate" />

                    <!-- <OutOfStockTable /> -->
                    <OrderGraph defaultRange="D" />

                    <TopDesignsChart />

                </div>
            </div>
        </div>
    </AuthLayout>
</template>
