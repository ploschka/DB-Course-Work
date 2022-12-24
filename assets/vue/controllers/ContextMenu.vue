<template>
    <ul id="context-menu" :class="cls" ref="menu">
        <li>
            <button @click="optionClicked(0)">Добавить</button>
        </li>
        <li>
            <button @click="optionClicked(1)">Удалить</button>
        </li>
        <li>
            <button @click="optionClicked(2)">Изменить</button>
        </li>
        <li>
            <button @click="hide">Закрыть</button>
        </li>
    </ul>
</template>

<script setup>
    import { ref } from 'vue'

    const cls = ref('')
    const menu = ref(null)
    const item = ref(null)
    const mygg = ref(null)

    defineExpose({
        show,
    })
    
    const emit = defineEmits(['optionClicked'])

    function show(itm, event, gg)
    {
        cls.value = 'active'
        menu.value.style.left = `${event.clientX + 10}px`
        menu.value.style.top = `${event.clientY}px`
        item.value = itm
        mygg.value = gg.value
    }

    function hide()
    {
        cls.value = ''
    }

    function optionClicked(number)
    {
        emit('optionClicked', number, item, mygg)
        hide()
    }

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