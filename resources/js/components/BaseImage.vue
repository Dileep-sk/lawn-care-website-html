<script setup>
import { ref, watch, onUnmounted, defineProps, defineEmits } from 'vue'

const props = defineProps({
  modelValue: [File, String, null],
  label: { type: String, default: '' },
})

const emit = defineEmits(['update:modelValue'])

const imagePreview = ref(null)
const isUrl = ref(false)

const revokeUrl = () => {
  if (!isUrl.value && imagePreview.value) URL.revokeObjectURL(imagePreview.value)
}

watch(() => props.modelValue, (newVal) => {
  // Revoke old URL if it was a File
  if (imagePreview.value && !isUrl.value) {
    URL.revokeObjectURL(imagePreview.value)
  }

  if (!newVal) {
    imagePreview.value = null
    isUrl.value = false
  } else if (typeof newVal === 'string') {
    imagePreview.value = newVal
    isUrl.value = true
  } else if (newVal instanceof File) {
    imagePreview.value = URL.createObjectURL(newVal)
    isUrl.value = false
  }
}, { immediate: true })

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (!file) return
  emit('update:modelValue', file)
  event.target.value = '' // reset input
}

const removeImage = () => {
  emit('update:modelValue', null)
}

onUnmounted(() => {
  revokeUrl()
})
</script>

<template>
  <div class="single-image-uploader">
    <label v-if="label" class="block font-medium mb-2">{{ label }}</label>
    <div class="relative border border-gray-300 rounded p-2 flex flex-col items-center justify-center cursor-pointer bg-white">
      <input type="file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="handleFileChange" />
      <template v-if="!imagePreview">
        <p class="text-gray-500 text-sm">Click to upload an image</p>
      </template>
      <template v-else>
        <img :src="imagePreview" alt="Selected image" class="max-h-40 object-contain" />
        <button type="button" class="absolute top-1 right-1 text-red-500 text-lg font-bold" @click.prevent="removeImage">&times;</button>
      </template>
    </div>
  </div>
</template>
