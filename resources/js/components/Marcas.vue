<template>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Marcas</div>

                <div class="card-body">
                    <div class="form-row">
                        <div class="col mb-3">
                            <input-container-component titulo="ID" id="inputId" id-help="inputIdHelp" texto-ajuda="Opcional. Informe o ID do registro">
                                <input type="number" class="form-control" id="inputId" aria-describedby="inputIdHelp" placeholder="ID">
                            </input-container-component>
                        </div>

                        <div class=" col mb-3">
                            <input-container-component titulo="Nome" id="inputNome" id-help="inputNomeHelp" texto-ajuda="Opcional. Informe o nome da marca">
                                <input type="text" class="form-control" id="inputNome" aria-describedby="inputNomeHelp" placeholder="Nome">
                            </input-container-component>
                        </div>
                    </div>
                </div>
                <div class="card-footer">                    
                    <button type="submit" class="btn btn-primary btn-sm float-right">Pesquisar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    export default {

    }
</script>
