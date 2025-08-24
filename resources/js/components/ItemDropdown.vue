<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useItems } from '@/composables/useItems'

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        default: ''
    },
    label: {
        type: String,
        default: 'Select Item'
    },
    error: {
        type: String,
        default: ''
    },
    required: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const showDropdown = ref(false)

const { options, loading, error: fetchError, fetchItems } = useItems()

onMounted(() => {
    fetchItems(true)
})

watch(
    () => props.modelValue,
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
        item.name.toLowerCase().includes(search.value.toLowerCase())
    )
})

const selectOption = (item) => {
    search.value = item.name
    emit('update:modelValue', item.id)
    showDropdown.value = false
}

const handleBlur = () => {
    setTimeout(() => {
        const found = options.value.find(o => o.name.toLowerCase() === search.value.toLowerCase())
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
</script>

<template>
    <div class="flex flex-col relative">
        <label class="text-sm font-medium mb-1">{{ label }}</label>

        <input type="text" v-model="search" @focus="showDropdown = true" @blur="handleBlur"
            placeholder="Search or enter new..." class="input w-full pr-10" :required="required" />

        <ul v-if="showDropdown && filteredOptions.length"
            class="absolute top-full left-0 right-0 bg-white border rounded-md shadow-md max-h-40 overflow-y-auto z-10">
            <li v-for="item in filteredOptions" :key="item.id" @mousedown.prevent="selectOption(item)"
                class="p-2 cursor-pointer hover:bg-gray-100">
                {{ item.name }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
        <p v-if="fetchError" class="text-red-500 text-xs mt-1">{{ fetchError }}</p>
    </div>
</template>
