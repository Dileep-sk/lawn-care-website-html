<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useItems } from '@/composables/useItems'

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const showDropdown = ref(false)
const justSelected = ref(false)

const { options, loading, error: fetchError, fetchItems } = useItems()

onMounted(() => {
    fetchItems(true)
})

// Sync search with incoming modelValue
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
    return options.value.filter(item =>
        item.name.includes(search.value)
    )
})

watch(search, () => {
    if (justSelected.value) return
    showDropdown.value = filteredOptions.value.length > 0
})

const selectOption = (item) => {
    justSelected.value = true
    search.value = item.name
    emit('update:modelValue', item.id)
    showDropdown.value = false
    setTimeout(() => {
        justSelected.value = false
    }, 200)
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

const onFocus = () => {
    showDropdown.value = true
}

const onClick = () => {
    showDropdown.value = true
}
</script>


<template>
    <div class="relative">
        <label class="block font-medium mb-1">Item Name</label>

        <input type="text" v-model="search" @focus="onFocus" @click="onClick" @blur="handleBlur"
            placeholder="Search or enter new..." class="input w-full pr-10" :required="required" />

        <ul v-if="showDropdown && filteredOptions.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="item in filteredOptions" :key="item.id" @mousedown.prevent="selectOption(item)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                {{ item.name }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
        <p v-if="fetchError" class="text-red-500 text-xs mt-1">{{ fetchError }}</p>
    </div>
</template>
