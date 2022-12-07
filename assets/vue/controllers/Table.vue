<template>
    <div v-for="subarr in arr" class="wrap">
        <div v-for="item in subarr" class="gg">
            {{ item }}
        </div>
    </div>
</template>

<script setup>
    defineProps({
        arr: Array
    });
</script>

<style>
    .gg
    {
        background: palevioletred;
    }
    .gg:hover
    {
        background: palegreen;
    }
    .wrap
    {
        border: solid 1px black;
        margin: 3px;
    }
</style>