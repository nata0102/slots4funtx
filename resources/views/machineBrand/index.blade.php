@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='MachineBrand';");
?>

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <a href="{{action('MachineBrandController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
          </div>

          <form method="GET" action="{{action('MachineBrandController@index')}}">
              <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
              </div>
              <div class="input-group mb-5">
                 <select onchange="fillMachineBrands(this.value)" class="form-control" name="type" id="type">
                      <option value="" >ALL TYPES</option>
                      @foreach($types as $tp)
                          <option value="{{$tp->id}}" {{ isset($_GET['type']) ? $_GET['type'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                      @endforeach
                  </select>

                  <select class="form-control selectpicker" title="-- SELECT BRAND --" data-live-search="true" id="machine" name="brand_type" hidden>
                  </select>

                  <input class="form-control" type="text" name="model" value="{{ isset($_GET['model']) ? $_GET['model'] : '' }}" placeholder="Model">

                  <button type="submit" class="btn btn-default" name="option" value="all"><i class="fas fa-search"></i>
                      <span class="glyphicon glyphicon-search"></span>
                  </button>
              </div>
          </form>

          <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0;">
            <table id="table" class="table" style="width: 100%; table-layout: fixed;">
              <thead>
                <tr>
                  <th style="width:100px; text-align: center;">Type</th>
                  <th style="width:100px; text-align: center;">Brand</th>
                  <th style="width:100px; text-align: center;">Model</th>
                  <!--th style="width:70px; text-align: center;">Weight</th-->
                	<th style="width:125px; text-align: center;"></th>
                </tr>
              </thead>
              <tbody>
              	@foreach($brands as $brand)
                  <tr>
                    <td>{{$brand->type->value}}</td>
                    <td>{{$brand->brand}}</td>
                    <td>{{$brand->model}}</td>
                    <!--td>{{$brand->weight}}</td-->
                    <td>
                      <div class="row" style="margin-right: 0; margin-left: 0;">

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                          <a href="{{action('MachineBrandController@edit',$brand->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'U') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                        </div>                        

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                          <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('MachineBrandController@destroy',$brand->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                        </div>

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} hidden class="col-8 inactive" style="padding: 0;">
                          <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="0" data-table="#table" data-message1="Are you sure to activate this brand?" data-message2="Activated" data-message3="Activated brand." data-method="DELETE" data-action="{{action('MachineBrandController@destroy',$brand->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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

  <script>
    function fillMachineBrands(type_id){
      $('#machine').empty();
      if(type_id){
        $('#machine').append('<option value="">ALL BRANDS</option>');
        var brands = {!!$brands_types!!};
        if(brands){
          for(var i =0; i<brands.length; i++){
            if(brands[i].lkp_type_id == type_id)
              $('#machine').append('<option value="'+brands[i].brand+'">'+brands[i].brand+'</option>');
          }
        }
      }
      $("#machine").selectpicker("refresh");
    }

    function selectionBrand(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#machine option[value='" + e + "']").prop("selected", true);
      });
      $("#machine").selectpicker("refresh");
    }

    window.onload = function() {
      @if(isset($_GET['type']))
        @if($_GET['type'])
          var type = {!!$_GET['type']!!};
          console.log(type);
          if(type != "")
            fillMachineBrands(type);
        @endif

        @if(isset($_GET['brand_type']))
            @if($_GET['brand_type'])
              var brand_id = "{!!$_GET['brand_type']!!}";
              console.log(brand_id);
              if(brand_id)
                selectionBrand(brand_id);
            @endif
        @endif
      @endif
    };
  </script>

  @stop
