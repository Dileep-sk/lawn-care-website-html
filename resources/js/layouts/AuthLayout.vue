<script setup>
import { isLoading } from '@/stores/loadingStore'

import Sidebar from '../components/Sidebar.vue'
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
import Loader from '../components/Loader.vue'
</script>

<template>
    <div id="mainBody" class="flex min-h-screen bg-[#F0F1F1]">
        <Loader v-if="isLoading" />
        <div class="main_container flex w-full">
            <Sidebar />
            <div id="contentArea" class="left_side_content ml-[300px] w-full flex flex-col">
                <Header />
                <slot />
                <!-- <Footer /> -->
            </div>
        </div>
    </div>
</template>
