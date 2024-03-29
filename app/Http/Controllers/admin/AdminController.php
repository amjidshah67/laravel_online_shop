<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index(){
        return view('admin.login');
    }
    public function authenticate(Request $request){
        $validator = validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->passes()){
            if (Auth::guard('admin')
                ->attempt(['email' => $request->email,'password'
                => $request->password],$request->get('remember'))){

                $dmin = Auth::guard('admin')->user();
                if ($dmin->role == 2){
                    return redirect()->route('admin.dashboard');
                }
                else{
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')
                        ->with('error','you are not autherize to access admin panel');

                }
            }else{
                return redirect()->route('admin.login')
                    ->with('error','Your email password is incorrect');
            }
        }else{
            return redirect()->route('admin.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

}
