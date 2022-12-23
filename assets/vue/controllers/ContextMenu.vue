<template>
    <ul id="context-menu" :class="cls" ref="menu">
        <li>
            <button @click="add">Добавить</button>
        </li>
        <li>
            <button @click="del">Удалить</button>
        </li>
        <li>
            <button @click="upd">Изменить</button>
        </li>
        <li>
            <button @click="clo">Закрыть</button>
        </li>
        <li v-if="false">
            <button @click="cnl">Отменить</button>
        </li>
    </ul>
</template>

<script setup>
    import { ref } from 'vue'

    const cls = ref('')
    const menu = ref(null)

    defineExpose({
        cls,
        menu,
    })

    function hide()
    {
        cls.value = ''
    }

    // function add(event)
    // {
    //     hide()
    // }

    // function del(event)
    // {
    //     props.item.value.cls = 'data-row deleted'
    //     cancelable.value = true;
    //     hide()
    // }

    // function upd(event)
    // {
    //     props.item.value.cls = 'data-row changed'
    //     cancelable.value = true;
    //     hide()
    // }

    function clo(event)
    {
        hide()
    }

    // function cnl(event)
    // {
    //     id.value.class = 'data-row'
    //     cancelable.value = false;
    //     hide()
    // }


</script>

<style lang="scss">
    #context-menu
    {
        position: fixed;
        display: none;
        &.active
        {
            display: block;
        }
        li
        {
            list-style-type: none;
        }
        button
        {
            border: none;
            background-color: rgb(94, 94, 94);
            color: white;
            text-decoration: none;
            text-align: left;
            padding: 5px;
            border-radius: 0;
            width: 100px;
            &:hover
            {
                background-color: rgb(158, 158, 158);
            }
        }
    }
</style>