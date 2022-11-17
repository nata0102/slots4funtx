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
                <select class="form-control selectpicker" id="client" name="client" style="width: 100%;" data-live-search="true" required onchange="address(this)">
                  <option value="" selected disabled>-- Select Client-Business --</option>
                  @foreach($clients as $client)
                    <option value="{{$client->id}}" data-address_id="{{$client->address_id}}" {{ $data ? $data["client"] == $client->id ? "selected":"" : ''}}>{{$client->name}} - {{$client->business_name}}</option>
                  @endforeach
                </select>
                <input type="hidden" name="address_id" value="" id="address_id">
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

            <div class="row">
              <div class="col-12 col-sm-4 form-group">
                <div class="" id="machines">


                  <div class="" id="select_content" hidden>
                    <label for="">Machines</label>
                    <select class="form-control selectpicker" data-live-search="true" multiple="multiple" name="machine_id[]" id="charge_machine" title="SELECT - MACHINES" >
                      <option value="">ALL</option>
                      @foreach($machines as $machine)
                        <option value="{{$machine->id}}">{{$machine->name_machine}}</option>
                      @endforeach
                    </select>
                  </div>

                </div>

              </div>
            </div>

            <div class="row" id="input" hidden>

                <div class="col-12 col-sm-4 form-group">
                  <label for="">Discount:</label>
                  <input class="form-control" id="discount" type="number" name="discount" value="" required style="width: 100%;">
                </div>

                <div class="col-12 col-sm-4 form-group">
                  <label for="">Payment Client:</label>
                  <input class="form-control" id="payment_client" type="number" name="payment_client" value="" required style="width: 100%;">
                </div>

                <div class="col-12 form-group">
                  <button type="button" class="btn btn-success" id="save" onclick='saveF()'>Save</button>
                </div>

            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


@stop

<script>

  search = "{{action('InvoiceController@machines')}}";
  store = "{{action('InvoiceController@store')}}";
  div_refresh = "machines";
  form_id = "form";

  function address(e){
    document.getElementById("address_id").value = e.options[e.selectedIndex].getAttribute("data-address_id");

  }






  function saveF(){
    document.getElementById("discount").setAttribute('required','');
    document.getElementById("payment_client").setAttribute('required','');


      document.getElementById("form").setAttribute('action',store);
      if (document.getElementById("form").checkValidity()) {
        document.getElementById("form").submit();
      }
      else{
        alert("Completa los campos.");
      }
  }

  function searchF(){

    document.getElementById("discount").removeAttribute('required');
    document.getElementById("payment_client").removeAttribute('required');
    document.getElementById("form").setAttribute('action',search);
    document.getElementById("input").setAttribute('hidden','');


    var dataString = $('#'+form_id).serialize();
    $.ajax({
      dataType: 'json',
      type:'POST',
      url: search,
      cache: false,
      data: dataString,
      success: function(){
        toastr.success('Información actualizada correctamente.', '', {timeOut: 3000});
        $("#"+div_refresh).load(" #"+div_refresh);
      },
      error: function(){
        toastr.error('Hubo un problema por favor intentalo de nuevo mas tarde.', '', {timeOut: 3000});
      }
    });


    setTimeout(() => {
      document.getElementById("select_content").removeAttribute('hidden');
      $('.selectpicker').selectpicker('refresh');

      document.getElementById("input").removeAttribute('hidden');
    }, 1000);

  }

</script>
