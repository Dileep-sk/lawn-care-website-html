<script setup>
import { ref, onMounted } from 'vue'
import AuthLayout from '../layouts/AuthLayout.vue'
import calender from '@/assets/icons/calender.svg'
import OutOfStockTable from "../components/OutOfStockTable.vue"
import DashboardDetails from "../components/DashboardDetails.vue"
import { initCalendar } from "@/utils/calendar"

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
                    <div
                        class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] h-[calc(50vh_-_132px)] rounded-[10px] p-[15px] ">


                        <div class="flex justify-between items-center ">

                            <h1 class="text-[18px] font-[600] text-[#000]">Sales Trends</h1>
                            <div class="flex gap-[10px]">

                                <div
                                    class="w-[30px] h-[26px] cursor-pointer bg-[#353B48] text-[#fff] rounded-[5PX] flex justify-center items-center text-[12px] ">
                                    D</div>
                                <div
                                    class="w-[30px] h-[26px] cursor-pointer border-1 border-[#353B48] text-[#000] rounded-[5PX] flex justify-center items-center text-[12px] ">
                                    W</div>
                                <div
                                    class="w-[30px] h-[26px] cursor-pointer border-1 border-[#353B48] text-[#000] rounded-[5PX] flex justify-center items-center text-[12px] ">
                                    M</div>
                                <div
                                    class="w-[30px] h-[26px] cursor-pointer border-1 border-[#353B48] text-[#000] rounded-[5PX] flex justify-center items-center text-[12px] ">
                                    Y</div>
                                <div
                                    class="w-[30px] h-[26px] cursor-pointer border-1 border-[#353B48] text-[#000] rounded-[5PX] flex justify-center items-center text-[12px] ">
                                    All</div>
                            </div>
                        </div>
                        <div class="w-full !h-[90%] mt-[10px] ">
                            <canvas id="salesChart"></canvas>
                        </div>


                        <!-- <img src="../../../assets/images/GroupLine.png" class="w-[100%] h-[90%]" alt=""> -->
                    </div>

                    <div
                        class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] h-[calc(50vh_-_132px)] rounded-[10px] p-[15px] ">
                        <h1 class="text-[18px] font-[600] text-[#000]">Top Designs</h1>
                        <div class="chart-container !w-[280px] !h-[280px] mx-auto">
                            <canvas id="pieChart" class="!h-[100%] mx-auto"></canvas>
                        </div>

                    </div>


                </div>

            </div>
        </div>
    </AuthLayout>
</template>
