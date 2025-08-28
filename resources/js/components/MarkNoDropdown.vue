<script setup>
import { ref, onMounted } from 'vue'
import Autocomplete from '@/components/Autocomplete.vue'
import { useMarkNo } from '@/composables/useMarkNo'

const modelValue = defineModel()

const { options, error: fetchError, fetchMarkNos } = useMarkNo()

onMounted(() => {
    fetchMarkNos(true)
})
</script>

<template>
    <Autocomplete v-model="modelValue" :options="options" label="Mark No" :required="true" :error="fetchError"
        option-label-key="name" option-value-key="id" />
</template>
