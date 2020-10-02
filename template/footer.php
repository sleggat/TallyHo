
		<div class="modal" id="modal_update" aria-hidden="true">
			<div class="modal-background"></div>
			<div class="modal-card">
					<header class="modal-card-head">
						<p class="modal-card-title"></p>
						<button class="delete" aria-label="close"></button>
					</header>
					<section class="modal-card-body">
						<form method="post" class="columns is-mobile is-multiline is-variable is-1">
							<div class="column is-9-mobile is-8">
								<div class="field">
									<label class="label">Date &amp; Time</label>
									<div class="control">
										<input class="input modal_datetime" name="DateTime" class="DateTime input" value="" >
									</div>
								</div>
							</div>
							<div class="column is-3-mobile is-4">
								<div class="field">
									<label class="label">Duration</label>
									<div class="control">
										<input class="input modal_duration" name="Duration" class="input" value="" >
									</div>
								</div>
							</div>
							<div class="column is-6 is-6-mobile">
								<div class="field">
									<label class="label">Client</label>
									<?= output_dropdown($client_array, 'Client', 'modal_client', '', ''); ?>
								</div>
							</div>
							<div class="column is-6 is-6-mobile">
								<div class="field">
									<label class="label">Project</label>
									<?= output_dropdown($project_array, 'Project', 'modal_project', '', ''); ?>
								</div>
							</div>
							<div class="column is-12">
								<div class="field">
									<label class="label">Description</label>
									<div class="control">
										<textarea class="textarea modal_description" name="Description" class="textarea" type="text" oninput="auto_grow(this)"></textarea>
									</div>
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
				</section>
		</div>
		<div class="modal" id="modal_delete" aria-hidden="true">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title"></p>
					<button class="delete" aria-label="close"></button>
				</header>
				<section class="modal-card-body">
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
				</section>
			</div>
		</div>
		<script>
			<?php echo $additional_js; ?>
		</script>
		<script src="guts/js/main.js"></script>
	</body>
</html>