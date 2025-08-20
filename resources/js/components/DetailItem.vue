<script setup>
defineProps({
  label: { type: String, required: true },
  value: { type: [String, Number], required: true },
  extraClass: { type: String, default: '' }
})
</script>

<template>
  <li class="flex mb-[12px] items-center">
    <h6 class="font-semibold w-[200px]">{{ label }}</h6>:
    <p :class="['ml-[20px]', extraClass]">{{ value }}</p>
  </li>
</template>
