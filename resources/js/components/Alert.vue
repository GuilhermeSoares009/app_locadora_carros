<template>
    <div :class="estilo" role="alert">
       {{titulo}}
       <hr>
       <p> {{detalhes.mensagem}} </p>
       <span v-if="detalhes.data.id">  {{'ID do registro: ' + detalhes.data.id}} </span>
       <ul v-if="detalhes.data">
            <li v-for="e,key in detalhes.dados" :key="key">{{ e[0] }}</li>
       </ul>
    </div>

</template>

<script>
    export default {
        props: ['tipo','titulo', 'detalhes'],
        computed: {
            estilo(){
                return 'alert alert-'+this.tipo;
            }
        }
    }
</script>
