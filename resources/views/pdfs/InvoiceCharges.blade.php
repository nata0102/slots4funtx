<!DOCTYPE html>
<html lang="es-ES">
  <head>
    <meta charset="utf-8">
    <title>Slots4Fun</title>
    <style>
        body {
            font-family:"Trebuchet MS", Helvetica, sans-serif;
            font-size: 14px;            
        }
        table {
            width: 100%;
            margin-bottom: 15px;
            text-align: left;
            table-layout:fixed;        }
        th {
            color: #FFF;
            background-color: #2E2E2D;
            padding: 2px 5px;
            font-size: 9px;
        }
        td {
            padding-left: 5px;
            padding-top: 5px;
        }
        .bold {
          font-weight: bold;
        }

        footer {
            position: fixed; 
            bottom: -60px; 
            left: 0px; 
            right: 0px;
            height: 70px; 

            /** Extra personal styles **/
            background-color: gray;
            color: white;
            text-align: center;
            line-height: 20px;
        }
       
  </style>
  </head>
  @if($res['invoice']['band_cancel'] == 0)
    <body>
  @else
    <body style="background-image: url('../public/images/cancelled.jpg');background-size: 100%;">  
  @endif 
    <div style="margin-left: 50px;margin-top: 0px">
      <h2>{{$res['invoice']['date_invoice']}}</h2>
      <h2 style="margin-top: 30px">INVOICE</h2>
    </div>

     <div style="margin-top:-200px;" align="right">  
        <img align="right" src="../public/images/logo-black.png"  style="margin-top:-35px; padding: 0px; width: 200px; margin-left:500px;" />
      </div>     

    <div style="margin-left: 0px;">
      <p><span class="bold">INVOICE:</span> {{$res['invoice']['folio']}}</p>
      <p><span class="bold">COMPANY NAME:</span> {{$res['invoice']['address']['business_name']}}</p>
      <p><span class="bold">CLIENT NAME:</span> {{$res['invoice']['client']['name']}}</p>  
      <p><span class="bold">ADDRESS:</span> {{$res['invoice']['address']['name_address']}}, {{$res['invoice']['address']['county']['value']}},{{$res['invoice']['address']['city']['value']}}</p>
      <p><span class="bold">PERSON IN CHARGE:</span> EDUARDO GUTIERREZ NAJERA</p>
    </div>

    <div class="table-responsive col-md-12" style="padding: 10px;font-size: 14px; margin-left: -20px;">
        <table class="table table-bordered table-striped table-condensed mb-none" >
        <thead>
        <tr class="active">
            <th style="width: 5%;font-size: 13px;">MACHINE</th>
            <th style="width: 40%;font-size: 13px;">TOTAL ($)</th>
        </tr>
        </thead>
        <tbody>
          @foreach($res['details'] as $detail)
            <tr>
              <td width="50%" style="text-align: center;">{{$detail->name_machine}}</td>
              <td  style="text-align: right;padding-right: 50px">{{$detail->utility_s4f}}</td>
            </tr>
          @endforeach
        </tbody>
        </table>
    </div>

    <div class="table-responsive col-md-12" style="padding: 10px;font-size: 14px; margin-left: -20px">
        <table class="table table-bordered table-striped table-condensed mb-none" >
        
        <div style="background-color: black;width:700px"></div>
        <tbody>
          <tr>
            <td width="90%" class="bold">TOTAL SYSTEM:</td>
            <td>${{$res['invoice']['total_system']}}</td>
          </tr>
          <tr>
            <td width="90%" class="bold">PERCENTAGE DISCOUNT:</td>
            <td >{{$res['invoice']['discount']}}%</td>
          </tr>
          <tr>
            <td width="90%" class="bold">TOTAL:</td>
            <td >${{$res['invoice']['total_discount']}}</td>
          </tr>
        </tbody>
        </table>
    </div>

  <footer style="margin-left:-50px;width:800px">
      <h2 style="color: #c3a37c;margin-top: 5px">SLOTS<span style="color: #FFF">4</span>FUN</h2>
      <h4 style="font-weight: normal;margin-top: -15px">T&nbsp;&nbsp;&nbsp;&nbsp;E&nbsp;&nbsp;&nbsp;&nbsp;X&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;S</h4>
  </footer>
  
  <!-- Scripts -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
