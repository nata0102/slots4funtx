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
		.table th, .table td {
			padding: 5px;
		}
		.table td a{
			padding: 0;
		}
		.table-bordered {
		  border: 1px solid #ddd !important;
		}
	</style>

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
																		<a class="js-acc-btn" href="#"></a>
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
																						<span class="email">{{ Auth::user()->email }}</span>
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
				                    <a href="{{ action('MainController@index') }}">Dashboard</a>
				                </li>
												<li>
														<a href="{{action('MachineController@index')}}">Machines</a>
												</li>
												<li>
														<a href="{{action('PartController@index')}}">Parts</a>
												</li>
												<li>
														<a href="{{action('MachineBrandController@index')}}">Machine Brands</a>
												</li>
												<li>
												<a href="{{action('PermissionController@index')}}">	Permissions</a>
												</li>
												<li>
												<a href="{{action('PercentagePriceController@index')}}">	Percentage/Amount</a>
												</li>
												<li>
													<a href="{{action('LookupController@index')}}">Configuration</a>
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
													<a href="{{action('MachineController@index')}}">Machines</a>
											</li>
											<li>
													<a href="{{action('PartController@index')}}">Parts</a>
											</li>
											<li>
													<a href="{{action('MachineBrandController@index')}}">Machine Brands</a>
											</li>
											<li>
												<a href="{{action('PermissionController@index')}}">Permissions</a>
											</li>
											<li>
												<a href="{{action('PercentagePriceController@index')}}">	Percentage/Amount</a>
												</li>
											<li>
												<a href="{{action('LookupController@index')}}">Configuration</a>
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
				                                            <span class="email">{{ Auth::user()->email }}</span>
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

			$(document.getElementById("update-lookup")).attr("action",url);
			$(document.getElementById("lookup-value")).attr("value",value);
			$(document.getElementById("p_key_value")).attr("value",type);
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
			  title: 'Are you sure?',
			  text: message1,
			  icon: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes!'
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
							console.log('error');
							$(table).load(" "+table);

							if(jqXHR.status == 422){
								message = jqXHR.responseText;
							}
							else{
								message = 'Oops! there was an error, please try again later.';
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


	</script>




</body>
</html>
