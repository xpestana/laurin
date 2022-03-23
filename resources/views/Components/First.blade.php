	<div class="row">
		<div class="col-md-3 mb-4 goa">
			<div class="custom-control custom-checkbox">
				<input class="custom-control-input" type="checkbox" id="canceled" name="canceled" onchange="annulle()">
				<label for="canceled" class="custom-control-label">Annulé?</label>
			</div>		
		</div>
		<div class="col-md-3 mb-4 annulle">
			<div class="custom-control custom-checkbox">
				<input class="custom-control-input" type="checkbox" id="goa" name="goa" onchange="goals()">
				<label for="goa" class="custom-control-label">GOA</label>
			</div>		
		</div>
		<div class="col-md-3 mb-4 annulle goa">
			<div class="form-group">
				<label for="flair">Flair</label>
				<select class="custom-select form-control-border" id="flair" name="flair">
					<option value="0">0</option>
					<option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
				</select>
			</div>		
		</div>
		<div class="col-md-3 mb-4 annulle goa">
			<div class="form-group">
				<label for="essence">Essence</label>
				<select class="custom-select form-control-border" id="essence" name="essence">
					<option value="0">0</option>
					<option value="5">5$</option>
					<option value="10">10$</option>
				</select>
			</div>		
		</div>
		<div class="col-md-6 mb-4 annulle goa">
			<div class="form-group">
				<label for="service">Service</label>
				<select class="custom-select form-control-border" id="service" name="service" required="">
					<option value="T1">CREVAISON</option>
					<option value="T3">SURVOLTAGE</option>
					<option value="T4">ESSENCE</option>
					<option value="T5">ENLISSEMENT</option>
					<option value="T6">REMORQUAGE</option>
					<option value="T7">DÉVERROUILLAGE</option>
					<option value="T8">ACCIDENT</option>
					<option value="SAISIE">SAISIE</option>
					<option value="REMISAGE">REMISAGE</option>
				</select>
			</div>
		</div>
		<div class="col-md-6 mb-4 annulle goa">
			<div class="form-group">
				<label for="enterprise">Enterprise</label>
				<select class="custom-select form-control-border" id="enterprise" name="enterprise" required="">
					<option value="PDG">PDG</option>
					<option value="ALLSTATE">ALLSTATE</option>
					<option value="AXA">AXA</option>
					<option value="GSA">GSA</option>
					<option value="ASSISTEL">ASSISTEL</option>
					<option value="ROAD">ROAD</option>
					<option value="ASSISTENZA">ASSISTENZA</option>
					<option value="URGENTLY">URGENTLY</option>
				</select>
			</div>
		</div>
		<div class="col-md-6 annulle goa">
			<div class="form-group">
				<label>Fête et heure de l'événement</label>
				<div class="input-group date" id="dateTime">
					<input type="datetime-local" name="dateTime" class="form-control" required="">
				</div>
			</div>
		</div>	
		<div class="col-md-3 annulle goa">
			<div class="form-group">
				<label>Durée du service (min)</label>
				<div class="input-group date" id="time_services">
					<input type="number" name="time_services" class="form-control" min="0" required="">
					<div class="input-group-append ml-1" data-target="#reservationdatetime">
						minutes
					</div>
				</div>
			</div>
		</div>	
		<div class="w-100"></div>
		<div align="right" class="col-md-3 ml-auto mt-3">
			<button type="button" class="btn btn-block btn-outline-success btn-flat" onclick="OneNext()">Suivant</button>
		</div>
	</div>
	<script>
		/* ANNULLE*/

		function annulle(){
			$('.annulle').toggle();
		}

		/* GOA*/

		function goals(){
			$('.goa').toggle();
		}
	</script>