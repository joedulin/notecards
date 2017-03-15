<div class="text-right" id="actions" style="display: none; margin-bottom: 2em;">
	<button type="button" id="new_card_submit" class="btn btn-success">New Card</button>
	<button type="button" id="search_clear" class="btn btn-default" style="display: none;">Clear Search</button>
</div>
<div id="main_page">
	<div class="jumbotron">
		<h1>Welcome to Notecards</h1>
		<p>How noteworthy</p>
	</div>
</div>

<!-- Modals -->
<!-- New Project Modal -->
<div class="modal fade" id="new_project_modal" tabindex="-1" role="dialog" aria-labelledby="new_project_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times</span></button>
				<h4 class="modal-title" id="new_project_modal_label">New Project..</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-4">Project Name</label>
						<div class="col-sm-8">
							<input type="text" data-id="name" class="form-control">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" data-id="submit" class="btn btn-primary">Create</button>
			</div>
		</div>
	</div>
</div>

<!-- New Card Modal -->
<div class="modal fade" id="new_card_modal" tabindex="-1" role="dialog" aria-labelledby="new_card_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times</span></button>
				<h4 class="modal-title" id="new_card_modal_label">New Card..</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-4">Card Name</label>
						<div class="col-sm-8">
							<input type="text" data-id="card_name" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Notes</label>
						<div class="col-sm-8">
							<textarea data-id="card_data" class="form-control" rows="5"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" data-id="submit" class="btn btn-primary">Create</button>
			</div>
		</div>
	</div>
</div>

<!-- Edit Card Modal -->
<div class="modal fade" id="edit_card_modal" tabindex="-1" role="dialog" aria-labelledby="edit_card_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times</span></button>
				<h4 class="modal-title" id="edit_card_modal_label" data-id="label">New Card..</h4>
			</div>
			<div class="modal-body">
				<div class="form-horizontal">
					<div class="form-group">
						<label class="control-label col-sm-4">Card Name</label>
						<div class="col-sm-8">
							<input type="text" data-id="card_name" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-4">Notes</label>
						<div class="col-sm-8">
							<textarea data-id="card_data" class="form-control" rows="5"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" data-id="submit" class="btn btn-primary">Save</button>
			</div>
		</div>
	</div>
</div>

<!-- Remove Card Modal -->
<div class="modal fade" id="remove_card_modal" tabindex="-1" role="dialog" aria-labelledby="remove_card_modal_label">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times</span></button>
				<h4 class="modal-title" id="remove_card_modal_label" data-id="label">New Card..</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger">
					<p>
						This will permanently remove the selected card. Are you sure you want to do that?
					</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" data-id="submit" class="btn btn-danger">Remove</button>
			</div>
		</div>
	</div>
</div>

<!-- Closet -->
<div style="display: none;">
	<div id="skeleton_no_cards" class="jumbotron">
		<h1>No Cards</h1>
		<p>No cards found. Why no cards found?</p>
	</div>
	<div id="skeleton_card_row" class="row" style="display: none;">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 data-id="card_name" class="panel-title"></h3>
			</div>
			<div class="panel-body">
				<p data-id="card_data"></p>
				<div class="text-right">
					<button type="button" data-id="remove_card" class="btn btn-danger">Remove</button>
					<button type="button" data-id="edit" class="btn btn-primary">Edit</button>
				</div>
			</div>
		</div>
	</div>
</div>
