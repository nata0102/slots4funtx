<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PartImage;

class ImagePartBrandController extends Controller
{
	public function index(Request $request)
  {
		  $res = [];
	    switch ($request->option) {
	        default:
            $res = $this->searchWithFilters($request->all()); 
	        break;
	    }
	    $types =  DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
	    $qry = "select l.lkp_id,l.brand_id,b.brand,b.model from parts_lkp_brands l, machine_brands b
	              where l.brand_id = b.id and b.active = 1 order by b.brand, b.model;";
		  $brands = json_encode(DB::select($qry));
	    return view('imagePartBrand.index',compact('res','types','brands'));
	}

  public function searchWithFilters($params){
      $qry = "select * from (select *, (select value from lookups where id=i.part_id) as part,
      (select brand from machine_brands where id=i.brand_id) as brand,
      (select model from machine_brands where id=i.brand_id) as model
      from images_brands i ";
      if (array_key_exists('type', $params) &&  array_key_exists('brand', $params)) {
        if($params['type'] != "" && $params['brand'] != "" )
          $qry .= " where i.part_id =".$params['type']." and i.brand_id = ".$params['brand'];
        else{
          if($params['type'] != "" || $params['brand'] != "" ){
            $qry .= " where ";
            if($params['type'] != "")
              $qry .= " i.part_id =".$params['type'];
            if($params['brand'] != "")
              $qry.= " i.brand_id = ".$params['brand'];
          }
        }        
      }
      $qry .= ") as t1 order by t1.part, brand,model"; 
      return DB::select($qry);
  }

	public function create(){
		if( url()->previous() != url()->current() ){
	        session()->forget('urlBack');
	        session(['urlBack' => url()->previous()]);
	    }

      $types = DB::table('lookups')->where('type','part_type')->where('active',1)->orderBy('value')->get();
	    $qry = "select l.lkp_id,l.brand_id,b.brand,b.model from parts_lkp_brands l, machine_brands b
	              where l.brand_id = b.id and b.active = 1 order by b.brand, b.model;";
		  $brands = json_encode(DB::select($qry));
	    return view('imagePartBrand.create',compact('types','brands'));
  }

  public function store(Request $request){
      	$this->validate($request, [
            'type' => 'required',
            'image' => 'required'
        ]); 
        try{
          return DB::transaction(function() use($request){
            $image = new PartImage;
            $image->part_id = $request->type;
            $image->brand_id = $request->brand;
            if($request->image){
                $image->name_image = $this->saveGetNameImage($request->image,'/images/part_brand/');
            }
            $image->save();
            $notification = array(
                'message' => 'Successful!!',
                'alert-type' => 'success'
            );
            return redirect()->action('ImagePartBrandController@index')->with($notification);
          });
        }catch(\Exception $e){
          $notification = array(
              'message' => 'Oops! there was an error, please try again later.',
              'alert-type' => 'error'
          );
          return back()->with($notification)->withInput($request->all());
        }
  }

   public function destroy($id){

      try{
          return DB::transaction(function() use ($id){
            $res = PartImage::findOrFail($id);
            if(file_exists(public_path() . '/images/part_brand/'.$res->name_image))
              unlink(public_path() . '/images/part_brand/'.$res->name_image);
            $destroy = $res->delete();
            if($destroy)
              return response()->json(200);
            else{
                $notification = array(
                  'message' => 'Oops! there was an error, please try again later.'.$e->getMessage(),
                  'alert-type' => 'error'
                );
                return redirect()->action('ImagePartBrandController@index')->with($notification);
            }
        });
      }catch(\Exception $e){
        $notification = array(
          'message' => 'Oops! there was an error, please try again later.'.$e->getMessage(),
          'alert-type' => 'error'
        );
        return redirect()->action('ImagePartBrandController@index')->with($notification);
      }
   }





	public function gallery($id){
      $part = Part::with('type','protocol','brand','status','machine.brand')->find($id);
      $images = PartImage::where('part_id',$id)->get();
      return view('parts.images',compact('images','part'));
    }

    public function createImage(Request $request, $id){
      $notification = array(
          'message' => 'There is already a record with the same data.',
          'alert-type' => 'info'
      );

      try{
        return DB::transaction(function() use($request, $id){
          $image = new PartImage;
          $image->part_id = $id;
          if($request->image){
              $image->name_image = $this->saveGetNameImage($request->image,'/images/part brand/');
          }
          $image->save();
          $notification = array(
              'message' => 'Successful!!',
              'alert-type' => 'success'
          );
          return redirect() -> action('PartController@gallery',$id)->with($notification);
        });
      }catch(\Exception $e){
        $notification = array(
            'message' => 'Oops! there was an error, please try again later.',
            'alert-type' => 'error'
        );
      }
      return redirect() -> action('PartController@gallery',$id)->with($notification);
    }

    public function deleteImage($id){
      $image = PartImage::where('id',$id)->first();
      $notification = array(
          'message' => 'Oops! there was an error, please try again later.',
          'alert-type' => 'error'
      );
      if($image != NULL){
        try{
          return DB::transaction(function() use($image, $notification){
            if(file_exists(public_path() . '/images/part brand/'.$image->name_image))
            {
              unlink(public_path() . '/images/part brand/'.$image->name_image);
            }
            $destroy = $image->delete();
            if($destroy){
              $notification = array(
                'message' => 'Successful!!',
                'alert-type' => 'success'
              );
            }
            return redirect() -> action('PartController@gallery',$image->part_id)->with($notification);
          });


        }catch(\Exception $e){
          $notification = array(
            'message' => 'Oops! there was an error, please try again later.',
            'alert-type' => 'error'
          );
          return redirect() -> action('PartController@gallery',$image->part_id)->with($notification);
        }
      }
      return redirect() -> back()->with($notification);


    }
}
