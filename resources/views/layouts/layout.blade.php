<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#" lang="es" itemscope itemtype="http://schema.org/WebPage">
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
	<link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/images/favicon/apple-icon-57x57.png') }}" />
	<link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/images/favicon/apple-icon-60x60.png') }}" />
	<link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/images/favicon/apple-icon-72x72.png') }}" />
	<link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/images/favicon/apple-icon-76x76.png') }}" />
	<link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/images/favicon/apple-icon-114x114.png') }}" />
	<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/images/favicon/apple-icon-120x120.png') }}" />
	<link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/images/favicon/apple-icon-144x144.png') }}" />
	<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/images/favicon/apple-icon-152x152.png') }}" />
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/images/favicon/apple-icon-180x180.png') }}" />
	<!-- favicon -->
	<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('/images/favicon/android-icon-192x192.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/images/favicon/favicon-16x16.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/images/favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/images/favicon/favicon-96x96.png') }}">
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

</head>
<body class="animsition">
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
																		<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
																</div>
																<div class="content">
																		<a class="js-acc-btn" href="#">Nombre</a>
																</div>
																<div class="account-dropdown js-dropdown">
																		<div class="info clearfix">
																				<div class="image">
																						<a href="#">
																								<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
																						</a>
																				</div>
																				<div class="content">
																						<h5 class="name">
																								<a href="#"></a>
																						</h5>
																						<span class="email"></span>
																				</div>
																		</div>
																		<div class="account-dropdown__body">
																				<div class="account-dropdown__item">
																						<a href="#">
																								<i class="zmdi zmdi-account"></i>Mi cuenta</a>
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
		            <div class="container-fluid">
		                <ul class="navbar-mobile__list list-unstyled">
		                    <li>
													<li>
					                    <a href="{{ action('MainController@index') }}">Dashboard</a>
					                </li>
													<li>
															<a href="{{action('MachineController@index')}}">Maquinas</a>
													</li>
													<li>
															<a href="{{action('PartController@index')}}">Piezas</a>
													</li>
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
			        <nav class="navbar-sidebar">
			            <ul class="list-unstyled navbar__list">
			                <li>
			                    <a href="{{ action('MainController@index') }}">Dashboard</a>
			                </li>
											<li>
													<a href="{{action('MachineController@index')}}">Maquinas</a>
											</li>
											<li>
													<a href="{{action('PartController@index')}}">Piezas</a>
											</li>

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
				                    <div class="header-button">
				                        <div class="account-wrap">
				                            <div class="account-item clearfix js-item-menu">
				                                <div class="image">
				                                  <img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
				                                </div>
				                                <div class="content">
				                                    <a class="js-acc-btn" href="#">NOMBRE</a>
				                                </div>
				                                <div class="account-dropdown js-dropdown">
				                                    <div class="info clearfix">
				                                        <div class="image">
				                                            <a href="#">
																											<img src="{{asset('/images/profiles/empty.jpg')}}" alt="" />
				                                            </a>
				                                        </div>
				                                        <div class="content">
				                                            <h5 class="name">
				                                                <a href="#">NOMBRE</a>
				                                            </h5>
				                                            <span class="email">EMAIL</span>
				                                        </div>
				                                    </div>
				                                    <div class="account-dropdown__body">
				                                        <div class="account-dropdown__item">
				                                            <a href="#">
				                                                <i class="zmdi zmdi-account"></i>Mi cuenta</a>
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
				    </header>

				<!-- HEADER DESKTOP-->

		        <!-- MAIN CONTENT-->
		            @yield('content')
		        <!-- END MAIN CONTENT-->
	        </div>
        <!-- END PAGE CONTAINER-->




	<!-- Jquery JS -->
	<script src="{{ asset('adminjs/jquery3.3.1.js') }}"></script>
	<!-- Bootstrap JS -->
	<script src="{{ asset('adminjs/popper.js') }}"></script>
	<script src="{{ asset('adminjs/bootstrap4.1.js') }}"></script>
	<script src="{{ asset('adminjs/bootstrap-select.min.js') }}"></script>
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
	<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
	<!-- main -->
	<script src="{{ asset('adminjs/main.js') }}"></script>
	<!-- javscripts -->
	<script src="{{ asset('adminjs/adminscripts.js') }}"></script>


	<script >
		$("[data-toggle=tooltip]").tooltip();
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
                "pageLength": 200,

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
		si = document.getElementById('description-ckeditor');
		if(si){
			CKEDITOR.config.height = 200;
			CKEDITOR.config.width = 'auto';
			CKEDITOR.replace('description-ckeditor');
		}
	</script>

	<script type="text/javascript">

		$("#etiqueta").tagsinput()
	</script>

	<script type="text/javascript">
		$(".infoUsuario").click(function(){
			var name = $(this).attr("data-name");
			var email = $(this).attr("data-email");
			var telephone = $(this).attr("data-telephone");
			var pet = $(this).attr("data-pet");


			$("#nombre").html(name);
			$("#email").html(email);
			$("#telefono").html(telephone);
			$("#mascota").html(pet);
		});
	</script>

	<script type="text/javascript">
		$(".reporte").click(function(){
			var places = $(this).attr("data-places");
			var notes = $(this).attr("data-notes");

			$("#lugar").html(places);
			$("#descripcion").html(notes);
		});
	</script>

	<script type="text/javascript">
			var input = document.getElementById('autocomplete');
		  //var autocomplete = new google.maps.places.Autocomplete(input);
			var autocomplete = new google.maps.places.Autocomplete(input);
			 google.maps.event.addListener(autocomplete, 'place_changed', function(){
					var place = autocomplete.getPlace();
					document.getElementById('latitud').value = place.geometry.location.lat();
					document.getElementById('longitud').value = place.geometry.location.lng();
			 });

	</script>



</body>
</html>
