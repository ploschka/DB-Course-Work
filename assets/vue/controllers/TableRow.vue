<template>
    <tr class="data-row">
        <td v-for="data in item">{{ data }}</td>
    </tr>
</template>

<script setup>
    defineProps({
        item: Array,
    })
</script>

<style lang="scss">
    .data-row:hover
    {
        background-color: paleturquoise;
    }
    .added
    {
        background-color: palegreen;
    }
    .deleted
    {
        background-color: palevioletred;
    }
    .changed
    {
        background-color: palegoldenrod;
    }
</style>
