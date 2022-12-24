<template>
    <ContextMenu ref="ctxmenu" @option-clicked="option"/>
    <table oncontextmenu="return false;">
        <tr>
            <td id="button-container">
                <button id="send-button" :class="buttonClass">Отправить</button>
            </td>
        </tr>
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
        link: String,
    });

    const ctxmenu = ref(null)
    const buttonClass = ref('')

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
        #button-container
        {
            border: none;
        }

        th
        {
            @extend td;
            font-weight: bold;
        }
    }
    #send-button
    {
        border: none;
        background-color: rgb(173, 173, 173);
        color: rgb(0, 0, 0);
        text-decoration: none;
        text-align: left;
        padding: 5px;
        border-radius: 0;
        width: 50%;
        height: 100%;
        min-width: 100px;
        &.active
        {
            background-color: rgb(0, 255, 0);
            &:hover
            {
                background-color: rgb(0, 205, 0);
            }
        }
    }
</style>
