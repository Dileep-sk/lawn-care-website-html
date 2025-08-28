<script setup>
import { ref, onMounted, computed } from 'vue'
import AuthLayout from '../../layouts/AuthLayout.vue'
import left_arrow from '@/assets/icons/left-arrow.svg'
import { useRoute } from 'vue-router'
import { STATUS_OPTIONS } from '@/constants/jobStatus'
import DetailItem from '../../components/DetailItem.vue'
import { useJobs } from '@/composables/useJobs'

const route = useRoute()
const jobId = route.params.id || 1

const { getJobDetails } = useJobs()

const jobs = ref(null)
const loading = ref(false)
const error = ref(null)

const jobsStatus = computed(() => {
    if (!jobs.value) return { label: '', class: '' }
    return STATUS_OPTIONS.find(s => s.value == jobs.value.status) || { label: 'Unknown', class: '' }
})

const jobImages = computed(() => {
    if (!jobs.value || !jobs.value.images) return []
    return jobs.value.images.map(img => img.full_url || img.image)
})

onMounted(async () => {
    loading.value = true
    try {
        jobs.value = await getJobDetails(jobId)
    } catch (err) {
        error.value = err.message || 'Failed to load jobs details'
    } finally {
        loading.value = false
    }
})

function openImageInNewTab(imgSrc) {
    window.open(imgSrc, '_blank')
}
</script>

<template>
    <AuthLayout>
        <div class="inner_contant mt-[20px] w-[100%]">
            <div class="content_container w-[100%] h-[100%] bg-white rounded-[10px]">
                <div id="view_jobs" class="w-full min-h-screen bg-white p-[15px]">
                    <div class="flex justify-between items-center mb-[30px]">
                        <h2 class="font-bold text-[24px]">jobs Detail</h2>
                        <div class="flex justify-end mb-6">
                            <router-link :to="{ name: 'jobs' }"
                                class="h-[45px] flex px-[20px] items-center gap-[10px] text-white text-[15px] font-medium rounded-[5px] bg-black">
                                <img :src="left_arrow" class="w-[20px]" alt="Back" />
                                Back
                            </router-link>
                        </div>
                    </div>

                    <div class="detail_box bg-[rgba(56,92,76,0.04)] p-[30px] mx-auto">
                        <template v-if="loading">
                            <p>Loading jobs details...</p>
                        </template>
                        <template v-else-if="error">
                            <p class="text-red-600">{{ error }}</p>
                        </template>

                        <template v-else-if="jobs">
                            <ul>
                                <DetailItem label="jobs No" :value="jobs.customer_name" />
                                <DetailItem label="Customer Name" :value="jobs.design_no" />
                                <DetailItem label="Date" :value="jobs.quantity" />
                                <DetailItem label="Broker Name" :value="jobs.jobs_no" />
                                <DetailItem label="Status" :value="jobsStatus.label" :extraClass="jobsStatus.class" />
                                <DetailItem label="Matching 1" :value="jobs.matching_1" />
                                <DetailItem label="Matching 2" :value="jobs.matching_2" />
                                <DetailItem label="Matching 3" :value="jobs.matching_3" />
                                <DetailItem label="Matching 4" :value="jobs.matching_4" />
                                <DetailItem label="Matching 5" :value="jobs.matching_5" />
                                <DetailItem label="Matching 6" :value="jobs.matching_6" />
                                <DetailItem label="Matching 7" :value="jobs.matching_7" />
                                <DetailItem label="Matching 8" :value="jobs.matching_8" />

                                <DetailItem label="Message" :value="jobs.message" />
                            </ul>

                            <div v-if="jobImages.length" class="mt-6">
                                <h3 class="font-semibold mb-3">Job Images</h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div v-for="(imgSrc, idx) in jobImages" :key="idx"
                                        class="image-wrapper cursor-pointer" @click="() => openImageInNewTab(imgSrc)">
                                        <img :src="imgSrc" :alt="'Job Image ' + (idx + 1)"
                                            class="w-full h-auto rounded border" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <p>No jobs data found.</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>
