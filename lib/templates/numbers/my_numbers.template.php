<div class="col-xs-12">
	<div class="col-sm-4">
		<div class="form-inline">
			<div class="form-group">
				<input type="text" id="search" class="form-control" aria-label="search">
				<button type="button" id="search_submit" class="btn btn-default">Search</button>
				<button type="button" style="display: none;" id="search_clear" class="btn btn-default">Clear</button>
			</div>
		</div>
	</div>
	<div class="col-sm-4 col-sm-offset-4 text-right">
		<a href="#" id="page_left">Previous</a> | <a href="#" id="page_right">Next</a>
	</div>
</div>
<div class="col-xs-12">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Number</th>
					<th>Address</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody id="numbers_list"></tbody>
		</table>
	</div>
</div>

<!-- Closet -->
<div style="display: none;">
	<table>
		<tbody>
			<tr id="skeleton_no_numbers">
				<td colspan="3">You do not have any numbers with us. This page will be full of numbers soon, I am sure ;-)</td>
			</tr>
			<tr id="skeleton_number_row">
				<td data-id="number"></td>
				<td data-id="address"></td>
				<td>
					<select data-id="action" class="form-control">
						<option value="0"> --- </option>
						<option value="set_address">Change Address</option>
						<option value="remove">Remove</option>
					</select>
				</td>
			</tr>
		</tbody>
	</table>
</div>
