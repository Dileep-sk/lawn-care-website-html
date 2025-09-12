<script setup>
import { ref, computed, watch, nextTick } from 'vue'

const props = defineProps({
    modelValue: [String, Number],
    label: String,
    options: Array,
    error: String,
    required: Boolean,
    placeholder: {
        type: String,
        default: 'Search or enter new...'
    },
    optionLabelKey: {
        type: String,
        default: 'name'
    },
    optionValueKey: {
        type: String,
        default: 'id'
    },
    isEdit: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const search = ref('')
const showDropdown = ref(false)
const justSelected = ref(false)
const showInput = ref(!props.isEdit)

const originalValue = ref('')

watch(
    () => props.modelValue,
    (val) => {
        if (!val) {
            search.value = ''
            originalValue.value = ''
            showDropdown.value = false
            return
        }
        const found = props.options.find(o => o[props.optionValueKey] === val)
        const label = found ? found[props.optionLabelKey] : val
        search.value = label
        originalValue.value = label
        showDropdown.value = false
    },
    { immediate: true }
)

const filteredOptions = computed(() => {
    if (!search.value) return props.options
    return props.options.filter(opt =>
        opt[props.optionLabelKey]?.toLowerCase().includes(search.value.toLowerCase())
    )
})

watch(search, () => {
    if (justSelected.value) return
    if (!showInput.value) return
    showDropdown.value = filteredOptions.value.length > 0
})

const selectOption = (item) => {
    justSelected.value = true
    search.value = item[props.optionLabelKey]
    originalValue.value = item[props.optionLabelKey]
    emit('update:modelValue', item[props.optionValueKey])
    showDropdown.value = false
    setTimeout(() => (justSelected.value = false), 200)
}

const handleBlur = () => {
    setTimeout(() => {
        if (justSelected.value) return

        const found = props.options.find(
            o => o[props.optionLabelKey]?.toLowerCase() === search.value.toLowerCase()
        )

        if (found) {
            emit('update:modelValue', found[props.optionValueKey])
            search.value = found[props.optionLabelKey]
            originalValue.value = found[props.optionLabelKey]
        } else if (search.value.trim()) {
            emit('update:modelValue', search.value.trim())
            originalValue.value = search.value.trim()
        } else {
            emit('update:modelValue', '')
            originalValue.value = ''
            search.value = ''
        }

        showDropdown.value = false

        if (props.isEdit) {
            showInput.value = false
        }
    }, 150)
}

const onFocus = () => {
    if (showInput.value) {
        showDropdown.value = true
    }
}

const onClick = () => {
    if (!showInput.value && props.isEdit) {
        search.value = ''
        showDropdown.value = true
        showInput.value = true
        nextTick(() => {
            const inputEl = document.getElementById(`autocomplete-${props.optionValueKey}`)
            if (inputEl) inputEl.focus()
        })
    }
}

const onInputClick = () => {
    if (showInput.value) {
        search.value = ''
        showDropdown.value = true
    }
}

const clearInput = () => {
    search.value = ''
    emit('update:modelValue', '')
    originalValue.value = ''
    showDropdown.value = false
    if (props.isEdit) {
        showInput.value = true
        nextTick(() => {
            const inputEl = document.getElementById(`autocomplete-${props.optionValueKey}`)
            if (inputEl) inputEl.focus()
        })
    }
}

const handleEnter = () => {
    const match = filteredOptions.value.find(
        (opt) =>
            opt[props.optionLabelKey]?.toLowerCase() === search.value.trim().toLowerCase()
    )

    if (match) {
        selectOption(match)
    } else if (search.value.trim()) {
        emit('update:modelValue', search.value.trim())
        originalValue.value = search.value.trim()
        showDropdown.value = false
        if (props.isEdit) {
            showInput.value = false
        }
    }
}
</script>

<template>
    <div class="relative">
        <label v-if="label" class="block font-medium mb-1">{{ label }}</label>

        <!-- Input -->
        <div v-if="showInput" class="relative">
            <input :id="`autocomplete-${props.optionValueKey}`" type="text" v-model="search" @focus="onFocus"
                @click="onInputClick" @keydown.enter.prevent="handleEnter" @blur="handleBlur" :placeholder="placeholder"
                class="block w-full appearance-none bg-white input !mt-[11px] border border-gray-300 hover:border-gray-400 rounded px-3 py-2 pr-8 leading-tight"
                :required="required" autocomplete="off" />

            <div class="absolute inset-y-0 right-0 flex items-center space-x-1 px-2" style="pointer-events:auto;">
                <button v-if="search" @click.prevent="clearInput"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none" aria-label="Clear input">
                    ✕
                </button>
                <svg class="fill-current h-4 w-4 text-gray-500" viewBox="0 0 20 20">
                    <path d="M5.516 7.548L10 12.033l4.484-4.485L16 8.064l-6 6-6-6z" />
                </svg>
            </div>
        </div>

        <!-- Label View -->
        <div v-else @click="onClick"
            class="block w-full appearance-none bg-white border border-gray-300 hover:border-gray-400 rounded px-3 py-2 pr-8 leading-tight">
            {{ search && search.trim() !== '' ? search : '\u00A0' }}
        </div>

        <!-- Dropdown -->
        <ul v-if="showDropdown && filteredOptions.length"
            class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="option in filteredOptions" :key="option[optionValueKey]"
                @mousedown.prevent="selectOption(option)"
                class="px-3 py-2 cursor-pointer hover:bg-blue-600 hover:text-white">
                {{ option[optionLabelKey] }}
            </li>
        </ul>

        <!-- Error -->
        <p v-if="error" class="text-red-500 text-xs mt-1">{{ error }}</p>
    </div>
</template>
