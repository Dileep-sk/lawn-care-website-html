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
    const total = props.lastPage
    const current = props.currentPage
    const pages = []

    if (total <= 7) {

        for (let i = 1; i <= total; i++) pages.push(i)
    } else {

        pages.push(1, 2)

        if (current > 4) {
            pages.push('left-ellipsis')
        } else {
            for (let i = 3; i < Math.min(current, 4); i++) {
                pages.push(i)
            }
        }

        const start = Math.max(3, current - 1)
        const end = Math.min(total - 2, current + 1)
        for (let i = start; i <= end; i++) {
            if (!pages.includes(i)) pages.push(i)
        }

        if (current < total - 3) {
            pages.push('right-ellipsis')
        } else {
            for (let i = current + 2; i <= total - 2; i++) {
                pages.push(i)
            }
        }

        pages.push(total - 1, total)
    }

    return pages
})

function goToPage(page) {
    if (typeof page === 'number' && page !== props.currentPage && page >= 1 && page <= props.lastPage) {
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

            <li v-for="(page, index) in pagesToShow" :key="page + '-' + index"
                @click="typeof page === 'number' && goToPage(page)"
                class="w-[45px] h-[35px] flex justify-center items-center rounded-[5px] border-[1px] cursor-pointer"
                :class="{
                    'cursor-default pointer-events-none': page === 'left-ellipsis' || page === 'right-ellipsis',
                    'bg-[#000] text-white border-[#000]': currentPage === page,
                    'bg-[#fff] text-black border-[#000]': currentPage !== page && typeof page === 'number'
                }">
                <template v-if="page === 'left-ellipsis' || page === 'right-ellipsis'">...</template>
                <template v-else>{{ page }}</template>
            </li>

            <li class="w-[45px] h-[35px] bg-[#fff] flex justify-center items-center cursor-pointer"
                :class="{ 'opacity-50 pointer-events-none': currentPage === lastPage }"
                @click="goToPage(currentPage + 1)">
                <slot name="next"></slot>
            </li>
        </ul>
    </div>
</template>
