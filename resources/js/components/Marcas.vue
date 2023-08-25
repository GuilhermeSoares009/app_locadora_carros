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
                    v-model="busca.id"
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
                    v-model="busca.nome"
                  />
                </input-container-component>
              </div>
            </div>
          </template>

          <template v-slot:rodape>
            <button type="submit" class="btn btn-primary btn-sm float-right" @click="pesquisar()">
              Pesquisar
            </button>
          </template>
        </card-component>
        <!-- Fim card de busca -->

        <!-- Início card de listagem de marcas -->
        <card-component titulo="Relação de Marcas">
          <template v-slot:conteudo>
            <table-component 
              :dados="marcas.data" 
              :visualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaVisualizar'}"
              :atualizar="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaAtualizar'}"
              :remover="{visivel: true, dataToggle: 'modal', dataTarget: '#modalMarcaRemover'}"
              :titulos="{
                id: {titulo:'ID', tipo: 'texto'},
                nome: {titulo:'Nome', tipo: 'texto'},
                imagem: {titulo:'Imagem', tipo: 'imagem'},
                created_at: {titulo:'Data de criação', tipo: 'data'}
              }"
            />
          </template>
          <template v-slot:rodape>
             <div class="row">
                <div class="col-10">
                  <paginate-component>
                      <li 
                      v-for="l, key in marcas.links" 
                      :key="key" 
                      :class="l.active ? 'page-item active': 'page-item'" 
                      @click="paginacao(l)">
                        <a class="page-link"
                         v-html="l.label"></a>
                      </li>
                  </paginate-component>
                </div>
                <div class="col">
                  <button
                    type="button"
                    class="btn btn-primary btn-sm float-right"
                    data-toggle="modal"
                    data-target="#modalMarca"                >
                    Adicionar
                  </button>
                </div>
             </div>
          </template>
        </card-component>
        <!-- fim card de listagem de marcas -->
      </div>
    </div>

    <!-- início do modal de inclusão de marca -->
    <modal-component id="modalMarca" titulo="Adicionar Marca">


          <template v-slot:alertas>
            <alert-component tipo="success" :detalhes="transacaoDetalhes" titulo="Cadastro feito com sucesso" v-if="transacaoStatus == 'adicionado' "></alert-component>
            <alert-component tipo="danger" :detalhes="transacaoDetalhes" titulo="Erro ao cadastrar a marca"   v-if="transacaoStatus == 'erro' "></alert-component>
          </template>



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
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn btn-primary" @click="salvar()">Salvar</button>
        </template>
    </modal-component>
    <!-- fim do modal de inclusão de marca -->

        <!-- início do modal de visualização de marca -->
          <modal-component id="modalMarcaVisualizar" titulo="Adicionar Marca">
            <template v-slot:alertas></template>
            <template v-slot:conteudo>
              <input-container-component titulo="ID">
                <input type="text" class="form-control" :value="$store.state.item.id" disabled>
              </input-container-component>

              <input-container-component titulo="Nome da marca">
                <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
              </input-container-component>

              <input-container-component titulo="Imagem" v-if="$store.state.item.imagem">
                <img :src="'storage/' +$store.state.item.imagem" width="50" height="50">
              </input-container-component>

              <input-container-component titulo="Data de criação">
                <input type="text" class="form-control" :value="$store.state.item.created_at" disabled>
              </input-container-component>



            </template>
             <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
             </template>
          </modal-component>
        <!-- fim do modal de visualização de marca -->

        <!-- início do modal de remoção de marca -->
          <modal-component id="modalMarcaRemover" titulo="Remover Marca">

            <template v-slot:alertas>
              <alert-component tipo="success" titulo="Transação realizada com sucesso" :detalhes="{mensagem: $store.state.transacao.mensagem}" v-if="$store.state.transacao.status == 'sucesso'"></alert-component>
              <alert-component tipo="danger" titulo="Erro na transação com sucesso" :detalhes="{mensagem:$store.state.transacao.mensagem}" v-if="$store.state.transacao.status == 'erro'"></alert-component>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">
              <input-container-component titulo="ID">
                <input type="text" class="form-control" :value="$store.state.item.id" disabled>
              </input-container-component>

              <input-container-component titulo="Nome da marca">
                <input type="text" class="form-control" :value="$store.state.item.nome" disabled>
              </input-container-component>
            </template>

             <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="remover()"  v-if="$store.state.transacao.status != 'sucesso'">Remover</button>
             </template>
          </modal-component>
        <!-- fim do modal de remoção de marca -->


        <!-- início do modal de remoção de marca -->
          <modal-component id="modalMarcaAtualizar" titulo="Atualizar Marca">

            <template v-slot:alertas>
            </template>

            <template v-slot:conteudo v-if="$store.state.transacao.status != 'sucesso'">

              <div class="form-group">
                  <input-container-component titulo="Nome da Marca" id="atualizarNome" id-help="atualizarNomeHelp" texto-ajuda="Informe o nome da marca">
                      <input type="text" class="form-control" id="atualizarNome" aria-describedby="atualizarNomeHelp" placeholder="Nome da Marca" v-model="$store.state.item.nome"/>
                  </input-container-component>
              </div>
              <div class="form-group">
                  <input-container-component 
                  titulo="Imagem" id="atualizarImagem" id-help="atualizarImagemHelp" texto-ajuda="Selecione uma imagem no formato PNG">
                      <input type="file" class="form-control-file" id="atualizarImagem" aria-describedby="atualizarImagemHelp" placeholder="Selecione uma imagem no formato PNG" @change="carregarImagem($event)"/>
                  </input-container-component>
            </div>

            </template>

             <template v-slot:rodape>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-danger" @click="atualizar()">Atualizar</button>
             </template>
          </modal-component>
        <!-- fim do modal de remoção de marca -->

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
            urlPaginacao: '',
            urlFiltro: '',
            nomeMarca: '',
            arquivoImagem: [],
            transacaoStatus: '',
            transacaoDetalhes: [],
            marcas: {data: []},
            busca: {id: '', nome: ''}
        }
    },
    methods: {
      atualizar(){        
        let formData = new FormData();
        formData.append('_method', 'patch');
        formData.append('nome', this.$store.state.item.nome);

        if(this.arquivoImagem){
          formData.append('imagem', this.arquivoImagem[0]);
        }

        let config = {
          headers: {
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json',
            'Authorization': this.token
          }
        }

        let url = this.urlBase + '/' + this.$store.state.item.id;


        axios.post(url,formData,config)
          .then(response => {
            console.log(response);
            atualizarImagem.value = '';
/*             this.$store.state.transacao.status  = 'sucesso'
            this.$store.state.transacao.mensagem  = response.data.msg*/
            this.carregarLista(); 
          })
          .catch( errors => {
            console.log(errors);
/*             this.$store.state.transacao.status  = 'erro'
            this.$store.state.transacao.mensagem  = errors.response.data.erro */
          });
      },
      remover(){
        let confirmacao = confirm('Tem certeza que deseja remover esse registro?');

        if(!confirmacao){
          return false;
        }

        let formData = new FormData();
        formData.append('_method', 'delete');

        let config = {
          headers: {
            'Accept': 'application/json',
            'Authorization': this.token
          }
        }

        let url = this.urlBase + '/' + this.$store.state.item.id;


        axios.post(url,formData,config)
          .then(response => {
            console.log(response)
            this.$store.state.transacao.status  = 'sucesso'
            this.$store.state.transacao.mensagem  = response.data.msg
            this.carregarLista();
          })
          .catch( errors => {
            console.log(erro);
            this.$store.state.transacao.status  = 'erro'
            this.$store.state.transacao.mensagem  = errors.response.data.erro
          });
      },
      pesquisar(){
          let filtro = '';

          for(let chave in this.busca){
            if(this.busca[chave]) {
                if(filtro != '') {
                  filtro += ";";
                }
                filtro += chave + ':like:' + this.busca[chave];
            }           
          }

          if(filtro != '') {
            this.urlFiltro = '&filtro='+ filtro;
          }

          this.carregarLista();
        },
        paginacao(l) {
          if(l.url){
            this.urlPaginacao = l.url.split('?')[1];
            this.carregarLista();
          }
        },
        carregarLista(){
            let url = this.urlBase + '?' + this.urlPaginacao + this.urlFiltro;
            let config = {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json',
                    'Authorizathion': this.token
                }
            };
            axios.get(url,config)
            .then(response => {
              this.marcas = response.data
            })
            .catch( errors => {

            });
        },
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
                    this.transacaoStatus = 'adicionado';
                    this.transacaoDetalhes = {
                      mensagem: 'ID do registro: ' + detalhes.data.id
                    };
                })
                .catch(errors => {
                    this.transacaoStatus = 'erro';
                    this.transacaoDetalhes = {
                      mensagem: errors.response.data.message,
                      dados: errors.response.data.errors
                    }
                });
        }
    },
    mounted(){
      this.carregarLista()
    }
};
</script>
