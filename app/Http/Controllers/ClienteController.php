<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ClienteRepository = new ClienteRepository($this->cliente);
        
        if($request->has('filtro')){
           $ClienteRepository->filtro($request->filtro);
        }

        if($request->has('atributos')){
            $ClienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($ClienteRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());

        $cliente = $this->cliente->create([
            'nome' => $request->nome,
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'O Recurso pesquisado não existe.'],404);
        }
        return response()->json($cliente,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request ,$id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null){
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe.'],201);
        }

        if($request->method() === 'PATCH'){
            $regrasDinamicas = array();

            foreach ($cliente->rules() as $input => $regra) {
                if(array_key_exists($input,$request->all()))
                {                    
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        }else{
            $request->validate($this->cliente->rules());
        }
        
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null){
            return response()->json(['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe.'],201);
        }

        $cliente->delete();
        return response()->json(['msg' => 'O cliente foi removido com sucesso!'],200);
    }
}
