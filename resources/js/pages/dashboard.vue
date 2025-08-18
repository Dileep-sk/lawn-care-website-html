<script setup>
import AuthLayout from '../layouts/AuthLayout.vue'
import calender from '@/assets/icons/calender.svg'

</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px]  border-b-[#E8F0E2] border border-transparent flex justify-between items-center">

                    <h1 class="text-[20px] text-[#000] font-[700]">Dashboard</h1>

                    <div class="flex gap-[15px] items-center">
                        <!-- <div class="search_box relative">
                                    <img src="../../../assets/icons/search.svg" class="absolute w-[20px]" alt="" srcset="">
                                    <input type="text" placeholder="Search here.."
                                        class=" input !border-0 !w-[325px] rounded-[5px] !px-[40px] !h-[45px] bg-[rgba(23,23,23,0.05)] ">
                                </div> -->

                        <div class="relative">
                            <img :src="calender" class="absolute top-[17px] left-[10px]" alt="">
                            <input
                                class="form-control input !border-0 !w-[300px] rounded-[5px] !px-[55px] !h-[45px] !bg-[rgba(23,23,23,0.05)]"
                                placeholder="Pick date range" id="kt_daterangepicker_1" />
                        </div>

                    </div>

                </div>

                <div class="grid grid-cols-2 gap-[20px] p-[20px]">
                    <div
                        class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] h-[calc(50vh_-_132px)] rounded-[10px] p-[15px] ">
                        <div class="grid grid-cols-3 gap-[15px]">
                            <div class="div h-[calc(25vh_-90px)] bg-[#E6FAF1] rounded-[10px] p-[10px] ">
                                <h6 class="text-[15px] font-[400] text-[#000]">Today's Orders</h6>
                                <h1 class="text-[50px] text-[#05C46B] font-[800] text-center mt-[15px]">50</h1>
                            </div>
                            <div class="div h-[calc(25vh_-90px)] bg-[rgba(255,185,0,0.1)] rounded-[10px] p-[10px]">
                                <h6 class="text-[15px] font-[400] text-[#000]">Pending Shipments</h6>
                                <h1 class="text-[50px] text-[#FFB900] font-[800] text-center mt-[15px]">1</h1>

                            </div>
                            <div class="div h-[calc(25vh_-90px)] bg-[#575FCF1A] rounded-[10px] p-[10px]">
                                <h6 class="text-[15px] font-[400] text-[#000]">Total Sales</h6>
                                <h1 class="text-[50px] text-[#575FCF] font-[800] text-center mt-[15px]">50</h1>

                            </div>
                            <div class="div h-[calc(25vh_-90px)] bg-[#FEEFF2] rounded-[10px] p-[10px]">
                                <h6 class="text-[15px] font-[400] text-[#000]"> Out of Stock</h6>
                                <h1 class="text-[50px] text-[#EF5777] font-[800] text-center mt-[15px]">50</h1>
                            </div>
                            <div class="div h-[calc(25vh_-90px)] bg-[rgba(43,203,186,0.1)] rounded-[10px] p-[10px]">
                                <h6 class="text-[15px] font-[400] text-[#000]"> Total Stock </h6>
                                <h1 class="text-[50px] text-[#2bcbba] font-[800] text-center mt-[15px]">33</h1>
                            </div>
                            <div class="div h-[calc(25vh_-90px)] bg-[rgba(165,94,234,0.1)] rounded-[10px] p-[10px]">
                                <h6 class="text-[15px] font-[400] text-[#000]">Least Design Sold </h6>
                                <h1 class="text-[50px] text-[#a55eea] font-[800] text-center mt-[15px]">20</h1>
                            </div>
                        </div>
                    </div>

                    <div
                        class="box shadow-[0px_8px_24px_rgba(149,157,165,0.2)] h-[calc(50vh_-_132px)] rounded-[10px] p-[15px] ">
                        <h1 class="text-[18px] font-[600] text-[#EB2F06]">Out of Stock</h1>
                        <div class="table_container pr-[10px] h-[calc(90%_-_20px)] overflow-y-auto mt-[15px]">
                            <table>
                                <thead class="!bg-[#eb2f060f]">
                                    <tr>
                                        <th class="rounded-tl-[5px]">Mark No</th>
                                        <th>Design No</th>
                                        <th>Item</th>
                                        <th class="rounded-tr-[5px]">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="!h-[40px]">
                                        <td>12</td>
                                        <td>D1002</td>
                                        <td>Cotton Shirt</td>
                                        <td>300</td>
                                    </tr>
                                    <tr class="!h-[40px]">
                                        <td>12</td>
                                        <td>D1002</td>
                                        <td>Cotton Shirt</td>
                                        <td>300</td>
                                    </tr>
                                    <tr class="!h-[40px]">
                                        <td>12</td>
                                        <td>D1002</td>
                                        <td>Cotton Shirt</td>
                                        <td>300</td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>

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
