<script setup>
import { ref, onMounted } from 'vue'
import AuthLayout from "../../layouts/AuthLayout.vue";
import BaseInput from "@/components/BaseInput.vue"

const user = ref({
    email: '',
    oldPassword: '',
    newPassword: '',
    reNewPassword: ''
})

onMounted(() => {
    const userData = localStorage.getItem('user')
    if (userData) {
        user.value = JSON.parse(userData)
    }
})
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-[100%]">
            <div class="content_container w-[100%] h-[100%] bg-white rounded-[10px]">
                <div class="top_header border-b p-[15px] border-b-[#E8F0E2] flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]">Profile</h1>
                </div>

                <div class="form_box p-[15px]">
                    <form class="p-[15px] bg-[rgba(56,92,76,0.04)] max-w-[500px] mx-auto grid gap-[30px]">

                        <!-- Email -->
                        <BaseInput label="Email" v-model="user.email" type="text" placeholder="Email" readonly />

                        <!-- Old Password -->
                        <BaseInput label="Old Password" v-model="user.oldPassword" type="password"
                            placeholder="********" />

                        <!-- New Password -->
                        <BaseInput label="New Password" v-model="user.newPassword" type="password"
                            placeholder="********" />

                        <!-- Re-entry New Password -->
                        <BaseInput label="Re-entry New Password" v-model="user.reNewPassword" type="password"
                            placeholder="********" />

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="button"
                                class="flex create justify-center gap-[10px] cursor-pointer items-center">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
