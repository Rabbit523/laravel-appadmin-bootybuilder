<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use phpseclib\Crypt\Hash;
use Illuminate\Hashing\BcryptHasher;

class ChangePasswordController extends Controller
{
    //

    public function change_password(Request $request) {
        $email = $request->get('email');
        $old_password = $request->get('old');
        $new_password = $request->get('new');

        $res = User::where('email', $email)->first();

        if(password_verify($old_password, $res->password)) {
            User::where('email', $email)->update([
                'password' => bcrypt($new_password)
            ]);

            return response()->json([
                'res' => 'success'
            ]);
        }

        return response()->json([
            'res'=> 'error'
        ]);
    }
}
