<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $users = User::latest()->paginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:100|string',
            'email' => 'required|max:100|string|email|unique:users',
            'password' => 'required|max:100|string',
            'type' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'type' => $request->type,
            'photo' => $request->photo,
            'password' => Hash::make($request->password),
        ]);

        //return $user;

        if($user->id) {
            return response(['success' => true, "msg" => "Data saved successfully!"], 200);
        } else {
            return response(['errors' => true], 500);
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
        return User::findOrFail($id);
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
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $user->id,
            'type' => 'required',
            'bio' => 'nullable|string',
            'photo' => 'nullable|string|max:100',
            'password' => 'sometimes|min:6',
        ]);

        if($user->update($request->all())) {
            return response(['success' => true, "msg" => "Data update successfully!"], 200);
        } else {
            return response(['errors' => true], 500);
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
        $user = User::findOrFail($id);

        if($user->delete()) {
            return response(['success' => true, "msg" => "Data delete successfully!"], 200);
        } else {
            return response(['errors' => true], 500);
        }
    }

    public function search()
    {
        if($search = \Request::get('q')){
            $users = User::where(function ($query) use ($search){
                $query->where('name', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('type', 'LIKE', "%$search%");
            })->paginate(5);
        } else {
            $users = User::latest()->paginate(5);
        }

        return $users;
    }
}
