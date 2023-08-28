<template>
    <div>
        <ul class="flex py-2 pl-2">
          <li v-for="(crumb, ci) in crumbs" :key="ci" class="text-sm px-1">
              <router-link :to="crumb.to" :class="[islast(ci) ? 'disabled-link' : 'text-sky-600'] ">
                  {{ crumb.title }} {{ crumb.divider }}
              </router-link>
          </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'BreadCrumbs',
    props: {
        crumbs: {
            type: Array,
            required: true
        }
    },
    methods: {
        islast(index)
        {
            return index === this.crumbs.length - 1;
        }
    }
}
</script>

<style>
    .disabled-link{
        pointer-events: none;        
        text-decoration: none;
        color: grey;
    }
</style>