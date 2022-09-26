<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" lang="{{ str_replace('_', '-', app()->getLocale()) }}" itemscope itemtype="http://schema.org/WebPage">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!-- color en moviles -->
	<meta name="theme-color" content="#93C144">
	<meta name="apple-mobile-web-app-status-bar-style" content="#93C144">
	<meta name="msaplication-navbutton-color" content="#93C144">

	<?php
		$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$partial_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
	?>

	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Slots4funtx</title>

	<!-- Apple icons -->
	<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/favicon/apple-icon-57x57.png') }}" />
	<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/favicon/apple-icon-60x60.png') }}" />
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/favicon/apple-icon-72x72.png') }}" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/favicon/apple-icon-76x76.png') }}" />
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/favicon/apple-icon-114x114.png') }}" />
	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/favicon/apple-icon-120x120.png') }}" />
	<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/favicon/apple-icon-144x144.png') }}" />
	<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/favicon/apple-icon-152x152.png') }}" />
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/favicon/apple-icon-180x180.png') }}" />
	<!-- favicon -->
	<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/favicon/android-icon-192x192.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon/favicon-16x16.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/favicon/favicon-96x96.png') }}">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="{{ asset('/favicon/ms-icon-144x144.png') }}">
	<meta name="theme-color" content="#ffffff">

	<!-- Fontfaces CSS-->
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/fontawesome5.3.1.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/ionicfonts.css') }} ">
	<!-- Bootstrap CSS-->
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/bootstrap4.1.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/flaticon.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/animate.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/bootstrap-select.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/datepicker.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/toastr.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/cropper.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/datatables.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/datepicker.css') }}"/>
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/tagsinput.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/animsition.min.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/bootstrap-progressbar.min.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/animate.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/hamburgers.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/slick.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/select2.min.css') }} ">
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/perfect-scrollbar.css') }} ">

	<!-- theme -->
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/theme.css') }} ">
	<!-- styles -->
	<link rel="stylesheet" type="text/css" href="{{ asset('admincss/adminstyles.css') }} ">

	<style media="screen">
	a.disabled {
	  pointer-events: none;
	  cursor: default;
	}

		.hidden {
		  display: none;
		}

		.table th, .table td {
			padding: 5px;
		}
		.table td a{
			padding: 0;
		}
		.table-bordered {
		  border: 1px solid #ddd !important;
		}

		input{
			text-transform: uppercase;
		}

		input, textarea, select, .form-control, .form-control:focus, .bootstrap-select > .dropdown-toggle, .bootstrap-select .dropdown-menu {
		  background-color : #efefef;
		}

		select{
			background: #eee;
		}

		.card{
			background: #dedede;
		}

		th{
			background: #bbb;
		}

		td{
			background: #ddd;
		}

		.table-bordered th, .table-bordered td {
	    border: 1px solid #666;
		}

		.menu-sidebar__content, .header-desktop, .menu-sidebar .logo {
			background: #dfdfdf;
		}

		#sub-header ul li:hover,
		body.home li.home,
		body.contact li.contact { background-color: #eee;}

		#sub-header ul li:hover a,
		body.home li.home a,
		body.contact li.contact a { color: #fff; }

		.navbar-sidebar .navbar__list li.active > a {
			color: green;
		}
		.navbar-sidebar .navbar__list li a:hover {
			color: green;
		}



	</style>
<script src="{{ asset('adminjs/jquery3.3.1.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{ asset('adminjs/bootstrap4.1.js') }}"></script>
<script src="{{ asset('adminjs/bootstrap-select.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('adminjs/tablesorter.js') }}"></script>
<script type="text/javascript">
     $(function(){
        $("#table").tablesorter();
    });
</script>
<script src="https://unpkg.com/qrious@4.0.2/dist/qrious.js"></script>
</head>
<?php
$menus = DB::select('select m.*,l.key_value,l.value from menu_roles m, lookups l where m.lkp_role_id='.Auth::user()->role->id.' and m.lkp_menu_id = l.id;');
?>
<body class="animsition home">
	<input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
    <div class="page-wrapper">

        <!-- HEADER MOBILE-->
		    <header class="header-mobile d-block d-lg-none">
		        <div class="header-mobile__bar">
		            <div class="container-fluid">
		                <div class="header-mobile-inner">
		                    <a class="logo-a" href="{{action('MainController@index')}}">
		                        <img src="{{asset('/images/logo-black.png')}}" alt="CoolAdmin" class="logo-img" />
		                    </a>
		                    <button class="hamburger hamburger--slider" type="button">
		                        <span class="hamburger-box">
		                            <span class="hamburger-inner"></span>
		                        </span>
		                    </button>

												<div class="header-button">
													<div class="account-wrap">
														<div class="account-item clearfix js-item-menu">
																<div class="image">
																	@if( Auth::user()->name_image == null)
																		<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
																	@else
																		<img src="{{asset('/images/users')}}/{{Auth::user()->name_image}}" alt="" />
																	@endif
																</div>
																<div class="content">
																		<a class="js-acc-btn" href="#"></a>
																</div>
																<div class="account-dropdown js-dropdown">
																		<div class="info clearfix">
																				<div class="image">
																						<a href="#">
																							@if( Auth::user()->name_image == null)
																								<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
																							@else
																								<img src="{{asset('/images/users')}}/{{Auth::user()->name_image}}" alt="" />
																							@endif
																						</a>
																				</div>
																				<div class="content">
																					<h5 class="name">
																							<a href="#">{{ Auth::user()->name }}</a>
																					</h5>
																					<h5 class="name">
																							<a href="#">{{ Auth::user()->phone }}</a>
																					</h5>
																						<span class="email">{{ Auth::user()->email }}</span>
																				</div>
																		</div>
																		<div class="account-dropdown__footer">
							                        <a class="dropdown-item" href="{{action('MainController@logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="zmdi zmdi-power"></i>Salir</a>
							                        <form id="logout-form" action="{{action('MainController@logout')}}" method="POST" style="display: none;">
							                          @csrf
							                        </form>
							                      </div>
																</div>
														</div>
													</div>
												</div>
		                </div>
		            </div>
		        </div>
		        <nav class="navbar-mobile">
		            <div class="container-fluid" id="sub-header">
		                <ul class="navbar-mobile__list list-unstyled">


											@foreach($menus as $menu)
											<li >
												<?php $action = $menu->key_value.'Controller@index' ?>
													<a href="{{action($action)}}" id='1' >{{$menu->value}}</a>
											</li>
											@endforeach


		                </ul>
		            </div>
		        </nav>
		    </header>
	    <!-- END HEADER MOBILE-->

	    <!-- MENU SIDEBAR-->
			<aside class="menu-sidebar d-none d-lg-block">
			    <div class="logo">
			        <a class="logo-a" href="{{action('MainController@index')}}">
			            <img src="{{asset('/images/logo-black.png')}}" alt="Cool Admin" class="logo-img" />
			        </a>
			    </div>
			    <div class="menu-sidebar__content js-scrollbar1">
			        <nav class="navbar-sidebar" id="sub-header2">
			            <ul class="list-unstyled navbar__list">

										@foreach($menus as $menu)
										<li >
											<?php $action = $menu->key_value.'Controller@index' ?>
												<a href="{{action($action)}}" id='1' >{{$menu->value}}</a>
										</li>
										@endforeach




			            </ul>
			        </nav>
			    </div>
			</aside>
	    <!-- END MENU SIDEBAR-->

	    <!-- PAGE CONTAINER-->
		    <div class="page-container">
			    <!-- HEADER DESKTOP-->

				    <header class="header-desktop d-none d-lg-block">
				        <div class="section__content section__content--p30">
				            <div class="container-fluid">
				                <div class="header-wrap">

													<div class="" style="position: absolute; right: 130px; width: 40px; top: 0px;">
														@if (config('locale.status') && count(config('locale.languages')) > 1)
															<div class="top-right links">
																@foreach (array_keys(config('locale.languages')) as $lang)
																	@if ($lang != App::getLocale())
																		<a href="{!! route('lang.swap', $lang) !!}">
																			{!! $lang !!} <small>{!! $lang !!}</small>
																		</a>
																	@endif
																@endforeach
															</div>
														@endif
													</div>

				                    <div class="header-button">
				                        <div class="account-wrap">
				                            <div class="account-item clearfix js-item-menu">



				                                <div class="image">
																					@if( Auth::user()->name_image == null)
																						<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
																					@else
																						<img src="{{asset('/images/users')}}/{{Auth::user()->name_image}}" alt="" />
																					@endif
				                                </div>
				                                <!--div class="content">
				                                    <a class="js-acc-btn" href="#"></a>
				                                </div-->
				                                <div class="account-dropdown js-dropdown">
				                                    <div class="info clearfix">
				                                        <div class="image">
				                                            <a href="#">
																											@if( Auth::user()->name_image == null)
																												<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
																											@else
																												<img src="{{asset('/images/users')}}/{{Auth::user()->name_image}}" alt="" />
																											@endif
				                                            </a>
				                                        </div>
				                                        <div class="content">
				                                            <h5 class="name">
				                                                <a href="#">{{ Auth::user()->name }}</a>
				                                            </h5>
				                                            <h5 class="name">
				                                                <a href="#">{{ Auth::user()->phone }}</a>
				                                            </h5>
				                                            <span class="email">{{ Auth::user()->email }}</span>
				                                        </div>
				                                    </div>
				                                    <!--div class="account-dropdown__body">
				                                        <div class="account-dropdown__item">
				                                            <a href="#">
				                                                <i class="zmdi zmdi-account"></i>Mi cuenta</a>
				                                        </div>
				                                    </div-->
																						<div class="account-dropdown__footer">
											                        <a class="dropdown-item" href="{{action('MainController@logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="zmdi zmdi-power"></i>Salir</a>
											                        <form id="logout-form" action="{{action('MainController@logout')}}" method="POST" style="display: none;">
											                          @csrf
											                        </form>
											                      </div>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </div>
				    </header>

				<!-- HEADER DESKTOP-->

		        <!-- MAIN CONTENT-->
		            @yield('content')
		        <!-- END MAIN CONTENT-->
	        </div>
        <!-- END PAGE CONTAINER-->




	<!-- Jquery JS -->

	<!-- Bootstrap JS -->
	<script src="{{ asset('adminjs/toastr.js') }}"></script>
	<script src="{{ asset('adminjs/cropper.js') }}"></script>
	<script src="{{ asset('adminjs/datatables.js') }}"></script>
	<script src="{{ asset('adminjs/datepicker.js') }}"></script>
	<script src="{{ asset('adminjs/tagsinput.js') }}"></script>
	<script src="{{ asset('adminjs/animsition.min.js') }}"></script>
	<script src="{{ asset('adminjs/bootstrap-progressbar.min.js') }}"></script>
	<script src="{{ asset('adminjs/wow.min.js') }}"></script>
	<script src="{{ asset('adminjs/slick.js') }}"></script>
	<script src="{{ asset('adminjs/select2.min.js') }}"></script>
	<script src="{{ asset('adminjs/jquery.waypoints.min.js') }}"></script>
	<script src="{{ asset('adminjs/jquery.counterup.min.js') }}"></script>
	<script src="{{ asset('adminjs/circle-progress.js') }}"></script>
	<script src="{{ asset('adminjs/perfect-scrollbar.js') }}"></script>
	<script src="{{ asset('adminjs/charts.min.js') }}"></script>

	<!-- CKEditor -->
	<script src="{{ asset('js/sweetalert.js') }}"></script>
	<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
	<!-- main -->
	<script src="{{ asset('adminjs/main.js') }}"></script>
	<!-- Sweet Alert -->
	<!-- javscripts -->
	<script src="{{ asset('adminjs/adminscripts.js') }}"></script>

	<script >
		$("[data-toggle=tooltip]").tooltip();
	</script>

	<script>
	$(function(){
		// this will get the full URL at the address bar
		var url = window.location.href;

		// passes on every "a" tag
		$("#sub-header a").each(function() {
						// checks if its the same on the address bar
						//console.log(this.href);
						//console.log(url);
				if(url == (this.href)) {
						$(this).closest("li").addClass("active");
				}
		});
	});

	$(function(){
		// this will get the full URL at the address bar
		var url = window.location.href;

		// passes on every "a" tag
		$("#sub-header2 a").each(function() {
						// checks if its the same on the address bar
				//console.log(this.href);
				if(url == (this.href)) {
						$(this).closest("li").addClass("active");
				}
		});
	});
	</script>

	<script >
		@if(Session::has('message'))
			var type = "{{ Session::get('alert-type', 'info') }}";
			switch(type){
				case 'info':
					toastr.info("{{ Session::get('message') }}","{{ Session::get('title') }}");
				break;
				case 'warning':
					toastr.warning("{{ Session::get('message') }}","{{ Session::get('title') }}");
				break;

				case 'success':
					toastr.success("{{ Session::get('message') }}","{{ Session::get('title') }}");
				break;

				case 'error':
					toastr.error("{{ Session::get('message') }}","{{ Session::get('title') }}");
				break;
			}
		@endif
	</script>

	<script>
		if ( $('[type="date"]').prop('type') != 'date' ) {
			$('[type="date"]').datepicker({
				format: 'yyyy-mm-dd',
			});
		}
	</script>

	<script >
		$(document).ready(function() {
			$('#DT').DataTable({
                "pageLength": 100,
				"language": {
					"lengthMenu": "Mostrar _MENU_ registros por pagina",
					"zeroRecords": "No se encontraron resultados en su busqueda",
					"searchPlaceholder": "Buscar registros",
					"info": "Mostrando registros de _START_ al _END_ de un total de  _TOTAL_ registros",
					"infoEmpty": "No existen registros",
					"infoFiltered": "(filtrado de un total de _MAX_ registros)",
					"search": "Buscar:",
					"paginate": {
						"first":"Primero",
						"last":"Último",
						"next":"Siguiente",
						"previous":"Anterior"
					},
				}
			});
		});
	</script>

	<script>

		function checkclic()
		{
			if (document.getElementById('check-active').checked){
				$(document.getElementById('check-input')).attr('value',0);
		  } else {
				$(document.getElementById('check-input')).attr('value',1);
		  }
		}

		function check(){
			check = $(document.getElementById('check-input')).attr('value');
			if(check){
				if(check == 0){
					$(document.getElementById('check-active')).attr('checked','');
				}

				if (document.getElementById('check-active').checked)
			  {
					arr = document.getElementsByClassName('active');
					if(arr.length > 0){
						for (var i = 0; i < arr.length; i++) {
							arr[i].setAttribute('hidden','');
						}
					}
					arr = document.getElementsByClassName('inactive');
					if(arr.length > 0){
						for (var i = 0; i < arr.length; i++) {
							arr[i].removeAttribute('hidden');
						}
					}
			  } else {
					arr = document.getElementsByClassName('inactive');
					if(arr.length > 0){
						for (var i = 0; i < arr.length; i++) {
							arr[i].setAttribute('hidden','');
						}
					}

					arr = document.getElementsByClassName('active');
					if(arr.length > 0){
						for (var i = 0; i < arr.length; i++) {
							arr[i].removeAttribute('hidden');
						}
					}
				}
		  }
		}

		$(document).ready(function() {
			check();
		});

		$("body").on("click",".lookup_edit",function(){
			var url = $(this).attr("data-href");
			var value = $(this).attr("data-value");
			var type = $(this).attr("data-type");
			var city = $(this).attr("data-city");
			console.log(city);

			$(document.getElementById("update-lookup")).attr("action",url);
			$(document.getElementById("lookup-value")).attr("value",value);
			$(document.getElementById("p_key_value")).attr("value",type);

			$(document.getElementById("city-div")).attr("hidden",'');
			document.getElementById("city-select").removeAttribute("name");
			document.getElementById("city-select").removeAttribute("required");





			if(city){
				document.getElementById("city-div").removeAttribute("hidden");
				$(document.getElementById(city)).attr("selected",'');
				$(document.getElementById("city-select")).attr("name",'lkp_city_id');
				$(document.getElementById("city-select")).attr("required",'');


			}

		});

		$("body").on("click",".input_img",function(){
			var inp = $(this).attr("data-id");
			var img = $(this).attr("data-id2");
			var i2 = $(document.getElementById(inp)).attr("data-id2");
			document.getElementById(inp).click();
			var defimg = $(document.getElementById(img)).attr("src");
			document.getElementById(inp).onchange = function (evt) {
				var tgt = evt.target || window.event.srcElement,
					files = tgt.files;
				// FileReader support
				if (FileReader && files && files.length) {
					var fr = new FileReader();
					fr.onload = function () {
						document.getElementById(img).src = fr.result;
						ResizeImage(inp,i2);
					}
					fr.readAsDataURL(files[0]);
				}
				else {
					$(document.getElementById(img)).attr('src','https://app.ecovit.com.mx/images/interface.png');
				}
			}
		});

		function ResizeImage(inp, i2) {
			var filesToUploads = document.getElementById(inp).files;
			var file = filesToUploads[0];
			if (file) {
				var reader = new FileReader();
				// Set the image once loaded into file reader
				reader.onload = function(e) {
					var img = document.createElement("img");
					img.src = e.target.result;
					var canvas = document.createElement("canvas");
					var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0);
					var MAX_WIDTH = 1000;
					var MAX_HEIGHT = 1000;
					var width = img.width;
					var height = img.height;
					if (width > height) {
						if (width > MAX_WIDTH) {
							height *= MAX_WIDTH / width;
							width = MAX_WIDTH;
						}
					} else {
						if (height > MAX_HEIGHT) {
							width *= MAX_HEIGHT / height;
							height = MAX_HEIGHT;
						}
					}
					canvas.width = width;
					canvas.height = height;
					var ctx = canvas.getContext("2d");
					ctx.drawImage(img, 0, 0, width, height);
					dataurl = canvas.toDataURL(file.type);
					dataurlJPG = canvas.toDataURL('image/jpeg');
					var resizedImage = dataURLToBlob(dataurlJPG);
					$(document.getElementById(i2)).val(dataurlJPG);
				}
				reader.readAsDataURL(file);
			}
		}

		var dataURLToBlob = function(dataURL) {
			var BASE64_MARKER = ';base64,';
			if (dataURL.indexOf(BASE64_MARKER) == -1) {
				var parts = dataURL.split(',');
				var contentType = parts[0].split(':')[1];
				var raw = parts[1];
				return new Blob([raw], {type: contentType});
			}
			var parts = dataURL.split(BASE64_MARKER);
			var contentType = parts[0].split(':')[1];
			var raw = window.atob(parts[1]);
			var rawLength = raw.length;
			var uInt8Array = new Uint8Array(rawLength);
			for (var i = 0; i < rawLength; ++i) {
				uInt8Array[i] = raw.charCodeAt(i);
			}
			return new Blob([uInt8Array], {type: contentType});
		}
	</script>

	<script>
		$('body').on('click','.editAddress',function(event){
			document.getElementById('updateAddress').reset();

			var action = $(this).attr('data-action');
			var name_address = $(this).attr('data-name_address');
			var business_name = $(this).attr('data-business_name');
			var city = $(this).attr('data-city');
			var county = $(this).attr('data-country');
			$(document.getElementById('name_address')).attr('value',name_address);
			$(document.getElementById('business_name')).attr('value',business_name);

			//var mySelect = document.getElementById("client-city2").selectedValue = city;
			if(city)
				$('#client-city2 option[value='+city+']').prop('selected', true);
			if(county)
				$('#client-county2 option[value='+county+']').prop('selected', true);

			$(document.getElementById('updateAddress')).attr('action',action);
		});
	</script>

	<script>


	//////////////////////

	function selectPaymentType(value){
		if(value == '68'){
			document.getElementById('content_percentage_payday').removeAttribute('hidden');
			$(document.getElementById('percentage_payday')).attr('payday',"payday");
			$(document.getElementById('percentage_payday')).attr('required',"");
		}else{
			$(document.getElementById('content_percentage_payday')).attr('hidden',"");
			document.getElementById('percentage_payday').removeAttribute('payday');
			document.getElementById('percentage_payday').removeAttribute('required');
		}
	}

/////////city
	function selectCityClient(value,div){
		console.log(value,div);
		$("#client-county-"+div+' option').prop('selected', function() {
        return this.defaultSelected;
    });

		if(value){
			document.getElementById("client-county-"+div).removeAttribute('hidden');

			county = document.getElementsByClassName('counties');
			for (var i = 0; i < county.length; i++) {
				county[i].setAttribute('hidden','');
			}

			county = document.getElementsByClassName('city-'+value);
			for (var i = 0; i < county.length; i++) {
				county[i].removeAttribute('hidden');
			}
		}
	}

	///////////////

	function selectCityLookup(value){
		$(document.getElementById('part_type_brand')).attr('hidden',"");
		$(document.getElementById('component_type')).attr('hidden',"");

		if(value == 'counties'){
			document.getElementById('city-form').removeAttribute('hidden');
			$(document.getElementById('city-select')).attr('name',"lkp_city_id");
			$(document.getElementById('city-select')).attr('required',"");
		}else{
			if(value == 'part_type')
				document.getElementById('part_type_brand').removeAttribute('hidden');
			if(value == 'details')
				document.getElementById('component_type').removeAttribute('hidden');
			$(document.getElementById('city-form')).attr('hidden',"");
			document.getElementById('city-select').removeAttribute('name');
			document.getElementById('city-select').removeAttribute('required');
		}
	}

	function percentageAmount(value){
		if(value == '44'){//rent
			document.getElementById('input').innerHTML='Amount <span style="color:red">*</span>';
		}if(value == '45'){//Percentage
			document.getElementById('input').innerHTML='Percentage <span style="color:red">*</span>';
		}if(value == ''){
			document.getElementById('input').innerHTML='Percentage/Amount <span style="color:red">*</span>';
		}
	}

	function permitSelect(value,change){//1chnage - 0no

		if(value == '41'){//state
			$(document.getElementById('long')).attr('pattern',"[0-9]{6}");
			$(document.getElementById('long')).attr('maxlength',"6");
			$(document.getElementById('long2')).attr('pattern',"[0-9]{6}");
			$(document.getElementById('long2')).attr('maxlength',"6");

			/*machines = document.getElementsByClassName('s-'+value);
			for (var i = 0; i < machines.length; i++) {
				machines[i].setAttribute('hidden','');
				machines[i].classList.add( "hidden" );
			}
			machines = document.getElementsByClassName('s-42');
			for (var i = 0; i < machines.length; i++) {
				machines[i].removeAttribute('hidden');
				machines[i].classList.remove( "hidden" );
			}*/

		}if(value == '42'){//city
			$(document.getElementById('long')).attr('pattern',"[0-9]{4}");
			$(document.getElementById('long')).attr('maxlength',"4");
			$(document.getElementById('long2')).attr('pattern',"[0-9]{4}");
			$(document.getElementById('long2')).attr('maxlength',"4");

			/*machines = document.getElementsByClassName('s-'+value);
			for (var i = 0; i < machines.length; i++) {
				machines[i].setAttribute('hidden','');
				machines[i].classList.add( "hidden" );
			}
			machines = document.getElementsByClassName('s-41');
			for (var i = 0; i < machines.length; i++) {
				machines[i].removeAttribute('hidden');
				machines[i].classList.remove( "hidden" );
			}*/

		}if(value == ''){

			$(document.getElementById('long')).attr('pattern',"[0-9]{6}");
			$(document.getElementById('long')).attr('maxlength',"6");
			$(document.getElementById('long2')).attr('pattern',"[0-9]{6}");
			$(document.getElementById('long2')).attr('maxlength',"6");

			/*machines = document.getElementsByClassName('s-42');
			for (var i = 0; i < machines.length; i++) {
				machines[i].classList.add( "hidden" );
			}

			machines = document.getElementsByClassName('s-41');
			for (var i = 0; i < machines.length; i++) {
				machines[i].classList.add( "hidden" );
			}*/

		}
	}

	function permitSelectIndex(value,change){//1chnage - 0no
	//	console.log(value);
		if(value == '41'){//state
			machines = document.getElementsByClassName('s-'+value);
			for (var i = 0; i < machines.length; i++) {
				machines[i].removeAttribute('hidden');
			}
			machines = document.getElementsByClassName('s-42');
			for (var i = 0; i < machines.length; i++) {
				machines[i].setAttribute('hidden','');
			}
		}if(value == '42'){//city
			machines = document.getElementsByClassName('s-'+value);
			for (var i = 0; i < machines.length; i++) {
				machines[i].removeAttribute('hidden');
			}
			machines = document.getElementsByClassName('s-41');
			for (var i = 0; i < machines.length; i++) {
				machines[i].setAttribute('hidden','');
			}
		}if(value == ''){
			machines = document.getElementsByClassName('s-42');
			for (var i = 0; i < machines.length; i++) {
				machines[i].removeAttribute('hidden');
			}

			machines = document.getElementsByClassName('s-41');
			for (var i = 0; i < machines.length; i++) {
				machines[i].removeAttribute('hidden');
			}
		}
	}

	$(document).ready(function() {
		if(document.getElementById('type-lookup')){
			type = document.getElementById('type-lookup').value;
			selectCityLookup(type);
		}

		if(document.getElementById('permit_type')){
			type = document.getElementById('permit_type').value;
			permitSelect(type,0);
		}

		if(document.getElementById('permit_type_index')){
			type = document.getElementById('permit_type_index').value;
			permitSelectIndex(type,0);
		}

		if(document.getElementById('percentage_type')){
			type = document.getElementById('percentage_type').value;
			percentageAmount(type);
		}

		if(document.getElementById('percentage_periodicity')){
			type = document.getElementById('percentage_periodicity').value;
			selectPaymentType(type);
		}
	});

	if(document.getElementById('percentage_type')){
		document.getElementById('percentage_type').addEventListener('change', function() {
			percentageAmount(this.value);
		});
	}

	if(document.getElementById('percentage_periodicity')){
		document.getElementById('percentage_periodicity').addEventListener('change', function() {
			selectPaymentType(this.value);
		});
	}

	if(document.getElementById('permit_type')){
		document.getElementById('permit_type').addEventListener('change', function() {
			permitSelect(this.value,1);
			/*$("#machine").val('default');
			$("#machine").selectpicker("refresh");*/
		});
	}
	if(document.getElementById('permit_type_index')){
		document.getElementById('permit_type_index').addEventListener('change', function() {
			permitSelectIndex(this.value,1);
		});
	}

	if(document.getElementById('type-lookup')){
		document.getElementById('type-lookup').addEventListener('change', function() {
			selectCityLookup(this.value);
		});
	}

	if(document.getElementById('client-city')){
		document.getElementById('client-city').addEventListener('change', function() {
			selectCityClient(this.value,1);
		});
	}

	if(document.getElementById('client-city2')){
		document.getElementById('client-city2').addEventListener('change', function() {
			selectCityClient(this.value,2);
		});
	}

	//////////////////////


	</script>

	<script>

		$('body').on("click", '.formButton', function(e) {
	    e.preventDefault();

	    //$("#wait").css("display", "block");

			var form_id = $(this).attr("data-form");
			var form = document.getElementById(form_id);
	    var form_action = $(form).attr("action");
	    var div_refresh = $(form).attr("data-refresh");

			var message1 = $(form).attr("data-message1");
			var message2 = $(form).attr("data-message2");
			var modal = $(form).attr("data-modal");
			var dataString = $('#'+form_id).serialize();
			var to = $("#token").val();



			$.ajax({
				type: "POST",
				headers:{"X-CSRF-TOKEN": to},
				data: dataString,
				url: form_action,
				cache: false,
				dataType: 'json',
				success: function(data) {
					console.log('success');
					$(div_refresh).load(" "+div_refresh);
					$(modal).modal('hide');

					form.reset();

					Swal.fire(
					 message1,
					 message2,
					 'success'
					);
				},
				error: function(jqXHR, textStatus, errorThrown){
					//$(div_refresh).load(" "+div_refresh);

					if(jqXHR.status == 422){
						message = $.parseJSON(jqXHR.responseText);
					}
					else{
						message = '{{__("Oops! there was an error, please try again later.")}}';
					}
					Swal.fire(
					 'Error!',
					 message,
					 'error'
					);
				},
			});
		});



	</script>

	<script>
		$('body').on('click','.delete-alert',function(event){
			var url = $(this).attr('data-action');
			var table = $(this).attr('data-table');
			var reload = $(this).attr('data-reload');

			var method = $(this).attr('data-method');
			var message1 = $(this).attr('data-message1');
			var message2 = $(this).attr('data-message2');
		  var message3 = $(this).attr('data-message3');
			var to = $("#token").val();

			Swal.fire({
			  title: '{{__("Are you sure?")}}',
			  text: message1,
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
				confirmButtonText: '{{__("Yes!")}}',
			  cancelButtonText: '{{__("Cancel")}}'
			}).then((result) => {
			  if (result.isConfirmed) {
					$.ajax({
						type: "POST",
						headers:{"X-CSRF-TOKEN": to},
						url: url,
						cache: false,
						dataType: 'json',
						data: {
                "_token": to,
                "_method": method
            },
						success: function(data) {
							console.log('success');
							$(table).load(" "+table);

							Swal.fire(
							 message2,
							 message3,
							 'success'
						 	);
						},
						error: function(jqXHR, textStatus, errorThrown){

							//$(table).load(" "+table);

							if(jqXHR.status == 422){
								$.parseJSON(jqXHR.responseText);
							}
							else{
								message = '{{__("Oops! there was an error, please try again later.")}}';
							}
							Swal.fire(
							 'Error!',
							 message,
							 'error'
						 	);
						},
					});
			  }
			});
		});

		$("input.find-serial").bind('keypress', function(event) {
	    var regex = new RegExp("^[a-zA-Z0-9]+$");
	    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	    if (!regex.test(key)) {
	      event.preventDefault();
	      return false;
	   }
	  });

		$(document).on('input', '.find-serial', function(){
			str = this.value;
			string = str.replace(/[^a-zA-Z0-9]/g, '');
			this.value = string;
		});


	</script>

	<script>
	$("body").on("click",".qr",function(){
			url = $(this).attr("data-action");
			document.getElementById("exampleModalCenterTitl").innerHTML = $(this).attr("data-id");

			new QRious({
				element: document.querySelector("#codigo"),
				value: url, // La URL o el texto
				size: 200,
				backgroundAlpha: 0, // 0 para fondo transparente
				foreground: "#000000", // Color del QR
				level: "Q", // Puede ser L,M,Q y H (L es el de menor nivel, H el mayor)
			});
	});

	</script>


	<script>
		function dataReset(){
			document.getElementById("formInputs").setAttribute('hidden','');
			document.getElementById("initial-form").setAttribute('hidden','');
			document.getElementById("initial-form").setAttribute('hidden','');
			document.getElementById("jackpot").setAttribute('hidden','');
			document.getElementById("formInputs").setAttribute('hidden','');
			document.getElementById("jackpotout1").removeAttribute('required');
			document.getElementById("jackpotinitial").setAttribute('hidden','');

			document.getElementById("masterin").removeAttribute('readonly');
			document.getElementById("masterout").removeAttribute('readonly');
			document.getElementById("jackpotout").removeAttribute('readonly');
			document.getElementById("masterin").setAttribute('required','');
			document.getElementById("masterout").setAttribute('required','');
			document.getElementById("jackpotout").setAttribute('required','');

			document.getElementById("jackpotout").removeAttribute('required');

			document.getElementById("initialform").reset();






		}

		function dataCharge(e) {

			//dataReset();

			document.getElementById("masterin1").value = '';
			document.getElementById("masterout1").value = '';
			document.getElementById("jackpotout1").value = '';
			document.getElementById("average").value = '';
			document.getElementById("machineid").value = '';
			document.getElementById("percentage").value = '';
			document.getElementById("name").value ='';



			if(e.options[e.selectedIndex].getAttribute("data-masterin") == ""){
				jackpotout = e.options[e.selectedIndex].getAttribute("data-jackpotout");
				if(e.options[e.selectedIndex].getAttribute("data-band") == "1"){
					document.getElementById("jackpotinitial").removeAttribute('hidden');
					document.getElementById("jpinitial").setAttribute('required','');
				}
				document.getElementById("initial-form").removeAttribute('hidden');
				document.getElementById("machineidinitial").value = e.options[e.selectedIndex].getAttribute("data-id");
			}
			else {
				document.getElementById("formInputs").removeAttribute('hidden');
				id = e.options[e.selectedIndex].getAttribute("data-id");
				masterin = e.options[e.selectedIndex].getAttribute("data-masterin");
				masterout = e.options[e.selectedIndex].getAttribute("data-masterout");
				jackpotout = e.options[e.selectedIndex].getAttribute("data-jackpotout");
				average = e.options[e.selectedIndex].getAttribute("data-average");
				percentage = e.options[e.selectedIndex].getAttribute("data-percentage");
				band = e.options[e.selectedIndex].getAttribute("data-band");

				if(average == ""){
					document.getElementById('avr').removeAttribute('hidden');
					average = "0";
				}
				else{
					document.getElementById("uc").value = average ;
					document.getElementById("us").value = average ;
					document.getElementById("us").setAttribute('max',average);
					document.getElementById('avr').setAttribute('hidden','');

				}

				document.getElementById("masterin1").value = e.options[e.selectedIndex].getAttribute("data-masterin");
				document.getElementById("masterout1").value = e.options[e.selectedIndex].getAttribute("data-masterout");
				document.getElementById("jackpotout1").value = e.options[e.selectedIndex].getAttribute("data-jackpotout");
				document.getElementById("average").value = average
				document.getElementById("machineid").value = e.options[e.selectedIndex].getAttribute("data-id");
				document.getElementById("percentage").value = e.options[e.selectedIndex].getAttribute("data-percentage");
				document.getElementById("name").value = e.options[e.selectedIndex].getAttribute("value");

				if(e.options[e.selectedIndex].getAttribute("data-band") == "1"){
					document.getElementById("jackpot").removeAttribute('hidden');
					document.getElementById("jackpotout").setAttribute('required','');
				}

			}


		}

		function dataInpu(e) {

			$('#machineselect option').prop('selected', function() {
	        return this.defaultSelected;
	    });

			dataReset();

			if(e.options[e.selectedIndex].getAttribute("value") == 'average_charge' || e.options[e.selectedIndex].getAttribute("value") ==  'normal_charge'){
				document.getElementById("type").value = e.options[e.selectedIndex].getAttribute("value");

				ch = document.getElementsByClassName('charge');
				for (var i = 0; i < ch.length; i++) {
					ch[i].removeAttribute('hidden');
				}
				ch = document.getElementsByClassName('initial');
				for (var i = 0; i < ch.length; i++) {
					ch[i].setAttribute('hidden','');
				}

			}
			else{
				document.getElementById("type2").value = e.options[e.selectedIndex].getAttribute("value");

				ch = document.getElementsByClassName('initial');
				for (var i = 0; i < ch.length; i++) {
					ch[i].removeAttribute('hidden');
				}

				ch = document.getElementsByClassName('charge');
				for (var i = 0; i < ch.length; i++) {
					ch[i].setAttribute('hidden','');
				}

			}
			if(e.options[e.selectedIndex].getAttribute("value") == 'average_charge'){
				document.getElementById("masterin").setAttribute('readonly','');
				document.getElementById("masterout").setAttribute('readonly','');
				document.getElementById("jackpotout").setAttribute('readonly','');

				document.getElementById('avr').setAttribute('hidden','');

				document.getElementById("masterin").removeAttribute('required');
				document.getElementById("masterout").removeAttribute('required');
				document.getElementById("jackpotout").removeAttribute('required');
			}

			document.getElementById("machineselect").removeAttribute('hidden');
		}

		function calculate(){
			if(document.getElementById('masterin').value != '' && document.getElementById('masterin1').value != "" && document.getElementById('masterout').value != "" && document.getElementById('masterout1').value != "")
			{

				document.getElementById("masterin").setAttribute('min',document.getElementById('masterin1').value);
				document.getElementById("masterout").setAttribute('min',document.getElementById('masterout1').value);



				i = document.getElementById('masterin').value - document.getElementById('masterin1').value;
				o = document.getElementById('masterout').value - document.getElementById('masterout1').value;
				p = document.getElementById('percentage').value;
				t = (i-o)*p/100;

				document.getElementById("uc").value = t ;
				document.getElementById("us").value = t ;

				document.getElementById("us").setAttribute('max',t);
			}
		}


	</script>




</body>
</html>
