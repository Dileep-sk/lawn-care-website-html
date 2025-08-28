<script setup>
import { onMounted } from 'vue'
import Autocomplete from '@/components/Autocomplete.vue'
import { useItems } from '@/composables/useItems'

const modelValue = defineModel()

const { options, error: fetchError, fetchItems } = useItems()

onMounted(() => {
  fetchItems(true)
})
</script>

<template>
  <Autocomplete
    v-model="modelValue"
    :options="options"
    label="Item Name"
    :required="true"
    :error="fetchError"
    option-label-key="name"
    option-value-key="id"
  />
</template>
