<template>
    <table>
        <tr>
            <th v-for="header in headers">{{ header }}</th>
        </tr>
        <TableRow v-for="row in table" :item="row" />
    </table>
</template>

<script setup>
    import TableRow from './TableRow.vue'
    defineProps({
        headers: Array,
        table: Array,
    });
</script>

<style lang="scss">
    table
    {
        border: solid thin black;
        td
        {
            border: solid thin black;
            text-align: left;
        }
        th
        {
            @extend td;
            font-weight: bold;
        }
    }
</style>