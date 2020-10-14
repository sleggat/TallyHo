		<div class="modal" id="modal_form" aria-hidden="true">
			<div class="modal-background"></div>
			<div class="modal-card">
				<header class="modal-card-head">
					<p class="modal-card-title"></p>
					<div class="buttons has-addons">
						<button class="button is-small is-white set_duration">Duration</button>
						<button class="button is-small is-link set_expense">Expense</button>
					</div>
					<button class="delete" aria-label="close"></button>
				</header>
				<section class="modal-card-body">
					<form method="post" name="main_form" id="main_form" onsubmit="return validateForm()" class="columns is-mobile is-multiline is-variable is-1">
						<div class="column is-6-mobile is-6">
							<div class="field">
								<label class="label">End Date &amp; Time</label>
								<div class="control">
									<input class="input modal_datetime" name="DateTime" value="" required>
								</div>
							</div>
						</div>
						<div class="column is-6-mobile is-6">
							<div class="field field_duration">
								<label class="label">Duration (mins)</label>
								<div class="control">
									<?= output_dropdown(range(0, 720, $default_timeincrement), 'Duration', 'modal_duration', $default_timeincrement, ''); ?>
								</div>
							</div>
							<div class="field field_expense hide">
								<label class="label">Expense (<?= CURRENCY_SYMBOL ?>)</label>
								<div class="control">
									<input class="input modal_expense" name="Expense" value="">
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
									<textarea class="textarea modal_description" name="Description" type="text" oninput="auto_grow(this)"></textarea>
								</div>
							</div>
						</div>

						<input class="modal_path" name="Path" value="" type="hidden" />
						<input class="modal_tasktype" name="TaskType" value="duration" type="hidden" />

						<div class="column is-3">
							<div class="control">
								<button class="button is-primary" type="submit" name="Submit" value="update">Submit</button>
							</div>
						</div>
						<div class="column is-9">
							<div class="form_error notification is-danger is-hidden"></div>
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
						<input class="modal_path" name="Path" value="" type="hidden" />
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