@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>

          <form class="" action="{{action('GameCatalogController@update',$res->id)}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="row">

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Software Name <span style="color:red">*</span></label>
                  <input type="text" class="form-control @error('name') is-invalid @enderror input100" name="name" value="{{$res->name}}" required>
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
                  <input type="text" class="form-control @error('license') is-invalid @enderror input100" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return valideKey(event);" id="sfw_license" name="license" value="{{$res->license}}">
                  @error('license')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <input type="hidden" name="band_select" value="0" />
                  <label><input type="checkbox" class="@error('band_select') is-invalid @enderror input100" name="band_select" value="1" {{$res->band_select == 1 ? 'checked' : ''}} /><span style="margin-left:10px">Allow Select Games</span></label>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Brands</label>
                  <select class="form-control selectpicker show-menu-arrow @error('brands') is-invalid @enderror input100" data-style="form-control" data-live-search="true" title="-- Select Brands --" multiple="multiple" name="brands_ids[]">
                  @foreach($brands as $brand)
                    <option  {{ (collect($brands_ids)->contains($brand->id)) ? 'selected':'' }}  value="{{$brand->id}}">{{$brand->brand}} - {{$brand->model}}</option>
                  @endforeach
                  </select>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Description</label>
                  <textarea class="form-control" name="description" value="{{$res->description}}">{{$res->description}}</textarea>
                </div>
              </div>

               <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                </div>
              </div>
 
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Name Game</label>
                  <input type="text" class="form-control @error('game_name') is-invalid @enderror input100" id="game_name" name="game_name" value="{{$res->game_name}}">
                  @error('game_name')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">License Game</label>
                  <input type="text" class="form-control @error('game_license') is-invalid @enderror input100" id="game_license" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" onkeypress="return valideKey(event);" name="game_license" value="{{old('game_license')}}" >
                  @error('game_license')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for=""></label>
                  <button type="button" onclick="addGame()" class="btn btn-info" style="width: 40px; margin-top: 35px; position: absolute;"><i class="fas fa-plus"></i></button>
                </div>
              </div>

            <div style="margin-top: 40px;" class="table-responsive table-striped table-bordered" id="table">
              <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0; margin-top: 18px;">
                <table id="table_games" class="table" style="width: 100%; table-layout: fixed;">
                  <thead>
                    <tr>
                      <th style="text-align: left;">Game</th>
                      <th style="text-align: left;">License</th>
                      <th style="width:45px; text-align: center;"></th>
                    </tr>
                  </thead>
                  <tbody>  
                  </tbody>
                </table>
              </div>

              <input onchange="fillTable()" type="hidden" class="form-control" id="games" name="games" value="{{$res->games}}" >

              <div style="margin-top: 10px;" class="col-12">
                <div class="form-group">
                  <button type="submit" class="btn btn-success">Save</button>
                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!--<script>-->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

  <script>
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
      cell3.innerHTML = '<div class="row" style="margin-right: 0; margin-left: 0;"><div class="col-4 active" style="padding: 0;"><button onclick="deleteGame('+rowCount+')" class="btn btn-link" style="width:40px; margin: 0; padding: 0;"><i class="far fa-trash-alt"></i></button></div></div>'; 
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

    window.onload = function() {
      let games = document.getElementById("games");
      var resume_table = document.getElementById("table_games");
      var arr1 = games.value.split('&$');
      for(var i=0;i < arr1.length; i++){
        if(arr1[i] != ""){
          var arr2 = arr1[i].split('|$');
          insertRow(arr2[0], arr2[1]);
        }
      }  
    };

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
  </script>
  @stop
