@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">
          <a href="{{url()->previous()}}" class="btn btn-info" style="width: 40px; margin-bottom: 10px"><i class="fas fa-long-arrow-alt-left"></i></a>
          <form class="" action="{{action('GameCatalogController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Software Name</label>
                  <input type="text" disabled class="form-control" name="name" value="{{$res->name}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Software License</label>
                  <input type="text" disabled class="form-control" name="license" value="{{$res->license}}">
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <input type="hidden" name="band_select" value="0" />
                  <label><input disabled type="checkbox" name="band_select" value="1" {{$res->band_select == 1 ? 'checked' : ''}} /><span style="margin-left:10px">Allow Select Games</span></label>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-md-4">
                <div class="form-group">
                  <label for="">Description</label>
                  <textarea disabled class="form-control" name="description">{{$res->description}}</textarea>
                </div>
              </div>

              <div class="table-responsive table-striped table-bordered">
                <h4 align="center">Initial Brands</h4>          
                <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0; margin-top: 18px;">
                  <table class="table" style="width: 100%; table-layout: fixed;">
                    <thead>
                      <tr>
                        <th style="text-align: left;">Brand</th>
                        <th style="text-align: left;">Model</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($brands as $brand)
                      <tr>
                          <td>{{$brand->brand->brand}}</td>
                          <td>{{$brand->brand->model}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

             <div style="margin-top: 10px;" class="table-responsive table-striped table-bordered" id="table">
              <h4 align="center">Games</h4>
              <div class="table-responsive table-striped table-bordered" style="font-size: 14px; padding: 0; margin-top: 18px;">
                <table id="table_games" class="table" style="width: 100%; table-layout: fixed;">
                  <thead>
                    <tr>
                      <th style="text-align: left;">Game</th>
                      <th style="text-align: left;">License</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

               <input type="hidden" class="form-control" id="games" name="games" value="{{$res->games}}" >

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
      cell1.innerHTML = val1;
      cell2.innerHTML = val2;
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
  </script>
  @stop
