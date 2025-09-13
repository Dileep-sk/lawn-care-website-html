<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  label: String,
  modelValue: String,
  placeholder: String,
  error: String
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <div class="flex flex-col">
    <label v-if="label" class="block mb-1 font-medium">{{ label }}</label>
    <textarea
      :placeholder="placeholder"
      class="border rounded p-2 bg-white border border-[#d4d4d4]"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
    <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
  </div>
</template>
