<template>
    <tr :class="cls" @contextmenu="ctx" ref="gg">
        <td v-for="data in props.item">{{ data }}</td>
    </tr>
</template>

<script setup>
    import { ref } from 'vue'

    const props = defineProps(['item', 'ctxm'])
    const emit = defineEmits(['menu'])

    const cls = ref('data-row')
    const gg = ref(null)

    function ctx(event)
    {
        emit('menu', props.item, event, gg)
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

    .updated
    {
        background-color: palegoldenrod;
    }
</style>
