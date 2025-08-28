<script setup>
import { ref, onMounted, computed, watch } from "vue"
import { useTransportCompanies } from "@/composables/useTransportCompanies"

const modelValue = defineModel()

const search = ref("")
const showDropdown = ref(false)
const justSelected = ref(false)  // Prevent premature dropdown close

const { options: companies, loading, error, handleFetchTransportCompanies } = useTransportCompanies()

onMounted(() => {
    handleFetchTransportCompanies()
})

watch(modelValue, (val) => {
    if (!val) {
        search.value = ""
        return
    }

    const found = companies.value.find(c => c.id === val)
    search.value = found ? found.name : val
}, { immediate: true })

const filteredTransportCompanies = computed(() => {
    if (!search.value) return companies.value
    return companies.value.filter(c =>
        c.name.toLowerCase().includes(search.value.toLowerCase())
    )
})

// Select company from dropdown
const selectCompany = (company) => {
    justSelected.value = true
    modelValue.value = company.id
    search.value = company.name
    showDropdown.value = false
    setTimeout(() => {
        justSelected.value = false
    }, 200)
}

// Close dropdown with delay to allow click selection
const handleBlur = () => {
    setTimeout(() => {
        if (justSelected.value) return
        const found = companies.value.find(c => c.name === search.value)
        if (!found && search.value) {
            modelValue.value = search.value
        }
        showDropdown.value = false
    }, 150)
}

// Show dropdown on focus
const onFocus = () => {
    showDropdown.value = true
}

// Toggle dropdown on click (open if closed)
const onClick = () => {
    if (!showDropdown.value) {
        showDropdown.value = true
    }
}

watch(search, () => {
    if (justSelected.value) return
    showDropdown.value = filteredTransportCompanies.value.length > 0
})
</script>

<template>
    <div class="relative">
        <label class="block font-medium mb-1">Transport Company</label>

        <input
            type="text"
            v-model="search"
            @focus="onFocus"
            @click="onClick"
            @blur="handleBlur"
            placeholder="Search Transport Company..."
            class="input w-full pr-10"
        />

        <ul
            v-if="showDropdown && filteredTransportCompanies.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto"
        >
            <li
                v-for="company in filteredTransportCompanies"
                :key="company.id"
                @mousedown.prevent="selectCompany(company)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100"
            >
                {{ company.name }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
