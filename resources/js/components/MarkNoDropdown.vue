<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useMarkNo } from '@/composables/useMarkNo'

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const showDropdown = ref(false)

const { options, loading, error: fetchError, fetchMarkNos } = useMarkNo()

onMounted(() => {
    fetchMarkNos(true)
})

watch(

    (val) => {
        if (!val) return
        const found = options.value.find(o => o.id === val)
        search.value = found ? found.name : val
    },
    { immediate: true }
)

const filteredOptions = computed(() => {
    if (!search.value) return options.value
    return options.value.filter(mark =>
        mark.name.includes(search.value)
    )
})

const selectOption = (mark) => {
    search.value = mark.name
    emit('update:modelValue', mark.id)
    showDropdown.value = false
}

const handleBlur = () => {
    setTimeout(() => {
        const found = options.value.find(o => o.name === search.value)
        if (found) {
            emit('update:modelValue', found.id)
        } else if (search.value.trim()) {
            emit('update:modelValue', search.value.trim())
        } else {
            emit('update:modelValue', '')
        }
        showDropdown.value = false
    }, 150)
}
watch(search, () => {
    showDropdown.value = filteredOptions.value.length > 0
})

</script>

<template>
    <div class="relative">
        <label class="block font-medium mb-1">Mark No</label>

        <input type="text" v-model="search" @focus="showDropdown = true" @blur="handleBlur"
            placeholder="Search or enter new..." class="input w-full pr-10" :required="required" />

        <ul v-if="showDropdown && filteredOptions.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="mark in filteredOptions" :key="mark.id" @mousedown.prevent="selectOption(mark)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                {{ mark.name }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
        <p v-if="fetchError" class="text-red-500 text-xs mt-1">{{ fetchError }}</p>
    </div>
</template>
