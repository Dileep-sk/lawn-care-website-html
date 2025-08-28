<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useOrders } from '@/composables/useOrders'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    modelValue: [String, Number, null],  // accept id now
    required: Boolean
})

const { fetchAllOrderNos } = useOrders()

const search = ref('')
const showDropdown = ref(false)
const justSelected = ref(false)  // Prevent premature dropdown close
const options = ref([])
const loading = ref(false)
const fetchError = ref(null)

const fetchOrderNos = async () => {
    loading.value = true
    fetchError.value = null
    try {
        const data = await fetchAllOrderNos()
        options.value = data
    } catch (err) {
        fetchError.value = err.message || 'Failed to fetch order numbers'
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchOrderNos()
})

// Sync search input with modelValue (which is now id)
watch(
    () => props.modelValue,
    (val) => {
        if (val === null || val === undefined || val === '') {
            search.value = ''
            return
        }
        // Find order by id (modelValue)
        const found = options.value.find(o => o.id === val)
        search.value = found ? found.order_no : ''
    },
    { immediate: true }
)

const filteredOptions = computed(() => {
    if (!search.value) return options.value
    return options.value.filter(order =>
        order.order_no.toLowerCase().includes(search.value.toLowerCase())
    )
})

watch(search, () => {
    if (justSelected.value) return
    showDropdown.value = filteredOptions.value.length > 0
})

// Show dropdown on input focus
const onFocus = () => {
    showDropdown.value = true
}

// Toggle dropdown open on input click if closed
const onClick = () => {
    if (!showDropdown.value) {
        showDropdown.value = true
    }
}

const selectOption = (order) => {
    justSelected.value = true
    search.value = order.order_no
    emit('update:modelValue', order.id)  // emit the id here
    showDropdown.value = false
    setTimeout(() => {
        justSelected.value = false
    }, 200)
}

const handleBlur = () => {
    setTimeout(() => {
        if (justSelected.value) return
        // Try to find a matching order_no, case insensitive
        const found = options.value.find(o => o.order_no.toLowerCase() === search.value.trim().toLowerCase())
        if (found) {
            emit('update:modelValue', found.id)  // emit id if matched
        } else if (search.value.trim()) {
            emit('update:modelValue', null)  // no match — emit null (or handle as needed)
        } else {
            emit('update:modelValue', null)
        }
        showDropdown.value = false
    }, 150)
}
</script>

<template>
    <div class="relative">
        <label class="block font-medium mb-1">Order No</label>

        <input
            type="text"
            v-model="search"
            @focus="onFocus"
            @click="onClick"
            @blur="handleBlur"
            placeholder="Search or enter new..."
            class="input w-full pr-10"
            :required="required"
        />

        <ul
            v-if="showDropdown && filteredOptions.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto"
        >
            <li
                v-for="order in filteredOptions"
                :key="order.id"
                @mousedown.prevent="selectOption(order)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100"
            >
                {{ order.order_no }}
            </li>
        </ul>

        <p v-if="fetchError" class="text-red-500 text-xs mt-1">{{ fetchError }}</p>
    </div>
</template>
