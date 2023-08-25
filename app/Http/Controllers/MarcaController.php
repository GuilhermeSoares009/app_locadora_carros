<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    protected $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributoRegistrosRelacionados($atributos_modelos);
        }else{
            $marcaRepository->selectAtributoRegistrosRelacionados('modelos');
        }
        
        if($request->has('filtro')){
           $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(),$this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens','public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param   Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null){
            return response()->json(['erro' => 'O recurso solicitado não existe.'],404);
        }
        return response()->json($marca,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $marca = $this->marca->find($id);

        if($marca === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'],201);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($marca->rules() as $input => $regra) {
                if(array_key_exists($input,$request->all()))
                {                    
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas,$this->marca->feedback());
        }else{
            $request->validate($this->marca->rules(),$this->marca->feedback());
        }

        $marca->fill($request->all());

        if($request->file('iamgem')){
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $marca->imagem = $imagem_urn;
        }

        $marca->save();
        return response()->json($marca,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'],201);
        }

        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();

        return response()->json(['msg' => 'A marca foi removida com sucesso!'],201);
    }
}
