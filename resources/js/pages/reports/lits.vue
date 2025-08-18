<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

function goToDashboard() {
    router.push('/dashboard')
}
</script>

<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-white text-black gap-8">
        <h1 class="text-6xl font-bold">Coming Soon</h1>
        <button @click="goToDashboard" class="px-6 py-3 bg-[#ff4757] rounded-md text-white hover:bg-[#ff4757] transition">
            Go to Dashboard
        </button>
    </div>
</template>
