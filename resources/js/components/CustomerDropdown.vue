<script setup>
import Autocomplete from '@/components/Autocomplete.vue'
import { onMounted } from 'vue'
import { useCustomers } from '@/composables/useCustomers'

const modelValue = defineModel()
const { options, error, handleFetchCustomers } = useCustomers()

onMounted(() => {
  handleFetchCustomers()
})
</script>

<template>
  <Autocomplete
    v-model="modelValue"
    label="Customer Name"
    :options="options"
    :error="error"
    option-label-key="name"
    option-value-key="id"
    placeholder="Search Customer..."
  />
</template>
