@extends('layouts.layout')

@section('content')


<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="" id="card-section">

        <div class="form-group">
          <select onchange="val()" class="form-control" name="role" id="role">
            <option value="" selected disabled>Selecciona un rol</option>
            @foreach($roles as $r)
                <option value="{{$r->key_value}}" {{$r->key_value == $rol ? 'selected' : ''}}>{{$r->value}}</option>
            @endforeach
          </select>
        </div>

        @if($rol)

        <div class="card">
          <form class="" action="{{ action('RoleConfigurationController@rolesConfigurationSave') }}" method="post">
            @csrf

            <?php $r = 0; ?>
            @foreach($menus as $menu)
            <?php
              $mr = DB::table('menu_roles')->where('lkp_menu_id',$menu->id)->where('lkp_role_id',$role->id)->first();
            ?>
            <div class="" style="position: relative;">
              {{$menu->value}}
              <input type="hidden" value="{{$menu->id}}" name="menu[{{$r}}]">
              <input type="hidden" value="{{$role->id}}" name="role[{{$r}}]">

              <input type="hidden" name="read[{{$r}}]" value="0">
              <input value="1" type="checkbox" name="read[{{$r}}]" {{ $mr ? str_contains($mr->actions, 'R') ? 'checked' : '' : ''}} style="position: absolute; left: 160px; top: 5px;" id="{{$r}}-read" ><label for="{{$r}}-read" style="position: absolute; left: 175px; top: 0; margin: 0;">Read</label>

              <input type="hidden" name="create[{{$r}}]" value="0">
              <input value="1" type="checkbox" name="create[{{$r}}]" {{ $mr ? str_contains($mr->actions, 'C') ? 'checked' : '' : ''}} style="position: absolute; left: 260px; top: 5px;" id="{{$r}}-create" ><label for="{{$r}}-create" style="position: absolute; left: 275px; top: 0; margin: 0;">Create</label>

              <input type="hidden" name="update[{{$r}}]" value="0">
              <input value="1" type="checkbox" name="update[{{$r}}]" {{ $mr ? str_contains($mr->actions, 'U') ? 'checked' : '' : ''}} style="position: absolute; left: 360px; top: 5px;" id="{{$r}}-update" ><label for="{{$r}}-update" style="position: absolute; left: 375px; top: 0; margin: 0;">Update</label>

              <input type="hidden" name="delete[{{$r}}]" value="0">
              <input value="1" type="checkbox" name="delete[{{$r}}]" {{ $mr ? str_contains($mr->actions, 'D') ? 'checked' : '' : ''}} style="position: absolute; left: 460px; top: 5px;" id="{{$r}}-delete" ><label for="{{$r}}-delete" style="position: absolute; left: 475px; top: 0; margin: 0;">Delete</label>

            </div>
            <hr>
            <?php $r++; ?>

            @endforeach
            <br>
            <button type="submit" class="btn btn-success">Actualizar</button>
          </form>


        </div>

        @endif

      </div>
    </div>
  </div>
</div>

<script>
  r = "{{action('RoleConfigurationController@index')}}";
  function val() {
    d = document.getElementById("role").value;
    window.location.replace(r+'/'+d);
  }
</script>

@stop
