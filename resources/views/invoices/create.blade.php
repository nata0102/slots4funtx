@extends('layouts.layout')

@section('content')

  <div class="main-content" >
    <div class="section__content section__content--p30">
      <div class="container-fluid">

        <form class="" action="" method="post" id="form">
          @csrf


          <div class="card" id="card-section">
            <div class="row">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Type:</label>
                <select class="form-control selectpicker" name="type" style="width: 100%;" id="type" data-live-search="true" required>
                  <option value="" selected disabled>-- Select Type --</option>
                  @foreach($types as $type)
                    <option value="{{$type->key_value}}" {{ $data ? $data["type"] == $type->key_value ? "selected":"" : ''}}>{{$type->value}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Client:</label>
                <select class="form-control selectpicker" id="client" name="client" style="width: 100%;" data-live-search="true" required>
                  <option value="" selected disabled>-- Select Client-Business --</option>
                  @foreach($clients as $client)
                    <option value="{{$client->id}}" {{ $data ? $data["client"] == $client->id ? "selected":"" : ''}}>{{$client->name}} - {{$client->business_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-sm-3 form-group">
                <label for="">from:</label>
                <input class="form-control" type="date" name="from" value="{{ $data ? $data['from'] : ''}}" required>
              </div>
              <div class="col-12 col-sm-3 form-group">
                <label for="">to:</label>
                <input class="form-control" type="date" name="to" value="{{ $data ? $data['to'] : ''}}" required>
              </div>
              <div class="col-12 col-sm-1 form-group">
                <button type="button" class="btn" style="margin-top: 37px;" id="search" onclick='searchF()'><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>

            <div class="row" id="machines">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Machines</label>
                <select class="form-control selectpicker" data-live-search="true" multiple="multiple" name="machine_id[]" id="charge_machine" title="SELECT - MACHINES" >
                  <option value="">ALL</option>
                  @foreach($machines as $machine)
                    <option value="{{$machine->id}}">{{$machine->name_machine}}</option>
                  @endforeach
                </select>
              </div>
            </div>

            @if(count($data)>0)
            <div class="row">
              <div class="col-12 col-sm-4 form-group">
                <label for="">Discount:</label>
                <input class="form-control" type="number" name="discount" value="" required>
              </div>

              <div class="col-12 col-sm-4 form-group">
                <label for="">Payment Client:</label>
                <input class="form-control" type="number" name="payment_client" value="" required>
              </div>

              <div class="col-12 form-group">
                <button type="button" class="btn btn-success" id="save" onclick='saveF()'>Save</button>
              </div>
            </div>
              @endif
          </div>
        </form>
      </div>
    </div>
  </div>


@stop

<script>

  search = "{{action('InvoiceController@machines')}}";
  store = "{{action('InvoiceController@store')}}";

  function saveF(){
      document.getElementById("form").setAttribute('action',store);
      if (document.getElementById("form").checkValidity()) {
        document.getElementById("form").submit();
      }
      else{
        alert("Completa los campos.");
      }
  }

  function searchF(){
    document.getElementById("form").setAttribute('action',search);
    if (document.getElementById("form").checkValidity()) {
      document.getElementById("form").submit();
    }
    else{
      alert("Completa los campos.");
    }
  }

</script>
