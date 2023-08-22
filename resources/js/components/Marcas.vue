<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <!-- Início card de busca -->
        <card-component titulo="Busca de Marcas">
          <template v-slot:conteudo>
            <div class="form-row">
              <div class="col mb-3">
                <input-container-component
                  titulo="ID"
                  id="inputId"
                  id-help="inputIdHelp"
                  texto-ajuda="Opcional. Informe o ID do registro"
                >
                  <input
                    type="number"
                    class="form-control"
                    id="inputId"
                    aria-describedby="inputIdHelp"
                    placeholder="ID"
                  />
                </input-container-component>
              </div>

              <div class="col mb-3">
                <input-container-component
                  titulo="Nome"
                  id="inputNome"
                  id-help="inputNomeHelp"
                  texto-ajuda="Opcional. Informe o nome da marca"
                >
                  <input
                    type="text"
                    class="form-control"
                    id="inputNome"
                    aria-describedby="inputNomeHelp"
                    placeholder="Nome"
                  />
                </input-container-component>
              </div>
            </div>
          </template>

          <template v-slot:rodape>
            <button type="submit" class="btn btn-primary btn-sm float-right">
              Pesquisar
            </button>
          </template>
        </card-component>
        <!-- Fim card de busca -->

        <!-- Início card de listagem de marcas -->
        <card-component titulo="Relação de Marcas">
          <template v-slot:conteudo>
            <table-component />
          </template>
          <template v-slot:rodape>
            <button
              type="button"
              class="btn btn-primary btn-sm float-right"
              data-toggle="modal"
              data-target="#modalMarca"
            >
              Adicionar
            </button>
          </template>
        </card-component>
        <!-- fim card de listagem de marcas -->
      </div>
    </div>

    <modal-component id="modalMarca" titulo="Adicionar Marca">
        <template v-slot:conteudo>
            <div class="form-group">
                <input-container-component 
                titulo="Nome da Marca" 
                id="novoNome" 
                id-help="novoNomeHelp" 
                texto-ajuda="Informe o nome da marca">
                    <input
                    type="text"
                    class="form-control"
                    id="novoNome"
                    aria-describedby="novoNomeHelp"
                    placeholder="Nome da Marca"
                    v-model="nomeMarca"/>
                </input-container-component>
            </div>
            <div class="form-group">
                <input-container-component 
                titulo="Imagem" id="novoImagem" id-help="novoImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                    <input
                    type="file"
                    class="form-control-file"
                    id="novoImagem"
                    aria-describedby="novoImagemHelp"
                    placeholder="Selecione uma imagem no formato PNG"
                    @change="carregarImagem($event)"/>
                </input-container-component>
            </div>
        </template>

        <template v-slot:rodape>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                Fechar
            </button>
          <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
  </div>
</template>

<script>

export default {
    computed: {
        token(){
            let token = document.cookie.split(';').find(indice => {
              return indice.includes('token=');
            });
            token = token.split('=')[1];
            token = 'Bearer ' + token;

            return token;
        }
    },
    data() {
        return {
            urlBase: 'http://127.0.0.1:8000/api/v1/marca',
            nomeMarca: '',
            arquivoImagem: []
        }
    },
    methods: {
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar()
        {
            let formData = new FormData();
            formData.append('nome', this.nomeMarca);
            formData.append('imagem', this.arquivoImagem[0]);
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorizathion': this.token
                }
            };

            axios.post(this.urlBase, formData, config)
                .then( response => {
                    //console.log(response)
                })
                .catch(errors => {
                    console.log(errors)
                });
        }
    }
};
</script>
