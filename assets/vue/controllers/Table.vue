<template>
    <ContextMenu ref="ctxmenu" @option-clicked="option"/>
    <table oncontextmenu="return false;">
        <tr>
            <th v-for="header in headers">{{ header }}</th>
        </tr>
        <TableRow v-for="row in table" :item="row" :ctxm="ctxmenu" @menu="ctx"/>
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

    function ctx(item, event, gg)
    {
        ctxmenu.value.show(item, event, gg)
    }

    function option(number, item, gg)
    {
        switch (number)
        {
            case 0:
                gg.value.setAttribute('class', 'data-row added')
                break
            case 1:
                gg.value.setAttribute('class', 'data-row deleted')
                break
            case 2:
                gg.value.setAttribute('class', 'data-row updated')
                break
        }
    }

</script>

<style lang="scss">
    table
    {
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
