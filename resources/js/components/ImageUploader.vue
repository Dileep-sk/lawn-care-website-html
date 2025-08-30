<script setup>
import { ref, watch, defineEmits, defineProps, onUnmounted } from 'vue'

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update:modelValue'])

const imagePreviews = ref([]) // Array of { src: string, isUrl: boolean }

const revokeUrls = () => {
    imagePreviews.value.forEach(img => {
        if (!img.isUrl) URL.revokeObjectURL(img.src)
    })
}

watch(() => props.modelValue, (newValues) => {
    revokeUrls()
    imagePreviews.value = []

    newValues.forEach(item => {
        if (typeof item === 'string') {
            // Existing image URL
            imagePreviews.value.push({ src: item, isUrl: true })
        } else if (item instanceof File) {
            // New file, create object URL
            imagePreviews.value.push({ src: URL.createObjectURL(item), isUrl: false })
        }
    })
}, { immediate: true })

const handleFileChange = (event) => {
    const files = event.target.files ? Array.from(event.target.files) : []
    // Combine existing URLs with newly added files
    const existingUrls = props.modelValue.filter(item => typeof item === 'string')
    emit('update:modelValue', [...existingUrls, ...files])
}

const removeImage = (index) => {
    const newValues = [...props.modelValue]
    newValues.splice(index, 1)
    emit('update:modelValue', newValues)
}

onUnmounted(() => {
    revokeUrls()
})
</script>

<template>
    <div class="image-uploader-wrapper">
        <label class="upload-label" for="file-input">
            <input id="file-input" type="file" multiple accept="image/*" class="hidden" @change="handleFileChange" />
            <div class="upload-area">
                <svg xmlns="http://www.w3.org/2000/svg" class="upload-icon" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v8m0 0l-4-4m4 4l4-4M12 4v8" />
                </svg>
                <p>Click or drag images to upload</p>
                <small class="hint">Supported formats: JPG, PNG, GIF</small>
            </div>
        </label>

        <div class="image-preview-container">
            <div v-for="(image, index) in imagePreviews" :key="index" class="image-wrapper" tabindex="0"
                aria-label="Uploaded image preview">
                <img :src="image.src" alt="Image preview" class="image" />
                <button type="button" class="remove-btn" @click="removeImage(index)" aria-label="Remove image"
                    title="Remove image">
                    &times;
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.image-uploader-wrapper {
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.upload-label {
    cursor: pointer;
    display: block;
    border: 2px dashed #9ca3af;
    /* gray-400 */
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    transition: border-color 0.3s ease;
    background-color: #f9fafb;
    /* gray-50 */
}

.upload-label:hover,
.upload-label:focus-within {
    border-color: #3b82f6;
    /* blue-500 */
    background-color: #e0f2fe;
    /* blue-100 */
    outline: none;
}

.upload-area {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: #6b7280;
    /* gray-500 */
}

.upload-icon {
    width: 48px;
    height: 48px;
    stroke: #3b82f6;
    /* blue-500 */
}

.upload-area p {
    margin: 0;
    font-weight: 600;
    font-size: 1rem;
    color: #111827;
    /* gray-900 */
}

.hint {
    font-size: 0.85rem;
    color: #9ca3af;
    /* gray-400 */
}

.image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 20px;
}

.image-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgb(0 0 0 / 0.1);
    cursor: default;
    transition: transform 0.2s ease;
}

.image-wrapper:focus-within,
.image-wrapper:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgb(59 130 246 / 0.5);
    /* blue shadow */
    outline: none;
}

.image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.remove-btn {
    position: absolute;
    top: 6px;
    right: 6px;
    background-color: rgba(220, 38, 38, 0.85);
    /* red-600 */
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    color: white;
    font-size: 22px;
    line-height: 28px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.remove-btn:hover,
.remove-btn:focus {
    background-color: rgba(185, 28, 28, 1);
    /* red-700 */
    outline: none;
}

.image-uploader-wrapper {
    width: 100%;
    max-width: 100%;
    /* remove max-width limit */
    margin: 0 auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.upload-label {
    cursor: pointer;
    display: block;
    border: 2px dashed #9ca3af;
    border-radius: 8px;
    padding: 40px 20px;
    text-align: center;
    transition: border-color 0.3s ease;
    background-color: #f9fafb;
    width: 100%;
    /* full width */
    box-sizing: border-box;
}

.image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 20px;
    width: 100%;
    /* full width */
    box-sizing: border-box;
    justify-content: flex-start;
    /* align previews to left */
}

.image-wrapper {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 4px rgb(0 0 0 / 0.1);
    cursor: default;
    transition: transform 0.2s ease;
    flex-shrink: 0;
}
</style>
