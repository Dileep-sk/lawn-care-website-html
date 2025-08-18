<script setup>
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px]  border-b-[#E8F0E2] border border-transparent flex justify-between items-center">

                    <h1 class="text-[20px] text-[#000] font-[700]"> Add Stock</h1>


                    <router-link :to="{ name: 'stock' }"
                        class=" cursor-pointer h-[45px] flex px-[20px] items-center gap-[10px] text-[#fff] text-[15px] font-[500] rounded-[5px] bg-[#000]   ">
                        <img :src="left_arrow" class="w-[20px]" alt="">
                        Back </router-link>


                </div>

                <div class="form_box p-[15px]">
                    <form action="" class="p-[15px] bg-[rgba(56,92,76,0.04)] ">
                        <div class="grid grid-cols-3 gap-[30px]">

                            <div class="input_box">
                                <label for="">Mark No</label>
                                <input type="text" class="input" placeholder="M001" name="" id="">
                            </div>

                            <div class="input_box">
                                <label for="">Design No</label>
                                <input type="text" class="input" placeholder="D1002" name="" id="">
                            </div>
                            <div class="input_box">
                                <label for="">Item Name</label>
                                <input type="text" class="input" placeholder="Cotton Shirt" name="" id="">
                            </div>


                            <div class="input_box">
                                <label for="">Quantity</label>
                                <input type="text" class="input" placeholder="00.00" name="" id="">
                            </div>

                        </div>

                        <div class="flex justify-end">
                            <button type="button"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center"> Add Stock
                                <img :src="right_arrow_white" alt="">
                            </button>
                        </div>
                    </form>
                </div>



            </div>
        </div>

    </AuthLayout>
</template>
