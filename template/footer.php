
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
	<link rel="stylesheet" href="http://localhost/timesheet/guts/css/flatpickr.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
	<script src="http://localhost/timesheet/guts/js/main.js"></script>
	<script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>

	<div class="modal" id="modal_update" aria-hidden="true">
		<h2 id="modal_header"></h2>
		<form method="post" class="form">
			<div class="field span-1">
				<label class="label">Date</label>
				<div class="control">
					<input id="modal_date" name="Date" class="input" type="date" value="" min="2018-01-01" max="6666-06-06" >
				</div>
			</div>
			<div class="field span-1">
				<label class="label">Start Time</label>
				<div class="control">
					<input id="modal_time" name="Time" class="input" type="text" value="" >
				</div>
			</div>
			<div class="field span-1">
				<label class="label">Duration</label>
				<div class="control">
					<input id="modal_duration" name="Duration" class="input" type="number" step="5" value="" >
				</div>
			</div>
			<div class="field span-1">
				<label class="label">Client</label>
				<div class="control">
					<input id="modal_client" name="Client" class="input" type="text" value="" >
				</div>
			</div>
			<div class="field span-2">
				<label class="label">Project</label>
				<div class="control">
					<input id="modal_project" name="Project" class="input" type="text" value="" >
				</div>
			</div>
			<div class="field span-3">
				<label class="label">Description</label>
				<div class="control">
					<input id="modal_description" name="Description" class="input" type="text" value="" >
				</div>
			</div>

			<input id="modal_affects" name="Affects" value="" type="hidden"/>

			<div class="field span-3">
				<div class="control">
					<button class="button is-link" type="submit" name="Submit" value="update">Submit</button>
				</div>
			</div>

		</form>
	</div>

</body>

</html>