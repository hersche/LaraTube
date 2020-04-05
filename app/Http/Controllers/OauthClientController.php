<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\User;
use App\Http\Resources\User as UserResource;

//$google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

class OauthClientController extends Controller
{

    use AuthenticatesUsers;
    private $gc;

    public function __construct(){
      $verifySSL = true;
      if(config("app.oauthclientuntrustedssl")=="true"){
        $verifySSL = false;
      }
      $this->gc = new \GuzzleHttp\Client([
              'allow_redirects' => true,
              'cookies'         => true,
              'verify' => $verifySSL
      ]);
    }


    private function getUser($res){
      $u = User::where(["email"=>$res["data"]["email"]])->first();
      if(empty($u)){
        $u = User::where(["username"=>$res["data"]["username"]])->first();
        if(empty($u)){
          $u = User::create(["email"=>$res["data"]["email"],"bio"=>$res["data"]["bio"],"roles"=>join(",",$res["data"]["roles"]),"username"=>$res["data"]["username"],"name"=>$res["data"]["name"],"avatar"=>$res["data"]["avatar"],"background"=>$res["data"]["background"]]);
        } else {
          $u->email = $res["data"]["email"];
          $u->save();
        }
      }

      //$res["data"]["roles"]=join(",",$res["data"]["roles"]);
      return $u;
    }

    public function oauthRefreshUser(Request $request){
      if(config("app.auth")=="oauth"){
        $response = $this->gc->get(config("app.oauthbaseurl").'/api/user', [
            'headers' => [
                'Authorization' => 'Bearer '.session()->get('token.access_token')
            ]
        ]);
        $res = json_decode((string) $response->getBody(), true);
        //var_dump($res);
        $u = $this->getUser($res);
        $u->update($res["data"]);
        $u->save();
        return new UserResource($u);
      }
    }

    public function oauthGetUser(Request $request){
      if(config("app.auth")=="oauth"){
        $response = $this->gc->get(config("app.oauthbaseurl").'/api/user', [
            'headers' => [
                'Authorization' => 'Bearer '.session()->get('token.access_token')
            ]
        ]);
        $res = json_decode((string) $response->getBody(), true);
        //var_dump($res);
        $u = $this->getUser($res);
        Auth::loginUsingId($u->id);
        return redirect("/");
      }
      }

    public function oauthLogin(Request $request){
        $query = http_build_query([
            'client_id' => config('app.oauthclientid'), // Replace with Client ID
            'redirect_uri' => config('app.oauthclientcallback'),
            'response_type' => 'code',
            'scope' => 'profile notifications'
        ]);
       return redirect(config('app.oauthclientauthorize').'?'.$query);

    }
    //
    public function oauthCallback(Request $request){
        $response = $this->gc->post(config('app.oauthclienttoken'), [
          'form_params' => [
          'grant_type' => 'authorization_code',
          'client_id' => config('app.oauthclientid'), // Replace with Client ID
          'client_secret' => config('app.oauthclientsecret'), // Replace with client secret
          'redirect_uri' => config('app.oauthclientcallback'),
          'code' => $request->code,
          ]
        ]);
        session()->put('token', json_decode((string) $response->getBody(), true));
        return redirect('/api/auth/getOauthUser');
      }

}
