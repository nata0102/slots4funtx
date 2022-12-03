<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        switch ($request->option) {
            case 'filters_components':
                $res = $this->getFiltersComponents();
                break;
            case 'report_components'
            break;
        }
    }

    public function getFiltersComponents(){
        $res = [];
        $res['types'] = DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
        $qry = "select l.lkp_id,l.brand_id,b.brand,b.model from parts_lkp_brands l, machine_brands b
              where l.brand_id = b.id and b.active = 1 order by b.brand, b.model;";
        $res['brands'] = json_encode(DB::select($qry));
        $res['status'] =  DB::table('lookups')->where('type','status_parts')->where('active',1)->orderBy('value')->get();
        return $res;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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
