
	<div class="modal" id="modal_update" aria-hidden="true">
		<h2 class="modal_header"></h2>
		<form method="post" class="grid-6">
			<div class="field span-4">
				<label class="label">Date &amp; Time</label>
				<div class="control">
					<input class="modal_datetime" name="DateTime" class="DateTime input" value="" >
				</div>
			</div>
			<div class="field span-2">
				<label class="label">Duration</label>
				<div class="control">
					<input class="modal_duration" name="Duration" class="input" type="number" step="5" value="" >
				</div>
			</div>
			<div class="field span-3">
				<label class="label">Client</label>
				<div class="control">
					<input class="modal_client" name="Client" class="input" type="text" value="" >
				</div>
			</div>
			<div class="field span-3">
				<label class="label">Project</label>
				<div class="control">
					<input class="modal_project" name="Project" class="input" type="text" value="" >
				</div>
			</div>
			<div class="field span-6">
				<label class="label">Description</label>
				<div class="control">
					<input class="modal_description" name="Description" class="input" type="text" value="" >
				</div>
			</div>

			<input class="modal_path" name="Path" value="" type="hidden"/>

			<div class="field span-6">
				<div class="control">
					<button class="button is-link" type="submit" name="Submit" value="update">Submit</button>
				</div>
			</div>

		</form>
	</div>
	<div class="modal" id="modal_delete" aria-hidden="true">
		<h2 class="modal_header"></h2>
		<form method="post" class="grid-6">
			<div class="span-6">
				You're about to delete this: <span class="modal_delete_path">-</span>
			</div>
			<input class="modal_path" name="Path" value="" type="hidden"/> 
			<div class="field span-6">
				<div class="control">
					<button class="button is-alert" type="submit" name="Submit" value="delete">Yes, delete it!</button>
				</div>
			</div>
		</form>
	</div>
	<script>
		<?php echo $additional_js; ?>
	</script>
	<script src="guts/js/main.js"></script>
</body>

</html>