<template>
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col" v-for="t,key in titulos" :key="key" class="text-uppercase">
          {{t}}
        </th>
      </tr>
    </thead>
    <tbody>

      <tr v-for="obj in dados" :key="obj.id">
        <td v-if="titulos.includes(chave)" v-for="valor,chave in obj" :key="chave">
            <span v-if="chave == 'imagem'">
                <img :src="'/storage/'+valor" width="30" height="30">
            </span>
            <span v-else>
              {{ valor }}
            </span>
        </td>
      </tr>




<!--       <tr v-for="m in dados" :key="m.id">
        <th scope="row">{{m.id}}</th>
        <td>{{m.nome}}</td>
        <td> <img width="100px"  :src="'/storage/'+m.imagem"></td>
      </tr> -->
    </tbody>
  </table>
</template>

<script>
export default {
  props: ['dados', 'titulos']
};
</script>
