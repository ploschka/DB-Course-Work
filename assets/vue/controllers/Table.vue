<template>
    <ContextMenu ref="ctxmenu"/>
    <table oncontextmenu="return false;">
        <tr>
            <th v-for="header in headers">{{ header }}</th>
        </tr>
        <TableRow v-for="row in table" :item="row" :ctxm="ctxmenu"/>
    </table>
</template>

<script setup>
    import TableRow from './TableRow.vue'
    import ContextMenu from './ContextMenu.vue'
    import { ref } from 'vue'

    defineProps({
        headers: Array,
        table: Array,
    });

    const ctxmenu = ref(null)

</script>

<style lang="scss">
    table
    {
        border: solid thin black;
        margin: 5px;
        td
        {
            border: solid thin black;
            text-align: left;
            padding: 3px;
        }

        th
        {
            @extend td;
            font-weight: bold;
        }
    }
</style>
