<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Users extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::paginate(10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);
        
        $arrayInsertUser = [
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'name' => $request->name,
        ];
        DB::beginTransaction();
        try {
            User::create($arrayInsertUser);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response(500)->json([
                'status' => 'Error',
                'message' => 'Unable to create user',
                'error' => $th->getMessage()
            ]);
        }
        DB::commit();
        return response(200)->json([
            'status' => 'Success',
            'message' => 'User has been created',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'password' => 'required|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        $user = User::find($id);
        if($user){
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->name = $request->name;

            return response(200)->json([
                'status' => 'Success',
                'message' => 'User has been updated',
            ]);
        }else{
            return response(200)->json([
                'status' => 'Warning',
                'message' => 'User failed to update',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        User::findOrFail($id)->delete();
    }
}
