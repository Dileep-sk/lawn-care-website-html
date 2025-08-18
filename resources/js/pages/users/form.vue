<script setup>
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import eye from '@/assets/icons/eye-off.svg'
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px]  border-b-[#E8F0E2] border border-transparent flex justify-between items-center">

                    <h1 class="text-[20px] text-[#000] font-[700]"> Add User</h1>


                    <router-link :to="{ name: 'users' }"
                        class=" cursor-pointer h-[45px] flex px-[20px] items-center gap-[10px] text-[#fff] text-[15px] font-[500] rounded-[5px] bg-[#000]   ">
                        <img :src="left_arrow" class="w-[20px]" alt="">
                        Back </router-link>


                </div>

                <div class="form_box p-[15px]">
                    <form action="" class="p-[15px] bg-[rgba(56,92,76,0.04)] ">
                        <div class="grid grid-cols-4 gap-[30px]">

                            <div class="input_box">
                                <label for="">User Name</label>
                                <input type="text" class="input" placeholder="test test" name="" id="">
                            </div>
                            <div class="input_box">
                                <label for="">Email</label>
                                <input type="text" class="input" placeholder="test@gmail.com" name="" id="">
                            </div>
                            <div class="input_box">
                                <label for="">Mobile Number</label>
                                <input type="text" class="input" placeholder=" +911234567895" name="" id="">
                            </div>

                            <div class="input_box">
                                <label for="password">Password</label>
                                <div class="relative">
                                    <input id="userPass" type="password" class="input w-full pr-10"
                                        placeholder="*********" />
                                    <img onclick="togglePassword('userPass', this)" :src="eye"
                                        class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                                        alt="Toggle visibility" />
                                </div>
                            </div>



                        </div>

                        <div class="flex justify-end mt-[20px]">
                            <button type="button"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center"> Add User
                            </button>
                        </div>
                    </form>
                </div>



            </div>
        </div>
    </AuthLayout>
</template>
