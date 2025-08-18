<script setup>
import AuthLayout from '../../layouts/AuthLayout.vue'
import search from '@/assets/icons/search.svg'
import plus from '@/assets/icons/plus.svg'
import edit from '@/assets/icons/edit.svg'
import deletes from '@/assets/icons/delete.svg'
import eye from '@/assets/icons/eye.svg'
import arrow_black from '@/assets/icons/left-arrow_black.svg'
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px]  w-[100%] ">
            <div class="content_container w-[100%] h-[100%]  bg-white rounded-[10px]">
                <div
                    class="top_header border-b p-[15px]  border-b-[#E8F0E2] border border-transparent flex justify-between items-center">

                    <h1 class="text-[20px] text-[#000] font-[700]">Job List</h1>

                    <div class="flex gap-[15px] items-center">
                        <div class="search_box relative">
                            <img :src="search" class="absolute w-[20px]" alt="" srcset="">
                            <input type="text" placeholder="Search here.."
                                class=" input !border-0 !w-[325px] rounded-[5px] !mt-[0] !px-[40px] !h-[45px] bg-[rgba(23,23,23,0.05)] ">
                        </div>
                        <div class="flex gap-[15px] items-center">
                            <select name=""
                                class="input !w-[250px] !border-0 !mt-[0] !bg-[rgba(23,23,23,0.05)] !h-[45px]" id="">
                                <option value="">Select Status</option>
                                <option value="">Penddign</option>
                                <option value="">Done</option>
                                <option value="">Select Status</option>
                            </select>
                            <button type="button"
                                class=" cursor-pointer h-[45px] px-[30px] text-[#fff] text-[15px] font-[500] rounded-[5px] bg-[#1E3799]   ">
                                Filter </button>
                        </div>
                        <router-link :to="{ name: 'jobs-create' }"
                            class="flex items-center gap-[5px] h-[45px] px-[15px] text-[15px] font-[500] text-white rounded-[5px] bg-[#05C46B]">
                            Create Job
                            <img :src="plus" class="w-[22px]" alt="Plus Icon" />
                        </router-link>
                    </div>
                </div>
                <div class="h-[calc(100vh_-_209px)] flex flex-col justify-between">
                    <div class="table_container p-[15px]">
                        <table>
                            <thead>
                                <tr>
                                    <th>Job  No</th>
                                    <th>Design No</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th class="w-[100px]">Edit</th>
                                    <th class="w-[100px]">Delete</th>
                                    <th class="w-[130px]">View Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>ORD1234</td>
                                    <td>20.00</td>

                                    <td>
                                        <div class="badge todo">TO DO </div>
                                    </td>
                                    <td> <router-link :to="{ name: 'jobs-edit' }"
                                            class="w-[70px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#1e90ff]">
                                            <img :src="edit" class="w-[20px]" alt="">

                                            Edit

                                        </router-link></td>
                                    <td> <a href="#delete_order" rel="modal:open"
                                            class="w-[90px] gap-[5px] text-white h-[35px] flex justify-center text-[15px] items-center rounded-[5px] bg-[#D62925]">
                                            <img :src="deletes" class="w-[20px]" alt="">
                                            Delete
                                        </a> </td>

                                    <td> <a href="#view_job" rel="modal:open"
                                            class="bg-[#3C40C6] cursor-pointer  !w-[130px]  h-[35px] text-white flex justify-center gap-[5px] items-center  rounded-[5px]">
                                            <img :src="eye" class="w-[20px]" alt="">
                                            View job
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination flex justify-end p-[15px]">
                        <ul class="flex gap-[10px]">
                            <li class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center">
                                <img :src="arrow_black" alt="">
                            </li>
                            <li
                                class="w-[45px] h-[35px] bg-[#000] flex justify-center items-center rounded-[5px] text-white border-[1px] border-[#000]">
                                1</li>
                            <li
                                class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center rounded-[5px] text-black border-[1px] border-[#000]">
                                2</li>
                            <li
                                class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center rounded-[5px] text-black border-[1px] border-[#000]">
                                3</li>
                            <li class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center ">
                                <img :src="arrow_black" class="rotate-[-180deg]" alt="">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
