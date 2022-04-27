@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='ImagePartBrand';");
?>

<div class="main-content">
    <div class="section__content section__content--p30">
        <div class="container-fluid">
            <div class="card" id="card-section">
        
                <div class="input-group mb-2">
                    <a href="{{action('ImagePartBrandController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>                   
                </div>

                <form method="GET" action="{{action('ImagePartBrandController@index')}}">
                    <div class="input-group mb-5">
                        <select onchange="fillBrand(this.value, {{$brands}})" id="parts_type" class="form-control selectpicker" name="type" data-live-search="true" title="-- SELECT TYPE --">
                          <option value="">ALL TYPES</option>
                          @foreach($types as $tp)
                              <option value="{{$tp->id}}" {{isset($_GET['type']) ? $_GET['type'] == $tp->id ?   'selected' : '' : ''}}>{{$tp->value}}</option>
                          @endforeach
                        </select>
                        <select class="form-control selectpicker" name="brand" id="parts_brands" data-old="{{old('brand')}}" data-live-search="true" title="-- SELECT BRAND --">
                              <option value="">ALL BRANDS</option>
                        </select>
                       
                        <button type="submit" class="btn btn-default" name="option"><i class="fas fa-search"></i><span class="glyphicon glyphicon-search"></span>
                        </button>
                    </div>
                </form>

                <div class="table-responsive table-striped table-bordered" >
                <table id="table" class="table" style="width: 100%; table-layout: fixed;font-size:16px;">
                    <thead>
                        <tr>
                            <th>Part Type</th>
                        	  <th>Brand</th>               
                            <th>Model</th>
                            <th style="width:125px; text-align: center;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    	@foreach($res as $r)
                        <tr>
                            <td>{{$r->part}}</td> 
                            <td>{{$r->brand}}</td>                             
                            <td>{{$r->model}}</td>                        
                            <td>
                                <div class="row" style="margin-right: 0; margin-left: 0;">
                                  <div class="col-4" style="padding: 0;">
                                    <a href="#" class="btn btn-link view_image {{str_contains($menu[0]->actions,'R') ? '' : 'disabled' }}" style="width:40px; margin: 0" data-toggle="modal" data-src="{{asset('/images/part_brand')}}/{{$r->name_image}}" data-target="#exampleModalCenter"><i class="far fa-image"></i></a>
                                  </div>                                 
                                  <div class="col-4 active" style="padding: 0;">
                                    <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('ImagePartBrandController@destroy',$r->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                                  </div>                                  
                                </div>
                            </td>                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="width: 300px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Image</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body" style="padding: 0;">
          <img src="" id="view_image" alt="" style="width: 100%;">
        </div>
    </div>
  </div>
</div>

<script>
  function fillBrand(type, brands){
    $('#parts_brands').empty();
    $('#parts_brands').append('<option value=""></option>');
    for(var i=0; i < brands.length; i++){
      if(type == brands[i].lkp_id){
        $('#parts_brands').append('<option value="'+brands[i].brand_id+'">'+brands[i].brand+' '+brands[i].model+'</option>');
      }
    }
    $("#parts_brands").selectpicker("refresh");
  }

  function selectionBrand(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#parts_brands option[value='" + e + "']").prop("selected", true);
      });
      $("#parts_brands").selectpicker("refresh");
  }

  window.onload = function() {
     if($('#parts_type').val() != ""){
        fillBrand($('#parts_type').val(), {!!$brands!!});
        @if(isset($_GET['brand']))
          selectionBrand("{{$_GET['brand']}}");
        @endif
     }
  };

  $("body").on("click",".view_image",function(){
        $(document.getElementById("view_image")).attr("src",$(this).attr("data-src"));
    });
  
</script>
@stop
