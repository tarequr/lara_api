<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    
    public function index()
    {
        try {
            $users = User::all();
            return response([
                'users'   => $users,
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ]);
        }
    }


    public function store(Request $request)
    {
        /* start validation part */
        $validator = Validator::make($request->all(),[
            'name'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'gendar'   => 'required',
            'avatar'   => 'required|image',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->all()
            ]);
        }
        /* end validation part */

        try {
            /* start image part */
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = 'IMG_'.date('YmdHi').'.'.$file->getClientOriginalExtension();
                $file->move(public_path('upload/user_images'),$filename);
            }
            /* end image part */

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'gendar'   => $request->gendar,
                'avatar'   => $filename
            ]);

            return response([
                'user'    => $user,
                'message' => 'User created successfully!'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ]);
        }
    }


    public function update(Request $request, $id)
    {
        /* start validation part */
        $validator = Validator::make($request->all(),[
            'name'     => 'required|string',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'gendar'   => 'required',
            'avatar'   => 'required|image',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->errors()->all()
            ]);
        }
        /* end validation part */

        try {
            $user = User::findOrFail($id);
            
            /* start image part */
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                @unlink(public_path('upload/user_images/'.$user->avatar));
                $filename = 'IMG_'.date('YmdHi').'.'.$file->getClientOriginalExtension();
                $file->move(public_path('upload/user_images'),$filename);
            }
            /* end image part */

            $user->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'gendar'   => $request->gendar,
                'avatar'   => $filename
            ]);

            return response([
                'user'    => $user,
                'message' => 'User updated successfully!'
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ]);
        }
    }


    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if (file_exists('upload/user_images/' . $user->avatar) AND ! empty($user->avatar)) {
                unlink('upload/user_images/' . $user->avatar);
            }
            $user->delete();
            return response([
                'message' => "User deleted successfully!."
            ]);
        } catch (\Throwable $th) {
            return response([
                'message' => $th->getMessage()
            ]);
        }
    }
}
