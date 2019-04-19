<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;
use App\Http\Resources\User as UserResource;

//$google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

class PasswordSecurityController extends Controller
{
  
      use AuthenticatesUsers;
    //
    public function show2faForm(Request $request){
    $user = Auth::user();
    //$request->session()->get('user-id');
    //$user = \App\User::find($request->session()->get('user-id'));
    $google2fa_url = "";
    if($user->passwordSecurity()->exists()){
        //$google2fa = app('pragmarx.google2fa');
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $google2fa_url = $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->passwordSecurity->secret
        );
    }
    $data = array(
        'user' => $user,
        'google2fa_url' => $google2fa_url
    );
    return view('auth.2fa')->with('data', $data);
}

public function generate2faSecret(Request $request){
    $user = Auth::user();
    // Initialise the 2FA class
    $google2fa = app('pragmarx.google2fa');

    // Add the secret key to the registration data
    \App\PasswordSecurity::create([
        'user_id' => $user->id,
        'enabled' => 0,
        'secret' => $google2fa->generateSecretKey(),
    ]);

    return redirect('/2fa')->with('success',"Secret Key is generated, Please verify Code to Enable 2FA");
}

public function enable2fa(Request $request){
    $user = Auth::user();
    $google2fa = app('pragmarx.google2fa');
    $secret = $request->input('verify-code');
    $valid = $google2fa->verifyKey($user->passwordSecurity->secret, $secret);
    if($valid){
        $user->passwordSecurity->enabled = 1;
        $user->passwordSecurity->save();
        return redirect('2fa')->with('success',"2FA is Enabled Successfully.");
    }else{
        return redirect('2fa')->with('error',"Invalid Verification Code, Please try again.");
    }
}

public function my2fadisable(Request $request){
  if(Hash::check($request->input("userpass"), Auth::user()->password)){
  $google2fa_url="";
  Auth::user()->passwordSecurity->enabled = 0;
  Auth::user()->passwordSecurity->secret = "";
  Auth::user()->passwordSecurity->save();
  return response('{"data":{"enabled":"0","url":"","key":""}}', 200);
  }
}

public function my2faTest(Request $request){
  if(Hash::check($request->input("userpass"), Auth::user()->password)){
  // Login succeded as we pass the middleware
  $user = Auth::user();
  $google2fa = app('pragmarx.google2fa');
  $valid = $google2fa->verifyKey($user->passwordSecurity->secret, $request->input("one_time_test_password"));
  if ($valid) {
    Auth::user()->passwordSecurity->enabled = 1;
    Auth::user()->passwordSecurity->save();
    $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
    $google2fa_url = $google2fa->getQRCodeInline(
        config('app.name'),
        Auth::user()->email,
        Auth::user()->passwordSecurity->secret
    );

    return response('{"data":{"enabled":"'.Auth::user()->passwordSecurity->enabled.'","url":"'.$google2fa_url.'","key":"'.Auth::user()->passwordSecurity->secret.'"}}', 200);
  } else {
    return response('{"twofactor":testinvalid}', 401);
  }
}  else {
   return response('{"data":{"auth":"failed"}}', 200);
 }
}

public function my2faRefresh(Request $request){
  if(Hash::check($request->input("userpass"), Auth::user()->password)){
    $google2fa_url="";
    $google2fa = app('pragmarx.google2fa');
    $user = User::find(Auth::id());

    if(Auth::user()->passwordSecurity()->exists()){
      $user->passwordSecurity->enabled = 0;
      $user->passwordSecurity->secret = $google2fa->generateSecretKey();
      $user->passwordSecurity->save();
      $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
      $google2fa_url = $google2fa->getQRCodeInline(
        config('app.name'),
        Auth::user()->email,
        Auth::user()->passwordSecurity->secret
      );
      return response('{"data":{"enabled":"'.Auth::user()->passwordSecurity->enabled.'","url":"'.$google2fa_url.'","key":"'.Auth::user()->passwordSecurity->secret.'"}}', 200);
    } else {
      Auth::user()->passwordSecurity()->create(["enabled" => 0,"secret"=>$google2fa->generateSecretKey()]);
      return response('{"data":{"enabled":"'.Auth::user()->passwordSecurity->enabled.'","url":"'.$google2fa_url.'","key":"'.Auth::user()->passwordSecurity->secret.'"}}', 200);
    }
    return response('{"data":{"enabled":"0","url":"","key":""}}', 200);
  } else {
    return response('{"data":{"auth":"failed"}}', 200);
  }
}

public function my2faGet(Request $request){
  if(Hash::check($request->input("userpass"), Auth::user()->password)){
  $google2fa_url="";
  
  if(Auth::user()->passwordSecurity()->exists()){
    if(Auth::user()->passwordSecurity->secret != ""){
      //$google2fa = app('pragmarx.google2fa');
      $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
      $google2fa_url = $google2fa->getQRCodeInline(
          config('app.name'),
          Auth::user()->email,
          Auth::user()->passwordSecurity->secret
      );
    }
    return response('{"data":{"enabled":"'.Auth::user()->passwordSecurity->enabled.'","url":"'.$google2fa_url.'","key":"'.Auth::user()->passwordSecurity->secret.'"}}', 200);
  } else {
    Auth::user()->passwordSecurity()->create(["enabled" => 0,"secret"=>""]);
  }
  return response('{"data":{"enabled":"0","url":"","key":""}}', 200);
} else {
  return response('{"data":{"auth":"failed"}}', 200);
}
}

public function my2faverify(Request $request){
  // Login succeded as we pass the middleware
  $user = User::find($request->session()->get('user-id'));
  $google2fa = app('pragmarx.google2fa');
  $valid = $google2fa->verifyKey($user->passwordSecurity->secret, $request->input("one_time_password"));
if ($valid) {
  Auth::loginUsingId($request->session()->get('user-id'));
  $request->session()->put('user-id',0);
  if(!empty($request->input("ajaxLogin"))){
    return new UserResource(Auth::user());
  } else {
    return $this->sendLoginResponse($request);
    //return redirect("/");
}
} else {
  if(!empty($request->input("ajaxLogin"))){
    return response('{"twofactor":invalid}', 401);
  } else {
    return redirect("/login");
}
}
}
public function disable2fa(Request $request){
    if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
        // The passwords matches
        return redirect()->back()->with("error","Your  password does not matches with your account password. Please try again.");
    }

    $validatedData = $request->validate([
        'current-password' => 'required',
    ]);
    $user = Auth::user();
    $user->passwordSecurity->enabled = 0;
    $user->passwordSecurity->save();
    return redirect('/2fa')->with('success',"2FA is now Disabled.");
}

}
