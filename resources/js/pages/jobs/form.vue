<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import AuthLayout from '../../layouts/AuthLayout.vue'
import BaseInput from '@/components/BaseInput.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import right_arrow_white from '@/assets/icons/right-arrow_white.svg'
import close_white from '@/assets/icons/close_white.svg'
import cloth from '@/assets/images/cloth.png'

const route = useRoute()
const routeName = route.name

// form state
const form = ref({
    customer_name: '',
    design_no: 'D1002',
    images: [],
    quantity: '',
    order_no: '',
    status: 'pending',
    matchings: Array(8).fill({ color: '', text: '' }) // 8 matching fields
})

const handleFileUpload = (event) => {
    form.value.images = Array.from(event.target.files)
}

const handleSubmit = () => {
    console.log('Job Form:', form.value)
}
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-full">
            <div class="content_container w-full h-full bg-white rounded-[10px]">
                <!-- Header -->
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] text-black font-bold">Create Job</h1>
                    <router-link :to="{ name: 'jobs' }"
                        class="h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-medium rounded-[5px] bg-black">
                        <img :src="left_arrow" class="w-[20px]" alt="Back" />
                        Back
                    </router-link>
                </div>

                <!-- Form -->
                <div class="form_box p-[15px]">
                    <form class="p-[15px] bg-[rgba(56,92,76,0.04)]" @submit.prevent="handleSubmit">
                        <div class="grid grid-cols-3 gap-[30px]">
                            <!-- Customer Name -->
                            <div class="input_box">
                                <label>Customer Name</label>
                                <select v-model="form.customer_name" class="input">
                                    <option disabled value="">Select Customer</option>
                                    <option value="ABC Textiles">ABC Textiles</option>
                                </select>
                            </div>

                            <!-- Design No -->
                            <BaseInput v-model="form.design_no" label="Design No" placeholder="D1002" />

                            <!-- Image Upload -->
                            <div class="input_box">
                                <label>Image</label>
                                <input type="file" multiple class="input" @change="handleFileUpload" />
                            </div>

                            <BaseInput v-model="form.quantity" label="Quantity" type="number" placeholder="00.00" />

                            <BaseInput v-model="form.order_no" label="Order No" placeholder="ORD1234" />

                            <!-- Status -->
                            <div class="input_box">
                                <label>Status</label>
                                <select v-model="form.status" class="input">
                                    <option disabled value="">Select Status</option>
                                    <option value="pending">Pending</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>

                            <!-- Matchings -->
                            <div v-for="(matching, index) in form.matchings" :key="index" class="input_box">
                                <label>Matching {{ index + 1 }}</label>
                                <div class="flex gap-[25px]">
                                    <select v-model="matching.color" class="input">
                                        <option disabled value="">Select</option>
                                        <option value="red">Red</option>
                                        <option value="blue">Blue</option>
                                    </select>
                                    <input v-model="matching.text" type="text" class="input" />
                                </div>
                            </div>
                        </div>

                        <!-- Edit Mode -->
                        <div v-if="routeName === 'jobs-edit'">
                            <div class="flex gap-[15px] flex-wrap mt-[20px]">
                                <div v-for="(image, i) in [cloth, cloth, cloth]" :key="i" class="relative w-max">
                                    <button type="button"
                                        class="bg-[#EB2F06] right-[5px] top-[5px] absolute w-[20px] h-[20px] flex rounded-[5px] justify-center items-center">
                                        <img :src="close_white" class="w-[10px]" alt="remove" />
                                    </button>
                                    <img :src="image" class="w-[120px] rounded-[5px]" alt="" />
                                </div>
                            </div>

                            <div class="flex gap-[10px] justify-end mt-[15px]">
                                <button type="button"
                                    class="flex create justify-center !bg-[#5352ed] gap-[10px] cursor-pointer items-center">
                                    Move to Order
                                    <img :src="right_arrow_white" alt="" />
                                </button>
                                <button type="button"
                                    class="flex cancel justify-center gap-[10px] cursor-pointer items-center">
                                    Cancel
                                </button>
                                <button type="button"
                                    class="flex create justify-center gap-[10px] cursor-pointer items-center">
                                    Edit Job
                                </button>
                            </div>
                        </div>

                        <!-- Create Mode -->
                        <div v-else>
                            <div class="flex justify-end">
                                <button type="submit"
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
