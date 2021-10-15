@extends('layouts.layout')

@section('content')

  <div class="main-content">
    <div class="section__content section__content--p30">
      <div class="container-fluid">
        <div class="card" id="card-section">

          <form class="" action="{{action('MachineController@store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
              <label for="">Game Title</label>
              <input type="text" class="form-control" name="game_title" value="">
            </div>

            <div class="form-group">
              <label for="">Owner</label>
              <select class="form-control" name="lkp_owner_id">
                <option value=""></option>
                  @foreach($owners as $owner)
                    <option value="{{$owner->id}}">{{$owner->value}}</option>
                  @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Serial</label>
              <input type="text" class="form-control" name="serial" value="">
            </div>

            <div class="form-group">
              <label for="">Inventory</label>
              <input type="text" class="form-control" name="inventory" value="">
            </div>

            <div class="form-group">
              <label for="">Address</label>
              <select class="form-control" name="address_id">
                <option value=""></option>
                  @foreach($addresses as $address)
                    <option value="{{$address->id}}">{{$address->name}} - {{$address->name_address}}</option>
                  @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Status</label>
              <select class="form-control" name="lkp_status_id">
                <option value=""></option>
                  @foreach($status as $st)
                    <option value="{{$st->id}}">{{$st->value}}</option>
                  @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Price</label>
              <input type="number" class="form-control" name="price" value="">
            </div>

            <div class="form-group">
              <label for="">Brand</label>
              <select class="form-control" name="machine_brand_id">
                <option value=""></option>
                  @foreach($brands as $brand)
                    <option value="{{(int)$brand->id}}">{{$brand->brand}} {{$brand->model}} {{$brand->weight}}</option>
                  @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Parts</label>
              <select class="selectpicker show-menu-arrow" data-style="form-control"                     data-live-search="true" title="-- Select Part --" multiple="multiple" name="parts[]">
              @foreach($parts as $part)
                <option value="{{$part->id}}">{{$part->serial}} - {{$part->value}}</option>
              @endforeach
              </select>
            </div>

            <div class="form-group">
              <label for="">Image</label>
              <input type="file" name="image" value="" accept="image/*">
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  @stop
