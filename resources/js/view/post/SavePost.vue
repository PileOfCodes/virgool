<template>
    <div class="flex justify-center my-4">
        <span v-if="categoriesErr" class="text-red-500 border-2 border-red-500 p-4 text-center">{{ " "+ categoriesErr+" " }}</span>
        <span v-if="imageErr" class="text-red-500 border-2 border-red-500 p-4 text-center mr-4">{{" "+imageErr+" "}}</span> 
    </div>
    <div class="flex  justify-around mt-12">
        <div>
            <p>عکس خود را آپلود کنید</p>
            <input type="file" @change="uploadImage">
            <img :src="form.image" v-if="form.image"  alt="post image" class="w-52 h-52">
            <p class="mt-4" title="عنوان">{{ form.title }}</p>
        </div>
        <div>
            <p class="mb-4">برای شناسایی بهتر نوشته تان دسته بندی را وارد نمایید</p>                           
            <v-row>
                <v-col cols="12">
                    <v-combobox v-model="form.categories" label="دسته بندی" multiple
                    variant="outlined" chips
                    hint="حداکثر 5 دسته بندی را وارد کنید"
                    persistent-hint
                    ></v-combobox>
                </v-col>
                <button @click="sendForm" class="rounded-md py-1 px-4 ml-32 float-left bg-sky-600 text-white">ذخیره پست</button>
            </v-row>   
        </div>
    </div>
    <textarea cols="60" rows="10" class="border-2 border-slate-400 p-2 rounded-md" 
    placeholder="توضیحات نوشته" v-model="form.description"></textarea>
</template>

<script>
    import store from '../../store/index.js'
    export default {
    name: 'SavePost',
    data: () => ({
        draft: {},
        file: null,
        imageErr: null,
        categoriesErr: null,
        form: {
            title: null,
            content: null,
            description: null,
            image: null,
            image_name: null,
            categories: []
        }
    }),
    watch: {
        'form.categories'(value)
        {
            if(value.length > 5)
            {
                this.$nextTick(() => this.form.categories.pop())
            }
        }
    },
    methods: {
        uploadImage(event) {
            this.file = event.target.files[0];
            this.form.image_name = event.target.files[0].name;
            const reader = new FileReader();
            reader.readAsDataURL(this.file)
            reader.onload = (image) => {
                this.form.image = image.target.result
        }
    },
    sendForm() {
        this.imageErr = ""
        this.categoriesErr = ""
        store.dispatch('savePost', this.form)
        .then(({data}) => {
            this.$router.push({name : 'post-show', params: { slug: data.data.slug}})
        })
        .catch(({response}) => {
            if(response.data.errors.image)
            {
                this.imageErr = response.data.errors.image[0]
            }
            if(response.data.errors.categories)
            {
                this.categoriesErr = response.data.errors.categories[0]
            }
        })
    }
    },

    async created() {
        await store.dispatch('getDraft', this.$route.params.link)
        .then((data) => {
            this.form.title = data.title
            this.form.content = data.content
        })
    }
}
</script>

<style>

</style>