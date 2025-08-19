<script setup>
import { ref, onMounted } from 'vue'
import AuthLayout from "../../layouts/AuthLayout.vue";
import eye from '@/assets/icons/eye-off.svg'

const user = ref({
    email: '',
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
                <div
                    class="top_header border-b p-[15px] border-b-[#E8F0E2] border border-transparent flex justify-between items-center">
                    <h1 class="text-[20px] text-[#000] font-[700]">Profile</h1>
                </div>

                <div class="form_box p-[15px]">
                    <form action="" class="p-[15px] bg-[rgba(56,92,76,0.04)] max-w-[500px] mx-auto">
                        <div class="grid grid-cols-1 gap-[30px]">
                            <div class="input_box">
                                <label for="">Email</label>
                                <input type="text" class="input" :value="user.email" placeholder="Email" readonly />

                            </div>

                            <!-- Old Password -->
                            <div class="input_box">
                                <label for="oldPassword">Old Password</label>
                                <div class="relative">
                                    <input value="123546ss" type="password" id="oldPassword" class="input w-full pr-10"
                                        placeholder="***********" />
                                    <img :src="eye" class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                                        onclick="togglePassword('oldPassword', this)" />
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="input_box">
                                <label for="newPassword">New Password</label>
                                <div class="relative">
                                    <input type="password" id="newPassword" class="input w-full pr-10"
                                        placeholder="********" />
                                    <img :src="eye" class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                                        onclick="togglePassword('newPassword', this)" />
                                </div>
                            </div>

                            <!-- Re-entry New Password -->
                            <div class="input_box">
                                <label for="reNewPassword">Re-entry New Password</label>
                                <div class="relative">
                                    <input type="password" id="reNewPassword" class="input w-full pr-10"
                                        placeholder="********" />
                                    <img :src="eye" class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                                        onclick="togglePassword('reNewPassword', this)" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-[20px]">
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
