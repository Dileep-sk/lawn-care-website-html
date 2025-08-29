<script setup>
import { onMounted, ref } from "vue";
import Autocomplete from "@/components/Autocomplete.vue";
import { useOrders } from "@/composables/useOrders";

const emit = defineEmits(["update:modelValue"]);
const props = defineProps({
    modelValue: [String, Number, null],
    required: Boolean,
});

const { fetchAllOrderNos } = useOrders();
const options = ref([]);
const fetchError = ref(null);

const fetchOrderNos = async () => {
    fetchError.value = null;
    try {
        const data = await fetchAllOrderNos();
        options.value = data;
    } catch (err) {
        fetchError.value = err.message || "Failed to fetch order numbers";
    }
};

onMounted(() => {
    fetchOrderNos();
});
</script>

<template>
    <Autocomplete
        :model-value="props.modelValue"
        @update:modelValue="(val) => emit('update:modelValue', val)"
        label="Order No"
        :options="options"
        option-label-key="order_no"
        option-value-key="id"
        :error="fetchError"
        placeholder="Search or enter new..."
        :required="required"
    />
</template>
