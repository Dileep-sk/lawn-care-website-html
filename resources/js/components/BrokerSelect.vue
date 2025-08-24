<script setup>
import { ref, onMounted, computed, watch } from "vue"
import { useBrokers } from "@/composables/useBrokers"
import { options } from "toastr"

const modelValue = defineModel()

const search = ref("")
const showDropdown = ref(false)

const { options: brokers, loading, error, handleFetchBrokers } = useBrokers()


onMounted(() => {
    handleFetchBrokers()
})

watch(modelValue, (val) => {
    if (!val) {
        search.value = ""
        return
    }

    const found = brokers.value.find(b => b.id === val)

    search.value = found ? found.name : val
}, { immediate: true })


const filteredBrokers = computed(() => {
    if (!search.value) return brokers.value
    return brokers.value.filter(b =>
        b.name.toLowerCase().includes(search.value.toLowerCase())
    )
})


const selectBroker = (broker) => {
    modelValue.value = broker.id
    search.value = broker.name
    showDropdown.value = false
}


const handleBlur = () => {
    const found = brokers.value.find(b => b.name === search.value)
    if (!found && search.value) {
        modelValue.value = search.value
    }
    showDropdown.value = false
}
watch(search, () => {
    showDropdown.value = filteredBrokers.value.length > 0
})
</script>

<template>
    <div class="relative">
        <label class="block font-medium mb-1">Broker Name</label>

        <input type="text" v-model="search" @focus="showDropdown = true" @blur="handleBlur"
            placeholder="Search Broker..." class="input w-full pr-10" />


        <ul v-if="showDropdown && filteredBrokers.length"
            class="absolute z-10 w-full bg-white border rounded-lg shadow mt-1 max-h-40 overflow-y-auto">
            <li v-for="broker in filteredBrokers" :key="broker.id" @mousedown.prevent="selectBroker(broker)"
                class="px-3 py-2 cursor-pointer hover:bg-gray-100">
                {{ broker.name }}
            </li>
        </ul>

        <p v-if="error" class="text-red-500 text-sm mt-1">{{ error }}</p>
    </div>
</template>
