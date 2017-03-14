<div class="col-sm-12">
	<div class="row" style="margin-bottom: 2em;">
		<div class="col-sm-4 col-sm-offset-1">
			<h3>Viewing: <small id="viewing">None</small></h3>
		</div>
	</div>
	<div class="row" style="max-height: 75%;">
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Addresses</h3>
				</div>
				<div class="panel-body">
					<div class="form-inline" id="new_address">
						<input type="text" data-id="name" class="form-control" placeholder="New Address..." style="width: 65%;">
						<button type="button" data-id="new_address_submit" class="btn btn-primary" style="width: 30%;">Create</button>
					</div>
				</div>
				<div class="list-group" id="addresses_list" style="overflow: auto; max-height: 400px;">

				</div>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-horizontal" id="address_info">
				<div class="form-group">
					<label class="control-label col-sm-4">Name</label>
					<div class="col-sm-8">
						<input type="text" data-id="name" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">Address</label>
					<div class="col-sm-8">
						<input type="text" data-id="address" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">Unit Type</label>
					<div class="col-sm-8">
						<select data-id="unit_type" class="form-control">
							<option value="none">N/A</option>
							<option value="ste">Suite</option>
							<option value="apt">Apartment</option>
						</select>
					</div>
				</div>
				<div class="form-group" data-id="unit_number_div" style="display: none;">
					<label class="control-label col-sm-4">Unit Number</label>
					<div class="col-sm-8">
						<input type="text" data-id="unit_number" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">City</label>
					<div class="col-sm-8">
						<input type="text" data-id="city" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">State</label>
					<div class="col-sm-8">
						<select data-id="state" class="form-control">
							<?php foreach ($this->states_array() as $abbr => $name) {
								printf('<option value="%s">%s</option>', $abbr, $name);
							} ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-4">Zip</label>
					<div class="col-sm-8">
						<input type="text" data-id="zip" class="form-control">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-12 text-right">
						<button type="button" data-id="update_submit" class="btn btn-primary">Save Changes</button>
						<button type="button" data-id="manage_endpoints" class="btn btn-success">Manage Endpoints</button>
						<button type="button" data-id="remove_submit" class="btn btn-danger">Remove Address</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Numbers</h3>
				</div>
				<div class="panel-body">
					<div class="form-inline" id="new_number">
						<input type="text" data-id="number" class="form-control" placeholder="Add Number.." style="width: 65%;">
						<button type="button" data-id="number_submit" class="btn btn-primary" style="width: 30%;">Add</button>
					</div>
				</div>
				<ul class="list-group" id="numbers_list" style="overflow: auto; max-height: 400px;"></ul>
			</div>
		</div>
	</div>
</div>

<!-- Closet -->
<div style="display: none;">
	<a href="#" class="list-group-item" id="skeleton_address_row"></a>
	<li class="list-group-item" id="skeleton_number_row"></li>
</div>
