<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    protected $modelo;

    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ModeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')){
            $atributos_marca = 'marca:id,'.$request->atributos_marca;
            $ModeloRepository->selectAtributoRegistrosRelacionados($atributos_marca);
        }else{
            $ModeloRepository->selectAtributoRegistrosRelacionados('marca');
        }
        
        if($request->has('filtro')){
           $ModeloRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $ModeloRepository->selectAtributos($request->atributos);
        }

        return response()->json($ModeloRepository->getResultado(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'O recurso pesquisado não existe.'],404);
        }
        return response()->json($modelo,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);

        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'],201);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($modelo->rules() as $input => $regra) {
                if(array_key_exists($input,$request->all()))
                {                    
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        }else{
            $request->validate($this->modelo->rules());
        }

        if($request->file('imagem'))
        {
            Storage::disk('public')->delete($modelo->imagem);
        }


        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos','public');
        
        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();
        return response()->json($modelo,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'],201);
        }

        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();

        return response()->json(['msg' => 'O modelo foi removido com sucesso!'],201);
    }
}
