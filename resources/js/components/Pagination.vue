<script setup>
import { computed } from 'vue'

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    lastPage: {
        type: Number,
        required: true
    }
})

const emit = defineEmits(['page-changed'])

const pagesToShow = computed(() => {
    const windowSize = 5
    const halfWindow = Math.floor(windowSize / 2)
    let startPage = props.currentPage - halfWindow
    let endPage = props.currentPage + halfWindow

    if (startPage < 1) {
        startPage = 1
        endPage = Math.min(windowSize, props.lastPage)
    }
    if (endPage > props.lastPage) {
        endPage = props.lastPage
        startPage = Math.max(1, props.lastPage - windowSize + 1)
    }

    const pages = []
    for (let i = startPage; i <= endPage; i++) {
        pages.push(i)
    }
    return pages
})

function goToPage(page) {
    if (page >= 1 && page <= props.lastPage) {
        emit('page-changed', page)
    }
}
</script>

<template>
    <div class="flex justify-end p-[15px]">
        <ul class="flex gap-[10px] items-center">
            <li class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center cursor-pointer"
                :class="{ 'opacity-50 pointer-events-none': currentPage === 1 }" @click="goToPage(currentPage - 1)">
                <slot name="prev"></slot>
            </li>

            <li v-for="page in pagesToShow" :key="page" @click="goToPage(page)"
                class="w-[45px] h-[35px] flex justify-center items-center rounded-[5px] border-[1px] cursor-pointer"
                :class="currentPage === page ? 'bg-[#000] text-white border-[#000]' : 'bg-[#fff] text-black border-[#000]'">
                {{ page }}
            </li>

            <li class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center cursor-pointer"
                :class="{ 'opacity-50 pointer-events-none': currentPage === lastPage }"
                @click="goToPage(currentPage + 1)">
                <slot name="next"></slot>
            </li>
        </ul>
    </div>
</template>
