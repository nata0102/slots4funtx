@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <div class="input-group mb-2">
            <a href="{{action('MachineBrandController@create')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;"><i class="fas fa-plus"></i></a>
          </div>

          <form method="GET" action="{{action('MachineBrandController@index')}}">
              <div class="" style="position: absolute; right: 90px; margin: 10px 0; top: 13px;">
                <input type="hidden" name="active" value="{{ isset($_GET['active']) ? $_GET['active'] : '1' }}" id="check-input">
                <label for="check-active"><input onclick="checkclic();" type="checkbox" class="check-active" value="1" data-id="active" id="check-active"> Inactive</label>
              </div>
              <div class="input-group mb-5">
                 <select onchange="fillMachineBrands(this.value)" class="form-control" name="type" id="type">
                      <option value="" >-- Select Type --</option>
                      @foreach($types as $tp)
                          <option value="{{$tp->id}}" {{ isset($_GET['type']) ? $_GET['type'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                      @endforeach
                  </select>

                  <select onchange="fillBrand(this.value,this.selectedIndex)" class="form-control selectpicker" title="-- Select Part --" id="part" data-live-search="true" name="part_id" hidden>
                      <option value=""></option>
                      @foreach($parts as $tp)
                          <option value="{{$tp->id}}" {{ isset($_GET['part_id']) ? $_GET['part_id'] == $tp->id ? 'selected' : '' : ''}}>{{$tp->value}}</option>
                      @endforeach
                  </select>

                  <select class="form-control selectpicker" title="-- Select Brand --" data-live-search="true" id="machine" name="brand_type" hidden>
                      <!--<option value=""></option>
                      @foreach($brands_types as $tp)
                          <option value="{{$tp->brand}}" {{ isset($_GET['brand_type']) ? $_GET['brand_type'] == $tp->brand ? 'selected' : '' : ''}}>{{$tp->brand}}</option>
                      @endforeach-->
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
                    @if($brand->lkp_part_id == null)
                      <td>{{$brand->type->value}}</td>
                    @else
                      <td>{{$brand->type->value}} => {{$brand->part->value}}</td>
                    @endif
                    <td>{{$brand->brand}}</td>
                    <td>{{$brand->model}}</td>
                    <!--td>{{$brand->weight}}</td-->
                    <td>
                      <div class="row" style="margin-right: 0; margin-left: 0;">

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                          <a href="{{action('MachineBrandController@edit',$brand->id)}}" class="btn btn-link" style="width:40px; margin: 0"><i class="far fa-edit"></i></a>
                        </div>

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 0 ? 'hidden' : '' : '' }} class="col-4 active" style="padding: 0;">
                          <button class="delete-alert btn btn-link" data-reload="1" data-table="#table" data-message1="You won't be able to revert this!" data-message2="Deleted!" data-message3="Your file has been deleted." data-method="DELETE" data-action="{{action('MachineBrandController@destroy',$brand->id)}}" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>
                        </div>

                        <div {{ isset($_GET['active']) ? $_GET['active'] == 1 ? 'hidden' : '' : 'hidden' }} hidden class="col-8 inactive" style="padding: 0;">
                          <button class="delete-alert btn btn-link" data-reload="0" data-table="#table" data-message1="Are you sure to activate this brand?" data-message2="Activated" data-message3="Activated brand." data-method="DELETE" data-action="{{action('MachineBrandController@destroy',$brand->id)}}" style="width:40px; margin: 0; padding: 0"><i class="fas fa-check"></i></button>
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
      if(type_id==53){
        $('#machine').append('<option value=""></option>'); 
        var brands = {!!$brands_types!!};console.log(brands);
        if(brands){
          for(var i =0; i<brands.length; i++)
            $('#machine').append('<option value="'+brands[i].brand+'">'+brands[i].brand+'</option>'); 
        }  
      }
      $("#machine").selectpicker("refresh");
    }

    function fillBrand(part_id,index){
      $('#machine').selectpicker('show');
      index = index-2;    
      if(part_id != ""){                    
        $('#machine').empty();
        $('#machine').append('<option value=""></option>'); 
        var brands = {!!$parts!!}[index].brands;
        if(brands){
          for(var i =0; i<brands.length; i++){
            var cad="";
            var cad_id="";
            if(brands[i].brand){
              cad += brands[i].brand;
              cad_id = brands[i].brand;
            }
            if(brands[i].model){
              cad += brands[i].model;
              if(cad_id == "")
                cad_id = brands[i].model;
            }
            $('#machine').append('<option value="'+cad_id+'">'+cad+'</option>'); 
          }
        }        
      }
      $("#machine").selectpicker("refresh");
    }
    window.onload = function() {
      @if(isset($_GET['type']))
        var type = {!!$_GET['type']!!};
        console.log(type);
        if(type == 54){
          console.log("Entre 54");
          $('#part').selectpicker('show');
          var part = document.getElementById("part");  
          fillBrand(part.value,part.selectedIndex);
        }else{
          if(type == 53)
            fillMachineBrands(type);
        }
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

    function selectionBrand(value){
      var arr = [value];
      $.each(arr, function(i,e){
        $("#machine option[value='" + e + "']").prop("selected", true);
      });
      $("#machine").selectpicker("refresh");
    }
  </script>

  @stop
