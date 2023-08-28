<template>
    <div class="mx-48 mt-12 mb-4 ">
        <Edit v-model="content" />
    </div>
</template>
  
  <script>
  import Edit from './Edit.vue'
  
  export default {
    name: 'PostCreate',
    components: {
      Edit,
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