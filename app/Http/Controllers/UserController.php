<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\UserData;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login(Request $request)
    {
        // try {
        $user = User::where('email', '=', $request['email'])->first();
        $error = [];
        if ($user == null) {
            $error = ["Usuario nao encontrado."];
        }

        if (!password_verify($request->password, $user->password)) {
            $error = ['Email ou senha incorretos!'];
        }

        if (!$error) {
            $token = $user->createToken('token')->accessToken;
            return response()->json(['status' => true, 'user' => $user, 'access_token' => $token]);
        } else {
            return response()->json(['status' => false, 'errors' => $error]);
        }
        // } catch (Exception $ex) {
        //     return response()->json(['status' => false, 'error' => $ex,]);
        // }
    }

    public function register(UserRequest $request)
    {
        $data = $request->validated();

        // try {
        $user = new User();
        $user->fill($data)->fill(['password' => bcrypt($data['password'])])->save();

        return response()->json(['status' => true]);
        // } catch (Exception $ex) {
        //     return response()->json(['status' => false, 'error' => $ex,]);
        // }
    }

    public function list_users(Request $request)
    {
        try {
            $users = User::orderBy('name', 'asc')->with(['images', 'sizes'])->where(function ($q) use ($request) {
                $q->whereRaw('lower(name) LIKE lower(?)', ['%' . $request->search . '%']);
            })->paginate(10);

            return response()->json([
                'status' => true,
                'users' => UserResource::collection($users),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'total_pages' => $users->total(),
                    'per_page' => $users->perPage(),
                ],
            ]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex,]);
        }
    }

    public function store_user(UserRequest $request)
    {
        $data = $request->validated();

        try {
            $user = new User();
            $user->fill($data)->fill(['password' => bcrypt($data['password'])])->save();

            // return $user;
            $user_data = new UserData();
            $user_data->fill($data)->fill(['user_id' => $user->id]);

            // if (isset($request->file('file'))) {
            //     $document = $request->file('file');
            //     $name = uniqid('photo_') . '.' . $document->getClientOriginalExtension();
            //     $user_data->file = $document->storeAs('photos', $name, ['disk' => 'public']);
            // }

            $user_data->save();

            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex,]);
        }
    }

    public function get_user($id)
    {
        try {
            $user = User::where('id', '=', $id)->first();

            return response()->json(['status' => true, 'user' => $user]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex,]);
        }
    }

    public function update_user(UserRequest $request)
    {
        $data = $request->validated();

        try {
            $user = User::where('id', '=', $data['id'])->first();
            $user->fill($data)->fill(['password' => bcrypt($data['password'])])->save();

            $user_data = UserData::where('user_id', '=', $user->id)->first();
            $user_data->fill($data);

            // if (isset($request->file('file'))) {
            //     $document = $request->file('file');
            //     $name = uniqid('photo_') . '.' . $document->getClientOriginalExtension();
            //     $user_data->file = $document->storeAs('photos', $name, ['disk' => 'public']);
            // }

            $user_data->save();

            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'error' => $ex,]);
        }
    }

    public function delete_user($id)
    {
        $product = User::findOrFail($id)->delete();

        if (empty($product)) {
            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }
}
