@extends('Layouts.App')
@section('content')
<style>
	.listAddress{
		background-color: #fff;
		border: 1px solid #ccc;
		width: 100%;
	}
	.list{
		cursor: pointer;
		border-bottom: 1px solid #ccc;
	}
</style>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="card  card-dark mt-4">
				<div class="card-header">
					<h3 class="card-title">Sélectionnez le service et l'entreprise</h3>
				</div>
				<div class="card-body">
					<form id="form-services" class="form-services" method="POST" action="{{ route('store.invoices') }}" enctype="multipart/form-data">
						@if ($errors->any())
    					<div class="alert alert-danger">
        				<ul>
            			@foreach ($errors->all() as $error)
                		<li>{{ $error }}</li>
            			@endforeach
        				</ul>
    					</div>
						@endif
						<section id="firstStep">
							@include('Components/First')
						</section>
						<section id="secondStep">

							@csrf
							<div class="row">
								<div class="col-md-12 mb-4">
									<div id="map"></div>
								</div>
								<div class="col-md-6 mb-4">
									<div class="form-group">
										<label for="enterprise_own">Sélectionnez l'entreprise</label>
										<select class="custom-select form-control-border" id="enterprise_own" name="enterprise_own">
											<option value="1" selected>1620 rang Saint Eduard, St-Liboire QC J0H 1R0</option>
											<option value="2">1200 Rue Daniel - Johnson O, Saint-Hyacinthe, QC J2S 7K7</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Situation actuelle</label>
										<div class="input-group date" id="dateTime">
											<input type="text" id="searchAddress" name='searchAddress' class="form-control">
										</div>
										<div class="listAddress p-4"></div>
									</div>
								</div>
								<div class="w-100"></div>
								<div align="right" class="col-md-3 ml-auto mt-3">
									<div class="btn-group">
										<button type="button" class="btn btn-block btn-outline-primary btn-flat m-0" onclick="SecondPrev()">Revenir</button>
										<button type="button" class="btn btn-block btn-outline-success btn-flat m-0" onclick="SecondNext()">Suivant</button>
									</div>
								</div>
							</div>
						</section>
						<section id="thirdStep">
							@include('Components/Third')
						</section>
						<section id="fourStep">
							<div class="row">
								<div class="col-md-12 ml-auto mt-3">
									<ul class="annulle goa">
										<li><strong><i class=" mb-3 fas fa-map-marker-alt mr-2"></i> Base :</strong> <span id="base_end"></span></li>
										<li><strong><i class=" mb-3 fas fa-map-marker-alt mr-2"></i> Destination :</strong> <span id="destination_end"></span></li>
										<li><strong><i class=" mb-3 fas fa-wrench mr-2"></i> Service :</strong> <span id="service_end"></span></li>
										<li><strong><i class=" mb-3 fas fa-user mr-2"></i> Nom :</strong> <span id="name_end"></span></li>
										<li><strong><i class=" mb-3 fas fa-phone mr-2"></i> Téléphone :</strong> <span id="phone_end"></span></li>
										<li><strong><i class=" mb-3 fas fa-envelope mr-2"></i> Courriel électronique :</strong> <span id="mail_end"></span></li>
									</ul>
									<ul class="show_enterprise">
										<li><strong><i class=" mb-3 fas fa-wrench mr-2"></i> Enterprise :</strong> <span id="enterprise_data"></span></li>
									</ul>
								</div>
								<div class="col-md-4">
									<img class="mt-4 img-fluid" id="imagenPrevisualizacion">
								</div>
								<div class="w-100"></div>
								<div align="right" class="col-md-3 ml-auto mt-3">
									<div class="btn-group w-100">
										<button type="button" class="btn btn-block btn-outline-primary btn-flat m-0" onclick="FourPrev()">Revenir</button>
										<button type="submit" class="btn btn-block btn-outline-success btn-flat m-0">Créer facture</button>
									</div>
								</div>
								<div align="center" class="col-12 mt-4">
									<p>
										<strong>Remarque:</strong> Les données doivent être complètes pour que le formulaire fonctionne
									</p>
								</div>
							</div>
						</section>
						<input type="hidden" id="lat_input" name="lat">
						<input type="hidden" id="lng_input" name="lng">
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>


	/*********************** GOOGLE MAPS **********************************************************/
function getGeocodeData(address) { 
	address = encodeURIComponent(address);  
	googleMapUrl = "https://maps.googleapis.com/maps/api/geocode/json?address="+ address +"&key=AIzaSyDsK-qVLYQGl_YSAHFQAmwxCqpCt-C0CZc";   
	$.ajax({
		type : "GET",
		url : googleMapUrl,
		success : function(geocodeResponseData){
			if(geocodeResponseData.results.length > 0){
				$('.listAddress').show();
				geocodeResponseData.results.forEach( function(valor, indice, array) {
					$('.listAddress').append('<div class="list py-1" onclick="geometry('+valor.geometry.location.lat +','+valor.geometry.location.lng +','+"'"+valor.formatted_address+"'"+')">'+valor.formatted_address+'</div>')
				});
			}else{
				$('.listAddress').hide();
			}
		}
	});
}

      // Initialize and add the map
      function initMap() {

        // The location of Uluru
        const uluru = { lat: 45.6561685248608, lng: -72.75923067667834 };
        const marker1 = { lat: 45.6561685248608, lng: -72.75923067667834 }; 
        const marker2 = { lat: 45.64103019874336, lng: -72.96843393267208 };
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
        	zoom: 8,
        	center: uluru,
        });

        const marker_1 = new google.maps.Marker({
        	position: marker1,
        	map: map,
        });

        const marker_2 = new google.maps.Marker({
        	position: marker2,
        	map: map,
        });

        marker_1.addListener("click", () => {
        	$('select[name=enterprise_own]').val(1);
        	map.setZoom(13);
        	map.setCenter(marker_1.getPosition());
        });

        marker_2.addListener("click", () => {
        	$('select[name=enterprise_own]').val(2);
        	map.setZoom(13);
        	map.setCenter(marker_2.getPosition());
        });


        $("select[name=enterprise_own]").change(function(){
        	if($('select[name=enterprise_own]').val() == 1){
        		map.setZoom(13);
        		map.setCenter(marker_1.getPosition());
        	}else{
        		map.setZoom(13);
        		map.setCenter(marker_2.getPosition());
        	}

        });

        /*GEO*/
        $( "#searchAddress" ).keyup(function() {
        	var searchAddress = document.getElementById("searchAddress");
        	$geocodeData = getGeocodeData(searchAddress.value); 
        	if($geocodeData) {         
        		$latitude = $geocodeData[0];
        		$longitude = $geocodeData[1];
        		$address = $geocodeData[2];
        	}
        });
      };

/******************************* END GOOGLE MAPS ***************************************/
      $('#secondStep').hide();
      $('#thirdStep').hide();
      $('#fourStep').hide();
      $('.listAddress').hide();

      /**/

      function OneNext() {
      	if($('input.cancelled').prop('checked') || $('input.goa').prop('checked')){
      		$('#firstStep').hide();
      		$('#fourStep').show();
	      	coupler();
      	}else{
      		$('#firstStep').hide();
      		$('#secondStep').show();
      	}
      }
      function SecondNext() {
      	$('#secondStep').hide();
      	$('#thirdStep').show();
      }
      function SecondPrev() {
      	$('#firstStep').show();
      	$('#secondStep').hide();
      }
      function ThirdPrev() {
      	$('#secondStep').show();
      	$('#thirdStep').hide();
      }
      function ThirdNext(){
      	$('#thirdStep').hide();
      	$('#fourStep').show();
      	coupler();
      }
      function FourPrev(){
      	if($('input.cancelled').prop('checked') || $('input.goa').prop('checked')){
      		$('#firstStep').show();
      		$('#fourStep').hide();
      	}else{
      	$('#fourStep').hide();
      	$('#thirdStep').show();
      	}
      }
      function coupler(){
      	var base = $('select[name="enterprise_own"] option:selected').text();
      	var destination = $('#searchAddress').val();
      	var services = $('select[name="service"] option:selected').text();
      	var name = $('#name_client').val();
      	var email = $('#email_client').val();
      	var phone = $('#phone_client').val();
      	var enterprise = $('#enterprise').val();

      	$('#base_end').html(base);
      	$('#destination_end').html(destination);
      	$('#service_end').html(services);
      	$('#name_end').html(name);
      	$('#mail_end').html(email);
      	$('#phone_end').html(phone);
      	$('#enterprise_data').html(enterprise);

      	/*   Img   */
      	const $seleccionArchivos = document.querySelector("#file_end"),
      	$imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion");

	  		// Los archivos seleccionados, pueden ser muchos o uno
	  		const archivos = $seleccionArchivos.files;
  			// Si no hay archivos salimos de la función y quitamos la imagen
  			if (!archivos || !archivos.length) {
  				$imagenPrevisualizacion.src = "";
  				return;
  			}
  			// Ahora tomamos el primer archivo, el cual vamos a previsualizar
  			const primerArchivo = archivos[0];
  			// Lo convertimos a un objeto de tipo objectURL
  			const objectURL = URL.createObjectURL(primerArchivo);
  			// Y a la fuente de la imagen le ponemos el objectURL
  			$imagenPrevisualizacion.src = objectURL;
  		}
  		function geometry(lat,long,address) {
  			$('.listAddress').hide();
  			$('#searchAddress').val(address);
  			$('#lat_input').val(lat);
  			$('#lng_input').val(long);
  		}
  	</script>
  	@endsection