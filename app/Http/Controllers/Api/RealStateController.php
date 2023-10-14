<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\Models\RealState;
use Illuminate\Http\Request;

class RealStateController extends Controller
{
    private RealState $realState;

    public function __construct(RealState $realState)
    {

        $this->realState = $realState;
    }

    public function index()
    {
        $realState = $this->realState->paginate(10);

        return response()->json($realState, 200);
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        $images = $request->file('images');

        try {
            $realState = $this->realState->create($data); // mass assigment

            if (isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            if ($images) {
                foreach ($images as $image) {
                    $path = $image->store('images', 'public');
                    dd($path);
                }
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel cadastrado com sucesso!',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function show($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);

            return response()->json([
                'data' => $realState,
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function update($id, RealStateRequest $request)
    {
        $data = $request->all();

        try {
            $realState = $this->realState->findOrFail($id);
            $realState->update($data); // mass assigment

            if (isset($data['categories']) && count($data['categories'])){
                $realState->categories()->sync($data['categories']);
            }

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel atualizado com sucesso!',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    public function destroy($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);
            $realState->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Imóvel removido com sucesso!',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
