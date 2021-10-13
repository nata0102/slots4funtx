<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;


class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = [];

        switch($request->option){
            case 'all':
               $res = $this->searchWithFilters($request->all());
            break;
            default:
               $res = Machine::with('status','address.client','brand','owner')->where('active',1)->take(20)->get();
            break;
        }
        //return $res;

        return view('machines.index',compact('res'));

        /*Search
                        {{ Form::open(['route' => 'machines', 'method' => 'GET', 'class' => 'form-inline-pull-right']) }}
                            <div class="form-group">
                                {{ Form::text('name',null, ['class' => 'form-control','placeholder'=> 'Machine']) }}
                            </div>
                            <div class="form-group">
                                <button type="submit", class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    
                                </button>
                            </div>
                        {{ Form::close() }}*/
    }

    public function searchWithFilters($params){
        $res = [];
        $aux = Machine::with([
            'status' => function ($query) use($params){
                if($params['status'])
                    $query->where('value', 'LIKE', "{$params['status']}%");
            },
            'address.client',
            'brand' => function($query) use ($params){
                if($params['brand'])
                    $query->where('brand', 'LIKE', "%{$params['brand']}%")
                    ->orWhere('model', 'LIKE', "%{$params['brand']}%")
                    ->orWhere('weight', 'LIKE', "%{$params['brand']}%");
            },
            'owner' => function($query) use($params){
                if($params['owner'])
                    $query->where('value', 'LIKE', "%{$params['owner']}%");
            }])->game($params['game'])->where('active',1)->get();
            foreach ($aux as $a) {
                $b_owner = true;
                if($params['owner']){
                    if($a->owner == null)
                        $b_owner = false;
                }
                $b_status = true;
                if($params['status']){
                    if($a->status == null)
                        $b_status = false;
                }
                $b_brand = true;
                if($params['brand']){
                    if($a->brand == null)
                        $b_brand = false;
                }
                if($b_owner == true && $b_status == true && $b_brand == true)
                    array_push($res,$a);
            }
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
