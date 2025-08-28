<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    options: Array,
    error: String,
    required: Boolean,
    placeholder: {
        type: String,
        default: 'Search or enter new...',
    },
    optionLabelKey: {
        type: String,
        default: 'name',
    },
    optionValueKey: {
        type: String,
        default: 'id',
    }
})

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const showDropdown = ref(false)
const justSelected = ref(false)

watch(() => props.modelValue, (val) => {
    if (!val) {
        search.value = ''
        return
    }

    const found = props.options.find(
        o => o[props.optionValueKey] === val
    )

    search.value = found ? found[props.optionLabelKey] : val
}, { immediate: true })

const filteredOptions = computed(() => {
    if (!search.value) return props.options
    return props.options.filter(opt =>
        opt[props.optionLabelKey]?.toLowerCase().includes(search.value.toLowerCase())
    )
})

watch(search, () => {
    if (justSelected.value) return
    showDropdown.value = filteredOptions.value.length > 0
})

const selectOption = (item) => {
    justSelected.value = true
    search.value = item[props.optionLabelKey]
    emit('update:modelValue', item[props.optionValueKey])
    showDropdown.value = false
    setTimeout(() => justSelected.value = false, 200)
}

const handleBlur = () => {
    setTimeout(() => {
        if (justSelected.value) return

        const found = props.options.find(
            o => o[props.optionLabelKey]?.toLowerCase() === search.value.toLowerCase()
        )

        if (found) {
            emit('update:modelValue', found[props.optionValueKey])
        } else if (search.value.trim()) {
            emit('update:modelValue', search.value.trim())
        } else {
            emit('update:modelValue', '')
        }

        showDropdown.value = false
    }, 150)
}

const onFocus = () => showDropdown.value = true
const onClick = () => showDropdown.value = true
</script>

<template>
    <div class="relative">
        <label v-if="label" class="block font-medium mb-1">{{ label }}</label>

        <div class="relative">
            <input type="text" v-model="search" @focus="onFocus" @click="onClick" @blur="handleBlur"
                :placeholder="placeholder"
                class="block w-full appearance-none bg-white border border-gray-300 hover:border-gray-400 rounded px-3 py-2 pr-8 leading-tight "
                :required="required" autocomplete="off" />
            <!-- Dropdown arrow icon -->
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                    <path d="M5.516 7.548L10 12.033l4.484-4.485L16 8.064l-6 6-6-6z" />
                </svg>
            </div>
        </div>

        <ul v-if="showDropdown && filteredOptions.length"
            class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="option in filteredOptions" :key="option[optionValueKey]"
                @mousedown.prevent="selectOption(option)" class="px-3 py-2 cursor-pointer hover:bg-blue-600 hover:text-white">
                {{ option[optionLabelKey] }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
    </div>
</template>
