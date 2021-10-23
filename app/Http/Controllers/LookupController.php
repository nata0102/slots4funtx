<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lookup;
use Illuminate\Support\Facades\DB;

class LookupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = [];
        switch ($request->option) {
            case 'type':
                $res = Lookup::where('type',$request->type)->orderBy('value')->get();
            break;
            default:
                $res = $this->searchWithFilters($request->all());
            break;
        }
        return view('lookups.index',compact('res'));
    }

    public function indexList(){
        $qry = "select tab1.*, l2.id, l2.value from(select l.key_value as p_key_value, value as p_value
                from lookups l where band_add = 1 and type='configuration') as tab1, lookups l2
                where l2.type = tab1.p_key_value;";
        return DB::select($qry);
    }

    public function searchWithFilters($params){
        $qry = "select tab1.*, l2.id, l2.value from(select l.key_value as p_key_value, value as p_value
                from lookups l where band_add = 1 and type='configuration') as tab1, lookups l2  where l2.type = tab1.p_key_value";
        if (array_key_exists('type', $params))
            $qry .= " and tab1.p_key_value like '%".$params['type']."%'";
        if (array_key_exists('value', $params))
            $qry .= " and l2.value like '%".$params['value']."%'";
        $qry .= " order by p_value, value;";
        return DB::select($qry);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $configuration =  DB::table('lookups')->where('type','configuration')->where('band_add',1)->get();
        return view('lookups.create',compact('configuration'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = DB::transaction(function() use($request){         
            return Lookup::create($request->all());
        });
        return $transaction;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Lookup::findOrFail($id);
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
    public function update(Request $request, $id)
    {
        $transaction = DB::transaction(function() use($request,$id){  
            $res = Lookup::findOrFail($id);
            $res->fill($request->all());
            $res->save();
        });
        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
