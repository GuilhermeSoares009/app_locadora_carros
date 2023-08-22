<template>
    <div :class="estilo" role="alert">
        A simple success alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
    </div>

</template>

<script>
    export default {
        props: ['tipo'],
        computed: {
            estilo(){
                return 'alert alert-'+this.tipo;
            }
        }
    }
</script>
