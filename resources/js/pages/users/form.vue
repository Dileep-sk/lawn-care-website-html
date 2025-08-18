<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import eye from '@/assets/icons/eye-off.svg'
import { createUser } from '@/services/userService'
import toastr from 'toastr'

const router = useRouter()

// Form refs
const name = ref('')
const email = ref('')
const mobile_number = ref('')
const password = ref('')
const password_confirmation = ref('')

// Create user handler
const handleCreateUser = async () => {
  try {
    const payload = {
      name: name.value,
      email: email.value,
      mobile_number: mobile_number.value,
      password: password.value,
      password_confirmation: password_confirmation.value,
    }
    await createUser(payload)
    toastr.success('User created successfully', 'Success')
    router.push({ name: 'users' })
  } catch (error) {
    if (error.response && error.response.data?.errors) {
      const errors = error.response.data.errors
      Object.values(errors).forEach(err => toastr.error(err[0], 'Error'))
    } else {
      toastr.error('Something went wrong', 'Error')
    }
  }
}

// Password toggle function
const togglePassword = (id) => {
  const input = document.getElementById(id)
  if (input.type === 'password') {
    input.type = 'text'
  } else {
    input.type = 'password'
  }
}
</script>

<template>
  <AuthLayout>
    <div class="inner_contant mt-[20px] w-[100%]">
      <div class="content_container w-[100%] h-[100%] bg-white rounded-[10px]">
        <div
          class="top_header border-b p-[15px] border-b-[#E8F0E2] border border-transparent flex justify-between items-center"
        >
          <h1 class="text-[20px] text-[#000] font-[700]">Add User</h1>

          <router-link
            :to="{ name: 'users' }"
            class="cursor-pointer h-[45px] flex px-[20px] items-center gap-[10px] text-[#fff] text-[15px] font-[500] rounded-[5px] bg-[#000]"
          >
            <img :src="left_arrow" class="w-[20px]" alt="Back" /> Back
          </router-link>
        </div>

        <div class="form_box p-[15px]">
          <form class="p-[15px] bg-[rgba(56,92,76,0.04)]" @submit.prevent="handleCreateUser">
            <div class="grid grid-cols-4 gap-[30px]">
              <div class="input_box">
                <label>User Name</label>
                <input type="text" class="input" placeholder="test test" v-model="name" />
              </div>

              <div class="input_box">
                <label>Email</label>
                <input type="text" class="input" placeholder="test@gmail.com" v-model="email" />
              </div>

              <div class="input_box">
                <label>Mobile Number</label>
                <input type="text" class="input" placeholder="+911234567895" v-model="mobile_number" />
              </div>

              <div class="input_box">
                <label for="password">Password</label>
                <div class="relative">
                  <input
                    id="userPass"
                    type="password"
                    class="input w-full pr-10"
                    placeholder="*********"
                    v-model="password"
                  />
                  <img
                    @click="togglePassword('userPass')"
                    :src="eye"
                    class="absolute right-[10px] top-[15px] w-5 h-5 cursor-pointer"
                    alt="Toggle visibility"
                  />
                </div>
              </div>
            </div>

            <div class="flex justify-end mt-[20px]">
              <button
                type="submit"
                class="flex create justify-center gap-[10px] cursor-pointer items-center"
              >
                Add User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthLayout>
</template>
