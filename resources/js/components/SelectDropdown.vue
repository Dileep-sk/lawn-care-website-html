<script>
export default {
    name: "SelectDropdown",
    props: {
        modelValue: {
            type: [String, Number],
            default: ""
        },
        label: {
            type: String,
            required: true
        },
        options: {
            type: Array,
            required: true
        },
        placeholder: {
            type: String,
            default: "Select an option"
        }
    },
    emits: ["update:modelValue"],
    methods: {
        onChange(event) {
            this.$emit("update:modelValue", event.target.value);
        }
    }
};
</script>


<template>
    <div class="input_box">
        <label>{{ label }}</label>
        <select :value="modelValue" @change="onChange" class="input">
            <option disabled value="">{{ placeholder }}</option>
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.text }}
            </option>
        </select>
    </div>
</template>
