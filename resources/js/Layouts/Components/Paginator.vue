<template>
    <div style="margin-bottom: -30px;">
        <p class="text-sm text-gray-700 leading-5">
            Affichage de
            <span class="font-medium" v-text="firstItem"></span>
            à
            <span class="font-medium" v-text="lastItem"></span>
            sur
            <span class="font-medium" v-text="total"></span>
            résultats
        </p>
    </div>
    <ul v-if="paginator!==undefined"
    class="pagination pagination-sm m-0 float-right"
    role="navigation">
    <template v-if="onFirstPage">
       <li class="page-item"><a class="page-link" href="javascript:void(0);">&laquo;</a></li>
   </template>
   <template v-else>
    <li class="page-item"><Link class="page-link" :href="previousPageUrl">&laquo;</Link></li>
</template>

<template v-for="link in paginator.links">
   <li class="page-item">
    <Link 
    class="page-link" 
    v-if="!isFirstOrLastOrDots(index,paginator.links.length,link.label)"
    :class="{'bg-sam' : link.active===true}"
    :href="link.url">
    <template v-if="link.label.indexOf('Previous') != -1"> <i class="fas fa-chevron-left mr-1"></i>    Prev </template>
    <template v-if="link.label.indexOf('Next') != -1" > Next <i class="ml-1 fas fa-chevron-right"></i> </template>
    <template v-if="link.label.indexOf('Previous') == -1 && link.label.indexOf('Next') == -1" > {{ link.label }} </template>
</Link>
<span v-else-if="link.label==='...'" aria-disabled="true"
class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">
{{ link.label }}
</span>
</li>
</template>

<template  v-if="hasMorePages">
    <li class="page-item"><Link class="page-link" :href="nextPageUrl">&raquo;</Link></li>
</template>
<template v-else>
    <li class="page-item mt-1"><a class="page-a" href="javascript:void(0);">&raquo;</a></li>
</template>
<template>

</template>
</ul>

</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3'

    export default {
        name: "Paginator",
        components: {Link},
        props: {
            paginator: {
                current_page: Number,
                data: Array,
                first_page_url: String,
                from: Number,
                last_page: Number,
                last_page_url: String,
                links: Array,
                next_page_url: String,
                path: String,
                per_page: Number,
                prev_page_url: String,
                to: Number,
                total: Number,
            }
        },
        methods: {
            isFirstOrLastOrDots(index,links_length,label) {
                return index===0 || index===links_length-1 || label.includes('...')
            },
        },

        computed: {
            onFirstPage() {
                return this.paginator.current_page === 1;
            },

            hasMorePages() {
                return this.paginator.current_page < this.paginator.last_page;
            },

            nextPageUrl() {
                return this.paginator.next_page_url;
            },

            previousPageUrl() {
                return this.paginator.prev_page_url;
            },

            firstItem() {
                return this.paginator.from;
            },

            lastItem() {
                return this.paginator.to;
            },

            total() {
                return this.paginator.total;
            },
        }
    }
</script>