<template>
    <div class="flex justify-center items-center pt-4 text-sm" v-if="user && user.isVerified == 1">
      <span class="ml-4"> شما هنوز ایمیل خود را تایید نکردید</span>
      <span class="text-sky-500" v-if="sended"> ایمیل ارسال شد!</span>
      <button @click="resendVerify" v-if="!sended" class="border-2 border-sky-500 px-2 py-1 rounded-xl hover:text-sky-500"> ارسال مجدد ایمیل</button>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'IsVerified',
    data: () => ({
        sended: false
    }),
    methods: {
        resendVerify() {
            axios.post('api/email/resend')
            .then(() => this.sended = true)
        },
    },
    computed: {
        ...mapState({
            user: state => state.user.user
        })
    }
}
</script>

<style>

</style>