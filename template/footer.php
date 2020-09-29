
	<div class="modal" id="modal_update" aria-hidden="true">
		<div class="modal-background"></div>
		<div class="modal-content">
			<h2 class="modal_header"></h2>
			<form method="post" class="columns is-mobile is-multiline is-variable is-1">
				<div class="column is-9-mobile is-8">
					<label class="label">Date &amp; Time</label>
					<div class="control">
						<input class="modal_datetime" name="DateTime" class="DateTime input" value="" >
					</div>
				</div>
				<div class="column is-3-mobile is-4">
					<label class="label">Duration</label>
					<div class="control">
						<input class="modal_duration" name="Duration" class="input" type="number" step="5" value="" >
					</div>
				</div>
				<div class="column is-6 is-6-mobile">
					<label class="label">Client</label>
					<div class="control">
						<input class="modal_client" name="Client" class="input" type="text" value="" >
					</div>
				</div>
				<div class="column is-6 is-6-mobile">
					<label class="label">Project</label>
					<div class="control">
						<input class="modal_project" name="Project" class="input" type="text" value="" >
					</div>
				</div>
				<div class="column is-12">
					<label class="label">Description</label>
					<div class="control">
						<textarea class="modal_description" name="Description" class="textarea" type="text" oninput="auto_grow(this)"></textarea>
					</div>
				</div>

				<input class="modal_path" name="Path" value="" type="hidden"/>

				<div class="column is-6">
					<div class="control">
						<button class="button" type="submit" name="Submit" value="update">Submit</button>
					</div>
				</div>
			</form>
		</div>
		<button class="modal-close is-large" aria-label="close"></button>
	</div>
	<div class="modal" id="modal_delete" aria-hidden="true">
		<div class="modal-background"></div>
		<div class="modal-content">
			<h2 class="modal_header"></h2>
			<form method="post" class="columns is-multiline is-variable is-1">
				<div class="column is-12">
					You're about to delete this: <span class="modal_delete_path">-</span>
				</div>
				<input class="modal_path" name="Path" value="" type="hidden"/> 
				<div class="column is-6">
					<div class="control">
						<button class="button is-alert" type="submit" name="Submit" value="delete">Yes, delete it!</button>
					</div>
				</div>
			</form>
		</div>
		<button class="modal-close is-large" aria-label="close"></button>
	</div>
	<script>
		<?php echo $additional_js; ?>
	</script>
	<script src="guts/js/main.js"></script>
</body>

</html>