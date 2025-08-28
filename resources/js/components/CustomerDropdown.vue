<script setup>
import { ref, onMounted, computed, watch } from "vue"
import { useCustomers } from "@/composables/useCustomers"

const modelValue = defineModel()

const search = ref("")
const showDropdown = ref(false)
const justSelected = ref(false)  // <-- Flag to prevent flicker on select

const { options: customers, loading, error, handleFetchCustomers } = useCustomers()

onMounted(() => {
    handleFetchCustomers()
})

watch(modelValue, (val) => {
    if (!val) {
        search.value = ""
        return
    }

    const found = customers.value.find(c => c.id === val)
    search.value = found ? found.name : val
}, { immediate: true })

const filteredCustomers = computed(() => {
    if (!search.value) return customers.value
    return customers.value.filter(c =>
        c.name.toLowerCase().includes(search.value.toLowerCase())
    )
})

const selectCustomer = (customer) => {
    justSelected.value = true
    modelValue.value = customer.id
    search.value = customer.name
    showDropdown.value = false
    setTimeout(() => {
        justSelected.value = false
    }, 200) // small delay to allow blur to finish
}

const handleBlur = () => {
    setTimeout(() => {
        if (justSelected.value) return // Prevent closing if just selected
        const found = customers.value.find(c => c.name === search.value)
        if (!found && search.value) {
            modelValue.value = search.value
        }
        showDropdown.value = false
    }, 150) // delay to allow click events inside dropdown to fire
}

watch(search, () => {
    if (justSelected.value) return
    showDropdown.value = filteredCustomers.value.length > 0
})

const onFocus = () => {
    showDropdown.value = true
}

const onClick = () => {
    showDropdown.value = true
}

</script>

<template>
    <div class="relative">
        <label class="block font-medium mb-1">Customer Name</label>

        <input
            type="text"
            v-model="search"
            @focus="onFocus"
            @click="onClick"
            @blur="handleBlur"
            placeholder="Search Customer..."
            class="input w-full pr-10"
        />

        <ul v-if="showDropdown && filteredCustomers.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto"
        >
            <li
                v-for="customer in filteredCustomers"
                :key="customer.id"
                @mousedown.prevent="selectCustomer(customer)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100"
            >
                {{ customer.name }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
