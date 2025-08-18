<script setup>
import close from '@/assets/icons/close.svg'
defineProps({
    isOpen: Boolean
})
defineEmits(['close'])
</script>
<template>

    <div v-if="isOpen" id="view_order"
        class="fixed inset-0 bg-[#00000073] bg-opacity-40 z-50 flex justify-center items-center">
        <div class="bg-white rounded-[10px] !p-[25px] relative">
            <div class="flex justify-between mb-[20px]">
                <h2 class="font-[700] text-[20px]">Order Detail</h2>
                <button @click="$emit('close')">
                    <img :src="close" alt="Close" />
                </button>
            </div>

            <div class="detail_box bg-[rgba(56,92,76,0.04)] p-[20px] rounded-[10px]">
                <ul>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Order No</h6>:
                        <p class="ml-[20px]">ORD1234</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Customer Name</h6>:
                        <p class="ml-[20px]">ABC Textiles</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Date</h6>:
                        <p class="ml-[20px]">05/07/2025</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Broker Name</h6>:
                        <p class="ml-[20px]">XYZ Textiles</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Transport Company</h6>:
                        <p class="ml-[20px]">XYZ PVT.LTD Surat</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Design No</h6>:
                        <p class="ml-[20px]">D1002</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Item Name</h6>:
                        <p class="ml-[20px]">Ghagra Choli</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Quantity</h6>:
                        <p class="ml-[20px]">20.00</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Rate</h6>:
                        <p class="ml-[20px]">8000.00</p>
                    </li>
                    <li class="flex !mb-[12px]">
                        <h6 class="font-[600] w-[200px]">Status</h6>:
                        <p class="ml-[20px]">Pending</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
