<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Address;
use DB;
use File;
use Input;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      switch($request->option){
        case 'all':
          $clients = $this->searchWithFilters($request->all());
        break;
        default:
          $clients = Client::where('active',1)->orderBy('id','desc')->take(20)->get();
        break;
      }
      return view('client.index',compact('clients'));
    }

    public function searchWithFilters($params){
      $res = Client::
      where('active',$params['active'])
      ->name($params['name'])
      ->email($params['email'])
      ->phone($params['phone'])->get();
      return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      return view('client.create');
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
        'name' => 'required',
        'phone' => 'required|unique:clients,phone|numeric',
        'email' => 'required|unique:clients,email',
      ]);

      try {
        return DB::transaction(function() use($request){
          $client = new Client;
          $client->name = $request->name;
          $client->phone = $request->phone;
          $client->dob = $request->dob;
          $client->email = $request->email;
          $client->referral = $request->referral;
          $client->active = 1;
          $created = $client->save();
          if($request->image){
            $client->photo = $this->saveGetNameImage($request->image,'/images/clients/');
          }
          $created = $client->save();
          if ($created) {
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );
            return redirect()->action('ClientController@index')->with($notification);
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
            return back()->with($notification)->withInput($request->all());
          }
        });
      } catch (\Exception $e) {
        $cad='Oops! there was an error, please try again later.';
        $message = $e->getMessage();
        $transaction = array(
            'message' => $message,
            'alert-type' => 'error'
        );
        return back()->with($transaction)->withInput($request->all());
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
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      $client = Client::find($id);
      $addresses = Address::where('client_id',$id)->where('active',1)->get();
      return view('client.show',compact('client','addresses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      if( url()->previous() != url()->current() ){
        session()->forget('urlBack');
        session(['urlBack' => url()->previous()]);
      }
      $client = Client::find($id);
      $cities =  DB::table('lookups')->where('type','cities')->where('band_add',0)->orderBy('value')->get();
      $counties =  DB::table('lookups')->where('type','counties')->where('band_add',0)->orderBy('value')->get();
      $addresses = Address::where('client_id',$id)->where('active',1)->with('county','city')->get();


      return view('client.edit',compact('client','addresses','cities','counties'));
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


      $this->validate($request, [
        'name' => 'required',
        'phone' => 'required|numeric|unique:clients,phone,'.$id,
        'email' => 'required|unique:clients,email,'.$id,
      ]);


      try {
        return DB::transaction(function() use($request,$id){
          $client = Client::find($id);
          $client->name = $request->name;
          $client->phone = $request->phone;
          $client->dob = $request->dob;
          $client->email = $request->email;
          $client->referral = $request->referral;
          $client->active = 1;
          $created = $client->save();
          if($request->image){
            if($client->photo != NULL && file_exists(public_path().'/images/clients/'.$client->photo)){
              unlink(public_path().'/images/clients/'.$client->photo);
            }
            $client->photo = $this->saveGetNameImage($request->image,'/images/clients/');
          }
          $created = $client->save();
          if ($created) {
            $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
            );
            return redirect()->action('ClientController@index')->with($notification);
          }else {
            $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
            );
            return back()->with($notification)->withInput($request->all());
          }
        });
      } catch (\Exception $e) {
        $cad='Oops! there was an error, please try again later.';
        $message = $e->getMessage();
        $transaction = array(
            'message' => $message,
            'alert-type' => 'error'
        );
        return back()->with($transaction)->withInput($request->all());
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
      try{
        return DB::transaction(function() use($id){
          $brand = Client::find($id);
          $brand->active = $brand->active == 0 ? 1 : 0;
          $destroy = $brand->save();
          if ($destroy) {
            return response()->json(200);
          }else {
            return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
          }
      });
      }catch(\Exception $e){
        return response()->json(['errors' => 'Oops! there was an error, please try again later.'], '422');
      }
    }
}
