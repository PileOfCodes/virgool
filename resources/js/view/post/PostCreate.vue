<template>
  <div class="mx-48 mt-12 mb-4 ">
      <Editor v-model="content" />
  </div>
</template>

<script>
import Editor from '../../components/Editor.vue'

export default {
  name: 'PostCreate',
  components: {
    Editor,
  },
  data: () => ({
    content: ''
  })
}
</script>

<style lang="scss">
/* Basic editor styles */
.ProseMirror {
  > * + * {
    margin-top: 0.75em;
  }

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }
}
</style>