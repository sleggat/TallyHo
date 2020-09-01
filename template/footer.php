
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
	<script src="http://localhost/timesheet/guts/js/main.js"></script>
	<script type="module" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule="" src="https://unpkg.com/ionicons@5.1.2/dist/ionicons/ionicons.js"></script>

	<div class="modal" id="modal_update" aria-hidden="true">
		<h2 id="modal_header"></h2>
		<form method="post" class="form">
			<div class="field span-2">
				<label class="label">Date &amp; Time</label>
				<div class="control">
					<input id="modal_datetime" name="DateTime" class="DateTime input" value="" >
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