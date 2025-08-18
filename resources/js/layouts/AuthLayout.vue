<script setup>
import Sidebar from '../components/Sidebar.vue'
import Header from '../components/Header.vue'
import Footer from '../components/Footer.vue'
</script>

<template>
    <div id="mainBody" class="flex min-h-screen bg-[#F0F1F1]">
        <!-- Loader -->
        <!-- <div id="loader" class="fixed bg-[#ffffffa0] inset-0 flex items-center justify-center z-50">
      <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div> -->

        <!-- Main Container -->
        <div class="main_container flex w-full">
            <Sidebar />

            <!-- Content Area -->
            <div id="contentArea" class="left_side_content ml-[300px] w-full flex flex-col">
                <Header />
                <slot />
                <!-- <Footer /> -->
            </div>
        </div>
    </div>
</template>
