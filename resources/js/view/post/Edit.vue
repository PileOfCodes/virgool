<template>
    <div :class="[addImage ? 'fixed top-0 left-0 right-0 bg-black opacity-80 w-full h-screen z-10' : 'hidden']">
        <div class="tiptapModal flex flex-col justify-center items-center bg-white">
            <span>عکس خود را آپلود کنید</span>
            <input type="file" ref="postImage" class="mt-4">
            <div class="flex items-center justify-center mt-4">
              <button @click="tiptapModal" class="border-sky-600 border-2 py-1 px-2 rounded-md hover:bg-sky-600 hover:text-white">بستن</button>
              <button @click.prevent="addSelectedImage" class="border-sky-600 border-2 rounded-md py-1 px-2 mr-4 hover:bg-sky-600 hover:text-white">افزودن تصویر</button>
            </div>
        </div>
    </div>
    <input type="text" placeholder="عنوان متن..." v-model="title">
    <div v-if="editor" class="z-1">
    <bubble-menu
      class="bubble-menu"
      :tippy-options="{ duration: 100 }"
      :editor="editor"
    >
      <button @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
        Bold
      </button>
      <button @click="editor.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
        Italic
      </button>
      <button @click="editor.chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
        Strike
      </button>
    </bubble-menu>

    <floating-menu
      class="floating-menu"
      :tippy-options="{ duration: 100 }"
      :editor="editor"
    >
      <button @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">
        H1
      </button>
      <button @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">
        H2
      </button>
      <button @click="editor.chain().focus().toggleBulletList().run()" :class="{ 'is-active': editor.isActive('bulletList') }">
        Bullet List
      </button>
      <button @click="tiptapModal">image</button>
    </floating-menu>
  </div>
  
  <editor-content :editor="editor"  class="border-2 py-4 px-2" />
  <button v-if="showSavingPost" @click.prevent="updatePost" class="bg-sky-600 py-1 px-2 mt-4 rounded-md text-white">ویرایش پیش نویس</button>
  <div v-if="showNotification.success">
    <div class="draftNotification">پیش نویس شما ویرایش شد</div>
  </div>
  <div v-if="showNotification.error">
    <div class="errorNotification">پیش نویس شما ویرایش نشد<span class="mr-12 cursor-pointer" @click="showNotification.error = false">بستن</span></div>
  </div>
</template>
  
<script>
  import { debounce } from 'lodash'
  import store from '../../store/index.js'
  import StarterKit from '@tiptap/starter-kit'
  import Image from '@tiptap/extension-image'
  import Heading from '@tiptap/extension-heading'
  import Placeholder from '@tiptap/extension-placeholder'
  import {
    BubbleMenu,
    Editor,
    EditorContent,
    FloatingMenu,
  } from '@tiptap/vue-3'
  
  export default {
    components: {
      EditorContent,
      BubbleMenu,
      FloatingMenu
    },
  
    props: {
      modelValue: {
        type: String,
        default: '',
      },
    },
  
    emits: ['update:modelValue'],
  
    data() {
      return {
        editor: null,
        addImage: false,
        selectedImage: null,
        imageName: null,
        imagePath: null,
        title: "",
        showNotification: {
          success: false,
          error: false
        }
      }
    },

    methods: {
        tiptapModal() {
            this.addImage = !this.addImage;
        }, 
        async addSelectedImage() {  
          const data = new FormData();
          data.append('file', this.$refs.postImage.files[0]);
          const response = await axios.post('/api/upload-post-image', data, {
            header: {
              'Content-Type': 'multipart/form-data'
            }
          })
          this.imageName = response.data.data
          this.imagePath = '/storage/images/posts/' + this.imageName;
          this.editor.chain().focus().setImage({ src: this.imagePath}).run()
          this.addImage = !this.addImage;
        },
        updatePost() {
          store.dispatch('updatePost', {
          title : this.title,
          content : this.modelValue,
          slug: this.$route.params.slug
        })
        .then(({data}) => {
            this.$router.push(`/posts/${this.$route.params.slug}/save`)
          })
        },
        debounce: debounce( function() {
          this.showNotification.success = false;
          store.dispatch('updatePost', { 
              title : this.title,
              content : this.modelValue,
              slug: this.$route.params.slug
          })
        }, 2000)
    },
  
    watch: {
      modelValue(value) {
        const isSame = this.editor.getHTML() === value
        if (isSame) {
          return
        }
        this.editor.commands.setContent(value, false)
      },
    },

    computed: {
      showSavingPost() {
        return this.title && this.modelValue
      }
    },

    created() {
        this.showNotification.success = true;
        axios.get(`/api/posts/${this.$route.params.slug}/edit`)
        .then(({data}) => {
          this.title = data.title,
          this.editor.commands.setContent(data.content)
          console.log(data.content)
        })
        .catch(({response}) => {
          if(response.status === 404)
          {
            this.$router.push({name: 'not-found'})
          }
          if(response.status === 403)
          {
            this.$router.push({name: 'access-denied'})
          }
        })
    },
  
    mounted() {
      this.editor = new Editor({
        extensions: [
          StarterKit,
          Placeholder.configure({
          placeholder: 'محتوای خود را وارد کنید...',
        }),
        Heading.configure({
          levels: [1, 2, 3],
          HTMLAttributes: {
            class: 'my-custom-class',
        },
        }),
        Image,
        ],
        content: this.modelValue,
        onUpdate: () => {
          this.$emit('update:modelValue', this.editor.getHTML()) 
        }
      })
    },

    updated() {
      this.debounce()
    }, 
  
    beforeUnmount() {
      this.editor.destroy()
    },
  }
</script>

<style lang="scss">
    .ProseMirror {
  > * + * {
    margin-top: 0.75em;
  }
  ul {
    list-style-type: disc;
  }
  ol {
      list-style-type: decimal;
    }
    ul,
  ol {
    padding: 0 1rem;
  }

  h1 {
    font-size: 2em;
    height: 32px;
    
  }
  h2 {
    font-size: 1.5em;
    height: 24px;
    
  }
  h3 {
    font-size: 1.17em;
    height: 18.72px;
    
  }
  h1,
  h2,
  h3{
    line-height: 1.1;
    font-weight: bolder;
  }

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }

  pre {
    background: #0D0D0D;
    color: #FFF;
    font-family: 'JetBrainsMono', monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0D0D0D, 0.1);
  }

  hr {
    border: none;
    border-top: 2px solid rgba(#0D0D0D, 0.1);
    margin: 2rem 0;
  }
}

    /* Placeholder (at the top) */
.ProseMirror p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: right;
  color: #adb5bd;
  pointer-events: none;
  height: 0;
}
    

img {
    max-width: 100%;
    height: auto;

    &.ProseMirror-selectednode {
    outline: 3px solid #68CEF8;
    }
}

.bubble-menu {
  display: flex;
  background-color: #0D0D0D;
  padding: 0.2rem;
  border-radius: 0.5rem;

  button {
    border: none;
    background: none;
    color: #FFF;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0 0.2rem;
    opacity: 0.6;

    &:hover,
    &.is-active {
      opacity: 1;
    }
  }
}
    
.floating-menu {
    display: flex;
    background-color: #0D0D0D10;
    padding: 0.3rem;
    border-radius: 0.5rem;

    button {
    border: none;
    background: none;
    font-size: 0.85rem;
    font-weight: 500;
    padding: 0 0.2rem;
    opacity: 0.6;

    &:hover,
    &.is-active {
        opacity: 1;
    }
    }
}
.tiptapModal {
    width: 340px;
    padding: 20px 10px;
    position: absolute;
    top: 50%;
    right: 50%;
    transform: translate(50%,-50%);
}
.draftNotification {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 40px;
  background-color: #0D0D0D;
  color: white;
  padding: 10px 40px;
  border-radius: 7px;
}
.errorNotification {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: 40px;
  background-color: rgb(255, 50, 50);
  color: white;
  padding: 10px 40px;
  border-radius: 7px;
}
</style>