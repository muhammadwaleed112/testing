<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class profile extends Controller
{
    public function profile(){
        return view('profile');
    }

    public function profile_update(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        if ($request->profile_image) {
        $file = $request->file('profile_image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('images', $filename, 'public');
        $profile = User::where('name',Auth::User()->name)->update([
                'profile_image' => $path,
            ]);
        }
        if ($request->name) {
            $name = User::where('name',Auth::User()->name)->update([
                'name' => $request->name,
            ]);
        }
        return response()->json(['status' => true,'message' => 'Profile Successfully Updated...']);
        
    }

    public function updatePassword(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            // Update the user's password
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['status' => true, 'message' => 'Password Updated Successfully']);
        } else {
            return response()->json(['status' => false, 'message' => 'Current Password Does Not Match']);
        }
    }
}
