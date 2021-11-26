@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          
          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          
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
                  <label for="">Game <span style="color:red">*</span></label>
                  <select id="select_game" onchange="fillContainedGames(this.value, this.selectedIndex)" class="form-control selectpicker @error('lkp_game_id') is-invalid @enderror input100" name="lkp_game_id" title="-- Select Game --" required=""  data-live-search="true">
                      <option value=""></option>
                      @foreach($games as $game)
                        <option value="{{$game->id}}" {{ old('lkp_game_id') == $game->id ? 'selected' : '' }}>{{$game->name}}</option>
                      @endforeach
                  </select>
                  @error('lkp_game_id')
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
                  <textarea onkeydown="return false;" id="contained_games_2"></textarea>
                  <textarea onkeydown="return false;" id="text_games" name="games"  value="{{old('games')}}" hidden></textarea>
                </div>
              </div>     

              <div class="col-12 col-sm-6 col-md-4" id="div_description_game" hidden>
                <div class="form-group">
                  <label for="">Description Game</label>
                  <textarea disabled id="description_game"></textarea>
                </div>
              </div>   

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brand</label>
                  <select onchange="changeBrand(this.value)" id="machine_brands" class="form-control selectpicker @error('machine_brand_id') is-invalid @enderror input100" name="machine_brand_id" title="-- Select Brand --" data-live-search="true">
                    <!--<option value="">OTHER</option>-->
                  </select>
                </div>
              </div>

              <input type="text" name="id_save_m" id="id_save_m" hidden="" value="{{old('id_save_m')}}">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Serial</label>
                  <input type="text" class="form-control @error('serial') is-invalid @enderror input100" name="serial" value="{{old('serial')}}">
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
                  <select class="form-control @error('address_id') is-invalid @enderror input100" name="address_id">
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
                  <label for="">Parts</label>
                  <select class="form-control selectpicker show-menu-arrow @error('parts') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Part --" multiple="multiple" name="parts_ids[]" id="parts_id_aux">
                  @foreach($parts as $part)
                    <option  {{ (collect(old('parts_ids'))->contains($part->id)) ? 'selected':'' }}  value="{{$part->id}}">{{$part->serial}} - {{$part->value}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Notes</label>
                  <textarea class="form-control" name="notes" value="{{old('notes')}}"></textarea>
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

  function changeBrand(brand_value){
      document.getElementById("id_save_m").value=brand_value;
      if(brand_value == ""){
          $('#machine_brands').empty();
          for(var i=0; i<{!!$brands!!}.length; i++){
            $('#machine_brands').append('<option value="'+{!!$brands!!}[i].id+'">'+{!!$brands!!}[i].brand+" "+{!!$brands!!}[i].model+'</option>');
          }
          $('#machine_brands').append('<option value="">OTHER</option>');
          $("#machine_brands").selectpicker("refresh");
      }
  }

  function fillContainedGames(game, index) {
      //LLena combo de description Games
      index=index-1;
      $('#contained_games').empty();
      var arr1 = {!!$games!!}[index].games.split("&$");
      if({!!$games!!}[index].band_select == 1){
          document.getElementById("div_contained_games").hidden=false; 
          document.getElementById("div_contained_games_2").hidden=true;        
      
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
          $("#contained_games").selectpicker("refresh");
      }else{
          document.getElementById("div_contained_games").hidden=true; 
          var container2 = document.getElementById("div_contained_games_2");
          container2.hidden=false;
          container2.value = {!!$games!!}[index].games;
          var cad_final = "";
          for(var i=0; i< arr1.length; i++){
              if(arr1[i] != ""){
                  var arr2 = arr1[i].split('|$');
                  cad_final += arr2[0]+" "+arr2[1]+'\n';
              }
          } 

          document.getElementById("contained_games_2").value = cad_final;
          document.getElementById("text_games").value  = {!!$games!!}[index].games;
      } 
      if({!!$games!!}[index].description != null && {!!$games!!}[index].description != ""){
          document.getElementById("div_description_game").hidden=false;
          document.getElementById("description_game").value = {!!$games!!}[index].description;
      }else
        document.getElementById("div_description_game").hidden=true;

      //LLena combo de Brands
      $('#machine_brands').empty();
      for(var i=0; i<{!!$games!!}[index].brands.length; i++){
           $('#machine_brands').append('<option value="'+{!!$games!!}[index].brands[i].machine_brand_id+'">'+{!!$games!!}[index].brands[i].brand.brand+" "+{!!$games!!}[index].brands[i].brand.model+'</option>');
      }
      $('#machine_brands').append('<option value="">OTHER</option>');
      $("#machine_brands").selectpicker("refresh");
  }

  window.onload = function() {
      var select_game = document.getElementById("select_game");
      var m_brand = document.getElementById("machine_brands");
      var brand_id = document.getElementById("id_save_m").value;
      var text_games = document.getElementById("text_games").value;
     
      if(select_game.value != "")
          fillContainedGames(select_game.value,select_game.selectedIndex + 1);
      
      /*if(select_game.value != "" && brand_id != ""){
         var band = false;
         for(var i=0;i<m_brand.options.length; i++){
            if(m_brand.options[i].value == brand_id){
              $('#machine_brands').selectpicker('val', brand_id);
              band=true;
            }
         }
         if(!band){
            changeBrand("");
            m_brand = document.getElementById("machine_brands");
            for(var i=0;i<m_brand.options.length; i++){
              if(m_brand.options[i].value == brand_id)
                $('#machine_brands').selectpicker('val', brand_id);
           }
         }
         document.getElementById("id_save_m").value = brand_id;
         $("#machine_brands").selectpicker("refresh");
      }*/
  };
</script>
  @stop
