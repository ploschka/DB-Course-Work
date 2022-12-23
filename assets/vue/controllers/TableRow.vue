<template>
    <tr :class="cls" @contextmenu="ctx">
        <td v-for="data in props.item">{{ data }}</td>
    </tr>
</template>

<script setup>
    import { ref } from 'vue'

    const props = defineProps(['item', 'ctxm'])

    const cls = ref('data-row')

    function ctx(event)
    {
        props.ctxm.cls = 'active'
        props.ctxm.menu.style.top = `${event.clientY}px`
        props.ctxm.menu.style.left = `${event.clientX + 10}px`
    }
</script>

<style lang="scss">
    .data-row
    {
        &:hover, &.active
        {
            background-color: paleturquoise;
        }
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
