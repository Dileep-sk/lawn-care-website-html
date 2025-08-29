<script>
export default {
    name: 'DashboardCard',
    props: {
        title: String,
        value: [String, Number],
        bgColor: String,
        textColor: String
    }
};
</script>

<template>
    <div class="h-[calc(25vh_-90px)] rounded-[10px] p-[10px]" :class="bgColor">
        <h6 class="text-[15px] font-[400] text-[#000]">{{ title }}</h6>
        <h1 class="text-[50px] font-[800] text-center mt-[15px]" :class="textColor">
            {{ value }}
        </h1>
    </div>
</template>
