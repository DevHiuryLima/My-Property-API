<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\RealState;
use App\Repository\RealStateRepository;
use Illuminate\Http\Request;

class RealStateSearchController extends Controller
{
    private RealState $realState;

    public function __construct(RealState $realState)
    {

        $this->realState = $realState;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Modelo usando o Builder no AbstractRepository.
        // isso por que se passa condições, exemplo preco > 10.
         $realState = RealState::query();
         $repository = new RealStateRepository($realState);

        // Modelo usando o Model no AbstractRepository.
         // Se fosse se as condições.
         // $repository = new RealStateRepository($this->realState);

        if($request->has('conditions')) {
            // Executa no repository a função para conditions
            $repository->selectConditions($request->get('conditions'));
        }

        if($request->has('fields')) {
            $repository->selectFilter($request->get('fields'));
        }

        $repository->setLocation($request->all(['state', 'city']));

        return response()->json([
            'data' => $repository->getResult()->paginate(10),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $realState = $this->realState->with('address')->with('photos')->findOrFail($id);

            return response()->json([
                'data' => $realState,
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
