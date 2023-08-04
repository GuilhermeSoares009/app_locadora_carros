<?php 
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectAtributoRegistrosRelacionados($atributos) {
        $this->model = $this->model->with($atributos);
    }

    public function filtro($filtros) {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $condicoes) {
            $condicao = explode(':',$condicoes);
            $this->model = $this->model->where($condicao[0],$condicao[1],$condicao[2]);
        }
    }

    public function selectAtributos($atributos) {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado() {
        return $this->model->get();
    }
}

?>