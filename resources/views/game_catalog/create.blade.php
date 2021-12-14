@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{session('urlBack')}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('GameCatalogController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Type <span style="color:red">*</span></label>
                  <select onchange="visibleControls(this.selectedIndex)" id="type_game_catalog" class="form-control @error('lkp_type_id') is-invalid @enderror input100" 
                  name="lkp_type_id" required>
                  <option value="">-- Select Type --</option>
                  @foreach($types as $type)
                        <option value="{{$type->id}}"  {{ old('lkp_type_id') == $type->id ? 'selected' : '' }}>{{$type->value}}</option>
                      @endforeach                    
                  </select>
                  @error('lkp_type_id')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Software Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror input100" name="name" value="{{old('name')}}" required>
                  @error('name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Software License</label>
                  <input type="text" class="form-control @error('license') is-invalid @enderror input100 find-serial" style="text-transform:uppercase;" pattern="[A-Za-z0-9]+" id="sfw_license" name="license" value="{{old('license')}}">
                  @error('license')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brands</label>
                  <select class="form-control selectpicker show-menu-arrow @error('brands') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Brands --" multiple="multiple" name="brands_ids[]">
                  @foreach($brands as $brand)
                    <option  {{ (collect(old('brands_ids'))->contains($brand->id)) ? 'selected':'' }}  value="{{$brand->id}}">{{$brand->brand}} - {{$brand->model}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Description</label>
                  <textarea class="form-control" name="description" value="{{old('description')}}"></textarea>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" hidden id="div_group1">
                <div class="form-group">
                  <input type="hidden" name="band_select" value="0" />
                  <label><input type="checkbox" id="check_band" class="@error('band_select') is-invalid @enderror input100" name="band_select" value="1" {{old('band_select') == 1 ? 'checked' : ''}} /><span style="margin-left:10px">Allow Select Games</span></label>
                </div>
              </div>

              
              <div class="col-12 col-sm-6 col-md-4" hidden id="div_group2">
                <div class="form-group">
                  <label for="">Name Game</label>
                  <input type="text" class="form-control @error('game_name') is-invalid @enderror input100" id="game_name" value="{{old('game_name')}}">
                  @error('game_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" hidden id="div_group3">
                <div class="form-group">
                  <label for="">License Game</label>
                  <input type="text" class="form-control @error('game_license') is-invalid @enderror input100 find-serial" id="game_license" style="text-transform:uppercase;" pattern="[A-Za-z0-9]+" value="{{old('game_license')}}" >
                  @error('game_license')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4" hidden id="div_group4">
                <div class="form-group">
                  <label for=""></label>
                  <button type="button" onclick="addGame()" class="btn btn-info" style="width: 40px; margin-top: 35px; position: absolute;"><i class="fas fa-plus"></i></button>
                </div>
              </div>

            <div style="margin-top: 40px;" class="table-responsive table-striped table-bordered"   id="table" hidden>
              <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0;">
                <table id="table_games" class="table" style="width: 100%; table-layout: fixed;">
                  <thead>
                    <tr>
                      <th style=" text-align: left;">Game</th>
                      <th style=" text-align: left;">License</th>
                      <th style="width:45px; text-align: center;"></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

              <div hidden>
                <input onchange="fillTable()" class="form-control" id="games" name="games" value="{{old('games')}}" >
              </div>

              
            </div>
            <div style="margin-top: 10px;" class="col-12">
                <div class="form-group">
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--<script>-->
  <script>
    function visibleControls(index){  
        var type = "";
        if(index > 0)
            type = {!!$types!!}[index-1].key_value;
        if(type == 'group'){
          document.getElementById("div_group1").hidden = false;
          document.getElementById("div_group2").hidden = false;
          document.getElementById("div_group3").hidden = false;
          document.getElementById("div_group4").hidden = false;
          document.getElementById("table").hidden = false;
        }else{
          document.getElementById("games").value = null;
          $("#table_games tr").remove(); 
          document.getElementById("div_group1").hidden = true;
          document.getElementById("div_group2").hidden = true;
          document.getElementById("div_group3").hidden = true;
          document.getElementById("div_group4").hidden = true;
          document.getElementById("table").hidden = true;
          document.getElementById("check_band").checked = false;         
        }

    }

    function insertRow(val1, val2){
      var table = document.getElementById("table_games");
      var rowCount = table.rows.length;
      var row = table.insertRow(rowCount);
      row.setAttribute("id", "td_"+rowCount);
      var cell1 = row.insertCell(0);
      var cell2 = row.insertCell(1);
      var cell3 = row.insertCell(2);
      cell1.innerHTML = val1;
      cell2.innerHTML = val2;
      cell3.innerHTML = '<button onclick="deleteGame('+rowCount+')" class="btn btn-link" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button>';
    }

    function addGame(){
      let game_name = document.getElementById("game_name");
      let game_license = document.getElementById("game_license");
      let games = document.getElementById("games");
      if(game_name.value != ""){
        insertRow(game_name.value,game_license.value);
        games.value += game_name.value+"|$";
        if(game_license!="")
          games.value += game_license.value+"&$";
        else
          games.value += "&$";
        game_name.value = "";
        game_license.value = "";
      }
    }

    function deleteGame(row_tr){
      var element = document.getElementById("td_"+row_tr);
      element.parentNode.removeChild(element);
      //Actualiza el input de games
      let games = document.getElementById("games");
      var resume_table = document.getElementById("table_games");
      games.value = "";
      for (var i = 1, row; row = resume_table.rows[i]; i++) {
        var col = row.cells[0];
        games.value += col.innerText+"|$";
        col = row.cells[1];
        if(col.innerText != "")
          games.value += col.innerText+"&$";
        else
          games.value += "&$";
      }
    }

    function fillTable(){
      let games = document.getElementById("games");
      var resume_table = document.getElementById("table_games");
      var arr1 = games.value.split('&$');
      for(var i=0;i < arr1.length; i++){
        if(arr1[i] != ""){
          var arr2 = arr1[i].split('|$');
          insertRow(arr2[0], arr2[1]);
        }
      }
    }

    function valideKey(evt){
        // code is the decimal ASCII representation of the pressed key.
        var code = (evt.which) ? evt.which : evt.keyCode;

        if(code==8) { // backspace.
          return true;
        } else if((code>=48 && code<=57) || (code >= 65 && code <= 90) || (code >= 97 && code <= 122)) { // is a number.
          return true;
        } else{ // other keys.
          return false;
        }
    }

    $('document').ready(function(){
      visibleControls($("#type_game_catalog option:selected").index());
      fillTable();
    });

  </script>
  @stop
