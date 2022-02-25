@extends('Layouts.App')
@section('content')
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="card  card-dark mt-4">
				<div class="card-header">
					<h3 class="card-title">Sélectionnez le service et l'entreprise</h3>
				</div>
				<div class="card-body">
					<section id="firstStep">
						@include('Components/First')
					</section>
					<section id="secondStep">
						<form class="form-services">
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
											<input type="text" name="actual" class="form-control">
										</div>
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
						</form>
						<script>
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
    }


</script>
</section>
<section id="thirdStep">
	@include('Components/Third')
</section>
</div>
</div>
</div>
</div>
</div>
<script>
	$('#secondStep').hide();
	$('#thirdStep').hide();

	/**/

	function OneNext() {
		$('#firstStep').hide();
		$('#secondStep').show();
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
</script>
@endsection