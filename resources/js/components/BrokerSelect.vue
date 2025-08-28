<script setup>
import Autocomplete from '@/components/Autocomplete.vue'
import { onMounted } from 'vue'
import { useBrokers } from '@/composables/useBrokers'

const modelValue = defineModel()
const { options, error, handleFetchBrokers } = useBrokers()

onMounted(() => {
  handleFetchBrokers()
})
</script>

<template>
  <Autocomplete
    v-model="modelValue"
    label="Broker Name"
    :options="options"
    :error="error"
    option-label-key="name"
    option-value-key="id"
    placeholder="Search Broker..."
  />
</template>
