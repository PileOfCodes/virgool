<template>
    <div class="flex justify-center my-4">
        <span v-if="categoriesErr" class="text-red-500 border-2 border-red-500 p-4 text-center">{{ " "+ categoriesErr+" " }}</span>
    </div>
    <div class="flex justify-around ">
        <div class="mt-12">
            <p>عکس خود را آپلود کنید</p>
            <input type="file" @change="uploadImage">
            <img v :src="form.is_image != null ? form.is_image : form.image" v-if="form.image" alt="post image" class="w-52 h-52">
        </div>
        <p>{{ form.title }}</p>
        <div>
            <p>برای شناسایی بهتر نوشته تان دسته بندی را وارد نمایید</p>
            <v-row>
                <v-col cols="12">
                    <v-combobox v-model="form.categories_title" label="دسته بندی" multiple
                    variant="outlined" chips
                    hint="حداکثر 5 دسته بندی را وارد کنید"
                    persistent-hint
                    ></v-combobox>
                </v-col>
                <button @click="sendForm" class="rounded-md py-1 px-4 ml-32 float-left bg-sky-600 text-white">ذخیره پست</button>
            </v-row>  
        </div>
    </div>
    <textarea cols="60" rows="10" placeholder="توضیحات نوشته" v-model="form.description"></textarea>
</template>

<script>
    import store from '../../store/index.js'
    export default {
    name: 'updatePost',
    data: () => ({
        draft: {},
        file: null,
        categoriesErr: null,
        form: {
            title: null,
            content: null,
            description: null,
            image: null,
            image_name: null,
            categories: [],
        }
    }),
    methods: {
        uploadImage(event) {
            this.form.is_image = null
            this.file = event.target.files[0];
            this.form.image_name = event.target.files[0].name;
            const reader = new FileReader();
            reader.readAsDataURL(this.file)
            reader.onload = (image) => {
                this.form.image = image.target.result 
        }
    },
    watch: {
    'form.categories'(value)
    {
        if(value.length > 5)
        {
            this.$nextTick(() => this.form.categories.pop())
        }
    }
    },
    sendForm() {
        this.categoriesErr = ""
        store.dispatch('editPost', this.form)
        .then(({data}) => {
            this.$router.push({name : 'post-show', params: { slug: this.$route.params.slug}})
        })
        .catch(({response}) => {
            if(response.data.errors.categories)
            {
                this.categoriesErr = response.data.errors.categories[0]
            }
        })
    }
    },

    async created() {
        let {data} = await axios.get(`/api/posts/${this.$route.params.slug}/edit`)
        this.form = data       
    }
}
</script>

<style>

</style>