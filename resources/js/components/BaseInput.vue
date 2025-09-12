<script setup>
import { ref } from "vue"
import eye_off from "@/assets/icons/eye-off.svg"
import eye_on from "@/assets/icons/eye-on.svg"


const props = defineProps({
    modelValue: [String, Number],
    label: String,
    type: {
        type: String,
        default: "text"
    },
    placeholder: String,
    readonly: Boolean,
    error: String,
    id: String
})

const emit = defineEmits(["update:modelValue"])

const inputType = ref(props.type)

// Toggle password visibility
const togglePassword = () => {
    if (inputType.value === "password") {
        inputType.value = "text"
    } else {
        inputType.value = "password"
    }
}
</script>

<template>
    <div class="input_box">
        <label v-if="label" :for="id" class="block mb-1 font-medium">{{ label }}</label>
        <div class="relative">
            <input :id="id" :type="inputType" class="input w-full pr-10" :placeholder="placeholder" :readonly="readonly"
                :value="modelValue" @input="emit('update:modelValue', $event.target.value)" />

            <!-- Show toggle only if password field -->
            <img v-if="props.type === 'password'" :src="inputType === 'password' ? eye_off : eye_on"
                class="absolute right-[10px] top-[50%] -translate-y-1/2 w-5 h-5 cursor-pointer"
                @click="togglePassword" />
        </div>

        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
