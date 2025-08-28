<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    modelValue: [String, Number, null],
    options: {
        type: Array,
        default: () => []
    },
    label: {
        type: String,
        default: ''
    },
    optionLabelKey: {
        type: String,
        default: 'name'
    },
    optionIdKey: {
        type: String,
        default: 'id'
    },
    placeholder: {
        type: String,
        default: ''
    },
    required: Boolean,
})

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const showDropdown = ref(false)
const justSelected = ref(false)

// Sync search input with modelValue
watch(
    () => props.modelValue,
    (val) => {
        if (val === null || val === undefined || val === '') {
            search.value = ''
            return
        }
        const found = props.options.find(o => o[props.optionIdKey] === val)
        search.value = found ? found[props.optionLabelKey] : ''
    },
    { immediate: true }
)

const filteredOptions = computed(() => {
    if (!search.value) return props.options
    return props.options.filter(item =>
        item[props.optionLabelKey].toLowerCase().includes(search.value.toLowerCase())
    )
})

watch(search, () => {
    if (justSelected.value) return
    showDropdown.value = filteredOptions.value.length > 0
})

const onFocus = () => {
    showDropdown.value = true
}

const onClick = () => {
    if (!showDropdown.value) {
        showDropdown.value = true
    }
}

const selectOption = (option) => {
    justSelected.value = true
    search.value = option[props.optionLabelKey]
    emit('update:modelValue', option[props.optionIdKey])
    showDropdown.value = false
    setTimeout(() => {
        justSelected.value = false
    }, 200)
}

const handleBlur = () => {
    setTimeout(() => {
        if (justSelected.value) return
        const found = props.options.find(
            o => o[props.optionLabelKey].toLowerCase() === search.value.trim().toLowerCase()
        )
        if (found) {
            emit('update:modelValue', found[props.optionIdKey])
        } else if (search.value.trim()) {
            emit('update:modelValue', null) // or emit typed value if you want
        } else {
            emit('update:modelValue', null)
        }
        showDropdown.value = false
    }, 150)
}
</script>

<template>
    <div class="relative">
        <label v-if="label" class="block font-medium mb-1">{{ label }}</label>

        <input type="text" v-model="search" @focus="onFocus" @click="onClick" @blur="handleBlur"
            :placeholder="placeholder" class="input w-full pr-10" :required="required" />

        <ul v-if="showDropdown && filteredOptions.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="option in filteredOptions" :key="option[optionIdKey]" @mousedown.prevent="selectOption(option)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                {{ option[optionLabelKey] }}
            </li>
        </ul>
    </div>
</template>
