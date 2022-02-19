<template>
    <div class="wrapper">
        <Header />
        <notify v-if="$page.props.flash" :key="$page.props.flash.id"/>
        
        <slot></slot>
    </div>
</template>
<script>
    import Header from '@/Layouts/Components/Header.vue'
    
    import Notify from '@/Layouts/Components/Toast.vue'

    export default {
        components: {
            Header,
            
        },
    }

</script>
<style scope>
 body{
    background-color: rgb(88 120 174 / 40%);
} 
</style>
