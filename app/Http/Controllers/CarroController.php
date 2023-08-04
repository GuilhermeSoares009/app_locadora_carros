<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;
class CarroController extends Controller
{

    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $CarroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')){
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            $CarroRepository->selectAtributoRegistrosRelacionados($atributos_modelo);
        }else{
            $CarroRepository->selectAtributoRegistrosRelacionados('modelo');
        }
        
        if($request->has('filtro')){
           $CarroRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $CarroRepository->selectAtributos($request->atributos);
        }

        return response()->json($CarroRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCarroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carro->rules());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if($carro === null){
            return response()->json(['erro' => 'O recurso pesquisado não existe.'],404);
        }
        return response()->json($carro,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if($carro === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'],201);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($carro->rules() as $input => $regra) {
                if(array_key_exists($input,$request->all()))
                {                    
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        }else{
            $request->validate($this->carro->rules());
        }
        
        $carro->fill($request->all());
        $carro->save();
        return response()->json($carro,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Carro  $carro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carro = $this->carro->find($id);
        if($carro === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'],201);
        }

        $carro->delete();
        return response()->json(['msg' => 'O carro foi removido com sucesso!'],200);
    }
}
