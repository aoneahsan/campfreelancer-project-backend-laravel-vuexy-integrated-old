<template>
    <div id="list-demo">
        <list-basic></list-basic>
        <list-header></list-header>
        <list-icon></list-icon>
        <list-content></list-content>
        <list-avatar></list-avatar>
    </div>
</template>

<script>
import ListBasic from './ListBasic.vue'
import ListHeader from './ListHeader.vue'
import ListIcon from './ListIcon.vue'
import ListContent from './ListContent.vue'
import ListAvatar from './ListAvatar.vue'

export default{
  components: {
    ListBasic,
    ListHeader,
    ListIcon,
    ListContent,
    ListAvatar
  }
}
</script>
