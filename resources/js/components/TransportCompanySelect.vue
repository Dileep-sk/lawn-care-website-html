<script setup>
import { onMounted } from 'vue'
import Autocomplete from '@/components/Autocomplete.vue'
import { useTransportCompanies } from '@/composables/useTransportCompanies'

const modelValue = defineModel()

const { options, error, handleFetchTransportCompanies } = useTransportCompanies()

onMounted(() => {
  handleFetchTransportCompanies()
})
</script>

<template>
  <Autocomplete
    v-model="modelValue"
    label="Transport Company"
    :options="options"
    :error="error"
    option-label-key="name"
    option-value-key="id"
    placeholder="Search Transport Company..."
  />
</template>
