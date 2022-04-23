@extends('layouts.layout')

@section('content')

<?php
  $menu= DB::select("select m.actions from menu_roles m, lookups l where m.lkp_role_id=".Auth::user()->role->id." and m.lkp_menu_id = l.id and l.key_value='Part';");
?>

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <div style="width: 50%;height: 40px;">
              <a href="{{action('PartController@create')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;float: left;"><i class="fas fa-plus"></i></a>
              <p style="margin-left: 10px;padding-top: 5px;font-weight: bold;">Add</p>
            </div>
            <div align="left" style="width: 50%;height: 40px;">
              <a href="{{action('PartController@createByRank')}}" class="btn btn-info {{str_contains($menu[0]->actions,'C') ? '' : 'disabled' }}" style="width: 40px; margin-bottom: 10px;float: left;"><i class="fas fa-plus"></i></a>
                    <p style="margin-left: 10px;padding-top: 5px;font-weight: bold;">By Rank</p>
            </div>
          </div>

          <form method="GET" action="{{action('PartController@index')}}">
              <div style="position: absolute; right: 90px; margin-top: 5px;">
                <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
              </div>
              <div style="margin-top: 40px" class="input-group mb-5">
                  <input type="hidden" class="form-control @error('machine') is-invalid @enderror input100" name="machine" id="machine" value="{{old('machine')}}">

                  <select class="form-control selectpicker" data-live-search="true" multiple="multiple" name="machines_ids[]" id="machines_ids" onChange="getSelectedOptions(this)">
                      <option disabled selected>SELECT GAMES</option>
                      <option value="-1">NOT ASSIGNED</option>
                        @foreach($machines as $tp)
                          <option value="{{$tp->id}}"  {{ isset($_GET['machine']) ? $_GET['machine'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->id}} - {{$tp->owner}} - {{$tp->game}} - {{$tp->serial}}</option>
                        @endforeach
                  </select>

                  <select onchange="fillBrand(this.value, {{$brands}})" id="parts_type" class="form-control selectpicker" name="type" data-live-search="true" title="-- SELECT TYPE --">
                      <option value="">ALL TYPES</option>
                      @foreach($types as $tp)
                          <option value="{{$tp->id}}" {{isset($_GET['type']) ? $_GET['type'] == $tp->id ?   'selected' : '' : ''}}>{{$tp->value}}</option>
                      @endforeach
                  </select>
                  <select class="form-control selectpicker" name="brand" id="parts_brands" data-old="{{old('brand')}}" data-live-search="true" title="-- SELECT BRAND --">
                      <option value="">ALL BRANDS</option>
                  </select>

                  <select class="form-control" name="status" >
                      <option value="">ALL STATUS</option>
                        @foreach($status as $tp)
                          <option value="{{$tp->id}}"  {{ isset($_GET['status']) ? $_GET['status'] == $tp->id ? 'selected' : '' : '' }}>{{$tp->value}}</option>
                        @endforeach
                  </select>

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
                      <th style="width:100px; text-align: center;">Brand-Model</th>
                      <th style="width:150px; text-align: center;">Details</th>
                      <th style="width:100px; text-align: center;">Serial</th>
                      <th style="width:85px; text-align: center;">Price</th>
                      <th style="width:135px; text-align: center;">Status</th>
                      <th style="width:175px; text-align: center;">Machine</th>
                    	<th style="width:125px; text-align: center;"></th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($parts as $part)
                    <tr>
                      @if($part->type != NULL)
                        <td>{{$part->type->value}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->brand_id != NULL)
                        <td>{{$part->brand->brand}} {{$part->brand->model}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->details != NULL)
                        <td>
                        @foreach($part->details as $detail)
                          <p>{{$detail->detail->value}}</p>
                        @endforeach
                        </td>
                      @else
                        <td></td>
                      @endif
                      <td>{{$part->serial}}</td>
                      <td>${{number_format($part->price,'2','.',',')}}</td>
                      @if($part->status != NULL)
                        <td>{{$part->status->value}}</td>
                      @else
                        <td></td>
                      @endif
                      @if($part->machine_id != null)
                        @if($part->machine->machine_brand_id != null)
                          @if($part->machine->game_catalog_id != null)
                            <td>{{$part->machine_id}} - {{$part->machine->owner->value}} - {{$part->machine->game->name}} - {{$part->machine->serial}}</td>
                          @else
                            <td>{{$part->machine_id}} - {{$part->machine->owner->value}} -  {{$part->machine->serial}}</td>
                          @endif
                        @else
                          <td></td>
                        @endif
                      @else
                        <td></td>
                      @endif
                      <td>
                        <div class="row" style="margin-right: 0; margin-left: 0;">
                          <div class="col-3" style="padding: 0;">
                            <a href="{{action('PartController@show',$part->id)}}" 
                              class="btn btn-link {{str_contains($menu[0]->actions,'R') ? '' : 'disabled'}}" style="width:40px; margin: 0"><i class="far fa-eye"></i></a>
                          </div>

                          <div class="col-3" style="padding: 0;">
                            <a href="{{action('PartController@gallery',$part->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'U') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-images"></i></a>
                          </div>

                          <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-3 active" style="padding: 0;">
                            <a href="{{action('PartController@edit',$part->id)}}" class="btn btn-link {{str_contains($menu[0]->actions,'U') ? '' : 'disabled' }}" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                          </div>

                          <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-3 active" style="padding: 0;">
                            <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('PartController@destroy',$part->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                          </div>

                          <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }}  class="col-6 inactive" style="padding: 0;">
                            <button class="delete-alert btn btn-link {{str_contains($menu[0]->actions,'D') ? '' : 'disabled' }}" data-reload="0" data-table="#table" data-message1="Are you sure to activate this part?" data-message2="Activated" data-message3="Activated part." data-method="DELETE" data-action="{{action('PartController@destroy',$part->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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

  function getSelectedOptions(sel) {
      var opts = [],opt;
      var len = sel.options.length;
      var ids = document.getElementById("machine");
      
      for (var i = 1; i < len; i++) {
        opt = sel.options[i];
        if (opt.selected) 
            opts.push(opt.value);
      }          
      ids.value = opts.toString();
    }

    function fillGames(ids){
        var arr = ids.split(",");
        $.each(arr, function(i,e){
            $("#machines_ids option[value='" + e + "']").prop("selected", true);
        });
        $("#machines_ids").selectpicker("refresh");
        document.getElementById("machine").value = ids;
    }


  window.onload = function() {
     if($('#parts_type').val() != ""){
        fillBrand($('#parts_type').val(), {!!$brands!!});
        @if(isset($_GET['brand']))
          selectionBrand("{{$_GET['brand']}}");
        @endif
     }

    @if (isset($_GET['machine'])) 
        fillGames("{{$_GET['machine']}}");
    @endif
  };
</script>
@stop
