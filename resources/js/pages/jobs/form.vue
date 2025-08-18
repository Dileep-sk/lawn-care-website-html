<script setup>
import { useRoute } from 'vue-router'
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'
import close_white from '@/assets/icons/close_white.svg'
import cloth from '@/assets/images/cloth.png'

const route = useRoute()
const routeName = route.name;

// console.log(route.path);
console.log(routeName);

</script>
<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px]  border-b-[#E8F0E2] border border-transparent flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]"> Create Job</h1>

                    <router-link :to="{ name: 'jobs' }"
                        class=" cursor-pointer h-[45px] flex px-[20px] items-center gap-[10px] text-[#fff] text-[15px] font-[500] rounded-[5px] bg-[#000]   ">
                        <img :src="left_arrow" class="w-[20px]" alt="">
                        Back </router-link>
                </div>
                <div class="form_box p-[15px]">
                    <form action="" class="p-[15px] bg-[rgba(56,92,76,0.04)] ">
                        <div class="grid grid-cols-3 gap-[30px]">
                            <div class="input_box">
                                <label for="">Customer Name</label>
                                <select name="" class="input" id="">
                                    <option value="">Select Customer Name</option>
                                    <option value="">ABC Textiles</option>
                                </select>
                            </div>
                            <div class="input_box">
                                <label for="">Design No</label>
                                <input type="text" class="input" value="D1002" placeholder="D1002" name="" id="">
                            </div>


                            <div class="input_box">
                                <label for="">image</label>
                                <input type="file" multiple class="input" placeholder="DD-MM-YYYY" name="" id="">
                            </div>
                            <div class="input_box">
                                <label for="">Quantity</label>
                                <input type="text" class="input" placeholder="00.00" name="" id="">
                            </div>

                            <div class="input_box">
                                <label for="">Order No</label>
                                <input type="text" class="input " placeholder="ORD1234 " name="" id="">
                            </div>

                            <div class="input_box">
                                <label for="">Status</label>
                                <select name="" class="input" id="">
                                    <option value="">Select Status</option>
                                    <option value="">Pending</option>

                                </select>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                            <div class="input_box">
                                <label for="">Matching</label>
                                <div class="flex gap-[25px]">
                                    <select name="" class="input" id="">
                                        <option value="">Select </option>
                                        <option value="">red</option>

                                    </select>
                                    <input type="text" class="input">
                                </div>
                            </div>
                        </div>

                        <!-- Show these only when route is 'jobs-edit' -->
                        <div v-if="routeName === 'jobs-edit'">
                            <div class="flex gap-[15px] flex-wrap mt-[20px]">
                                <div class="relative w-[max-content]">
                                    <button
                                        class="  bg-[#EB2F06] absolute right-[5px] top-[5px] absolute w-[20px] h-[20px] flex rounded-[5px] justify-center items-center">
                                        <img :src="close_white" class="w-[10px] block" alt=""></button>
                                    <img :src="cloth" class="w-[120px] rounded-[5px]  block " alt="">
                                </div>
                                <div class="relative w-[max-content]">
                                    <button
                                        class="bg-[#EB2F06] right-[5px] top-[5px] absolute w-[20px] h-[20px] flex rounded-[5px] justify-center items-center">
                                        <img :src="close_white" class="w-[10px] block" alt=""></button>
                                    <img :src="cloth" class="w-[120px] rounded-[5px]  block " alt="">
                                </div>
                                <div class="relative w-[max-content]">
                                    <button
                                        class="bg-[#EB2F06] right-[5px] top-[5px] absolute w-[20px] h-[20px] flex rounded-[5px] justify-center items-center">
                                        <img :src="close_white" class="w-[10px] block" alt=""></button>
                                    <img :src="cloth" class="w-[120px] rounded-[5px]  block " alt="">
                                </div>
                            </div>

                            <div class="flex gap-[10px] justify-end mt-[15px]">
                                <button type="button"
                                    class="flex create justify-center !bg-[#5352ed] gap-[10px] cursor-pointer items-center">
                                    Move to Order
                                    <img :src="right_arrow_white" alt="">
                                </button>
                                <button type="button"
                                    class="flex create !bg-[#5352ed] justify-center gap-[10px] cursor-pointer items-center">
                                    Move to Order
                                    <img :src="right_arrow_white" alt="">
                                </button>
                                <button type="button"
                                    class="flex cancel justify-center gap-[10px] cursor-pointer items-center">
                                    Cancel
                                </button>
                                <button type="button"
                                    class="flex create justify-center gap-[10px] cursor-pointer items-center"> Edit
                                    Job
                                </button>
                            </div>
                        </div>

                        <div v-if="routeName !== 'jobs-edit'">
                            <div class="flex justify-end">
                                <button type="button"
                                    class="flex create justify-center gap-[10px] cursor-pointer items-center">
                                    Submit Job
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
