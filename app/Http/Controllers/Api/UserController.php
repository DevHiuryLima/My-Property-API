<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->paginate(10);

        return response()->json($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (!$request->has('password') || !$request->get('password')){
            $message = new ApiMessages('É nescessário informar uma senha para o usuário...');
            return response()->json($message->getMessage(), 401);
        }

        Validator::make($data, [
            'phone' => 'required',
            'mobile_phone' => 'required',
        ])->validate();

        try {
            $data['password'] = bcrypt($data['password']);
            $user = $this->user->create($data); // mass assigment
            $user->profile()->create([
                'phone' => $data['phone'],
                'mobile_phone' => $data['mobile_phone'],
            ]);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário cadastrado com sucesso!',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
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
            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

            return response()->json([
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        if ($request->has('password') && $request->get('password')){
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        Validator::make($data, [
            'profile.phone' => 'required',
            'profile.mobile_phone' => 'required',
        ])->validate();

        try {
            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);
            $user = $this->user->findOrFail($id);
            $user->update($data); // mass assigment

            $user->profile()->update($profile);

            return response()->json([
                'data' => [
                    'msg' => 'Usuário atualizado com sucesso!',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = $this->user->findOrFail($id);
            $user->delete();

            return response()->json([
                'data' => [
                    'msg' => 'Usuário removido com sucesso!',
                ]
            ], 200);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
