<script setup>
import { onMounted } from 'vue'
import Autocomplete from '@/components/Autocomplete.vue'
import { useDesignNo } from '@/composables/useDesignNo'

const modelValue = defineModel()

const { options, error: fetchError, fetchDesignNos } = useDesignNo()

onMounted(() => {
    fetchDesignNos(true)
})
</script>

<template>
    <Autocomplete v-model="modelValue" :options="options" label="Design No" :required="true" :error="fetchError"
        option-label-key="name" option-value-key="id" />
</template>
