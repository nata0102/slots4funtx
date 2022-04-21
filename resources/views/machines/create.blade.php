@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <div>
            <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px;float: left"><i class="fas fa-long-arrow-alt-left"></i></a>

            <p style="margin-left: 120px;margin-top: 10px">ID: {{$id}}</p>
          </div>

          <form class="" action="{{action('MachineController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">             

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Owner <span style="color:red">*</span></label>
                  <select class="form-control @error('lkp_owner_id') is-invalid @enderror input100" name="lkp_owner_id" required="">
                      @foreach($owners as $owner)
                        <option value="{{$owner->id}}"  {{ old('lkp_owner_id') == $owner->id ? 'selected' : '' }}>{{$owner->value}}</option>
                      @endforeach
                  </select>
                  @error('lkp_owner_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Game Title</label>
                  <select id="select_game" onchange="fillContainedGames(this.value, this.selectedIndex)" class="form-control selectpicker @error('game_catalog_id') is-invalid @enderror input100" name="game_catalog_id" title="-- Select Game --" data-live-search="true">
                      <option value=""></option>
                      @foreach($games as $game)
                        <option value="{{$game->id}}" {{ old('game_catalog_id') == $game->id ? 'selected' : '' }}>{{$game->name}}</option>
                      @endforeach
                  </select>
                  @error('game_catalog_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="div_contained_games" hidden>
                <div class="form-group">
                  <label for="">Contained Games</label>
                  <select id="contained_games" class="form-control selectpicker show-menu-arrow @error('games_select') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Games --" multiple="multiple" name="games_select[]">
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="div_contained_games_2" hidden>
                <div class="form-group">
                  <label for="">Contained Games</label>
                  <textarea class="form-control" disabled onkeydown="return false;" id="contained_games_2"></textarea>
                  <textarea class="form-control" onkeydown="return false;" id="text_games" name="games"  value="{{old('games')}}" hidden></textarea>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" id="div_description_game" hidden>
                <div class="form-group">
                  <label for="">Description Game</label>
                  <textarea class="form-control" disabled id="description_game"></textarea>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  <div hidden>
                    <input  id="old_machine_brand_id" name="old_machine_brand_id" value="{{old('old_machine_brand_id')}}">
                  </div>
                  <select onchange="changeBrand(this.value)" id="machine_brands" class="form-control selectpicker @error('machine_brand_id') is-invalid @enderror input100" name="machine_brand_id" title="-- Select Brand --" data-live-search="true">
                    <option value="">OTHER</option>
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial</label>
                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100 find-serial" name="serial" value="{{old('serial')}}" style="text-transform:uppercase;" pattern="[A-Za-z0-9]+" >
                  @error('serial')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Address</label>
                  <select id="machine_address" class="form-control selectpicker @error('address_id') is-invalid @enderror input100" name="address_id" data-live-search="true" title="-- Select Client Address --">
                    <option value=""></option>
                      @foreach($addresses as $address)
                        <option value="{{$address->id}}" {{ old('address_id') == $address->id ? 'selected' : '' }}>{{$address->name}} - {{$address->name_address}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Status</label>
                  <select class="form-control @error('lkp_status_id') is-invalid @enderror input100" name="lkp_status_id">
                    <option value=""></option>
                      @foreach($status as $st)
                        <option value="{{$st->id}}" {{ old('lkp_status_id') == $st->id ? 'selected' : '' }}>{{$st->value}}</option>
                      @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Components</label>
                  <select class="form-control selectpicker show-menu-arrow @error('parts') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Part --" multiple="multiple" name="parts_ids[]" id="parts_id_aux">
                  @foreach($parts as $part)
                    <option  {{ (collect(old('parts_ids'))->contains($part->id)) ? 'selected':'' }}  value="{{$part->id}}">{{$part->id}} - {{$part->serial}} - {{$part->value}} - {{$part->brand}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Notes</label>
                  <textarea class="form-control" name="notes" value="{{old('notes')}}">{{old('notes')}}</textarea>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Inventory</label>
                  <input type="text" class="form-control @error('inventory') is-invalid @enderror input100 find-inventory" name="inventory" value="{{old('inventory')}}" style="text-transform:uppercase;" pattern="[A-Za-z0-9]+" >
                  @error('serial')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Image</label>
                  <div style="width: 110px; height: 110px; background: #fff; border-radius: 5px; margin: 0; cursor: pointer; overflow: hidden; position: relative;" class="input_img tomaFoto" data-id="img-btn-3" data-id2="img3" data-id3="img-new-3">
                    <img src="{{asset('/images/interface.png')}}" alt="" id="img3" style="width: 80%; height: auto; transform: translate(-50%, -50%); position: absolute; top: 50%; left: 50%;">
                  </div>
                  <input class="photo" type="file" name="image" value="{{old('image')}}" id="img-btn-3" data-id2="img-new-3" accept="image/*" hidden>
                  <input class="mg" type="text" value="" id="img-new-3" accept="image/*" hidden>
                </div>
              </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<script>

  function addOptionsSelectGames(games_cad){
    if(games_cad!= null && games_cad != ""){
      document.getElementById("div_contained_games").hidden=false;
      var arr1 = games_cad.split("&$");
      for(var i=0; i< arr1.length; i++){
          if(arr1[i] != ""){
              var arr2 = arr1[i].split('|$');
              $('#contained_games').append('<option value="'+arr1[i]+'" data {{ (collect(old("games_select"))->contains('+arr1[i]+')) ? "selected":"" }}>'+arr2[0]+" "+arr2[1]+'</option>');
          }
      }
      @if(old('games_select') != null)
        var count= "{{ count(old('games_select')) }}";
        var a3 = @json(old('games_select'));
        $.each(a3, function(i,e){
          $("#contained_games option[value='" + e + "']").prop("selected", true);
        });
      @endif
    }
  }

  function addOptionsTextArea(games_cad){
    if(games_cad!= null && games_cad != ""){
      var container2 = document.getElementById("div_contained_games_2");
      container2.hidden=false;
      container2.value = games_cad;
      var arr1 = games_cad.split("&$");
      var cad_final = "";
      for(var i=0; i< arr1.length; i++){
          if(arr1[i] != ""){
              var arr2 = arr1[i].split('|$');
              cad_final += arr2[0]+" "+arr2[1]+'\n';
          }
      }
      document.getElementById("contained_games_2").value = cad_final;
      document.getElementById("text_games").value  = games_cad;
    }
  }

  //Llena los games
  function fillContainedGames(game, index) {
      //LLena combo de description Games
      index = index-2;      
      $('#contained_games').empty();
      document.getElementById("text_games").value = "";
      document.getElementById("contained_games_2").value = "";
      document.getElementById("div_contained_games").hidden = true;
      document.getElementById("div_contained_games_2").hidden = true;
      document.getElementById("div_description_game").hidden = true;
      if(game != ""){
        var games_cad = {!!$games!!}[index].games;
        var band_select = {!!$games!!}[index].band_select;
        var type_game = {!!$games!!}[index].type.key_value;
        if(type_game == "group"){
            if(band_select == 1){
                addOptionsSelectGames(games_cad);
            }else{
                addOptionsTextArea(games_cad);
            }
        }        
        $("#contained_games").selectpicker("refresh");
        if({!!$games!!}[index].description != null && {!!$games!!}[index].description != ""){
            document.getElementById("div_description_game").hidden=false;
            document.getElementById("description_game").value = {!!$games!!}[index].description;
        }
      }
      fillSelectBrands(index);
  }

  function fillSelectBrands(index){
    //LLena combo de Brands
      $('#machine_brands').empty();
      if(index >= 0){
        for(var i=0; i<{!!$games!!}[index].brands.length; i++){
             $('#machine_brands').append('<option value="'+{!!$games!!}[index].brands[i].machine_brand_id+'">'+{!!$games!!}[index].brands[i].brand.brand+" "+{!!$games!!}[index].brands[i].brand.model+'</option>');
        }        
      }
      $('#machine_brands').append('<option value="">OTHER</option>');
      $("#machine_brands").selectpicker("refresh");
  }

  function changeBrand(brand_value){
      document.getElementById('old_machine_brand_id').value=brand_value;
      if(brand_value == "")
          fillOtherBrands();
  }

  function fillOtherBrands(){
    $('#machine_brands').empty();
    for(var i=0; i<{!!$brands!!}.length; i++){
      $('#machine_brands').append('<option value="'+{!!$brands!!}[i].id+'">'+{!!$brands!!}[i].brand+" "+{!!$brands!!}[i].model+'</option>');
    }
    $('#machine_brands').append('<option value="">OTHER</option>');
    $("#machine_brands").selectpicker("refresh");
  }

  function checkFillBrand(brand_id){
      if($("#machine_brands option[value='"+brand_id+"']").length == 0)
          fillOtherBrands();
      $.each([brand_id], function(i,e){
          $("#machine_brands option[value='" + e + "']").prop("selected", true);
      });
      $("#machine_brands").selectpicker("refresh");

  }

  function valideKey(e){
      // code is the decimal ASCII representation of the pressed key.
      /*var code = (evt.which) ? evt.which : evt.keyCode;

      if(code==8) { // backspace.
        return true;
      } else if((code>=48 && code<=57) || (code >= 65 && code <= 90) || (code >= 97 && code <= 122)) { // is a number.
        return true;
      } else{ // other keys.
        return false;
      }*/
  }

   $(document).ready(function() {
      $("#select_game").selectpicker("refresh");
      $("#machine_address").selectpicker("refresh");      
      var select_game = document.getElementById("select_game");
      if(select_game.value){
        fillContainedGames(select_game.value, select_game.selectedIndex);
        var brand_id = document.getElementById('old_machine_brand_id').value;
        if(brand_id != "")
            checkFillBrand(brand_id);
      }    
  });

</script>
  @stop
