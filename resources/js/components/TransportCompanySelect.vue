<script setup>
import { ref, onMounted, computed, watch } from "vue"
import { useTransportCompanies } from "@/composables/useTransportCompanies"

const modelValue = defineModel()

const search = ref("")
const showDropdown = ref(false)

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

// Select
const selectCompany = (company) => {
    modelValue.value = company.id
    search.value = company.name
    showDropdown.value = false
}

// Blur
const handleBlur = () => {
    const found = companies.value.find(c => c.name === search.value)
    if (!found && search.value) {
        modelValue.value = search.value
    }
    showDropdown.value = false
}

watch(search, () => {
    showDropdown.value = filteredTransportCompanies.value.length > 0
})


</script>

<template>
    <div class="relative">
        <label class="block font-medium mb-1">Transport Company</label>

        <input type="text" v-model="search" @focus="showDropdown = true" @blur="handleBlur"
            placeholder="Search Transport Company..." class="input w-full pr-10" />

        <!-- Dropdown -->
        <ul v-if="showDropdown && filteredTransportCompanies.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="company in filteredTransportCompanies" :key="company.id" @mousedown.prevent="selectCompany(company)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                {{ company.name }}
            </li>
        </ul>

        <!-- Loading / Error -->
        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
