var groups = [];
var numbers = [];
var active_group = {};
var search = {};

$(function () {
	get_groups();
	$address_info.unit_type.on('change', function () {
		if ($(this).val() == 'none') {
			$address_info.unit_number_div.hide();
		} else {
			$address_info.unit_number_div.show();
		}
	});
	$new_address.new_address_submit.click(create_tmp_group);
	$address_info.update_submit.click(update_group);
})

function get_groups() {
	$.ajax({
		url: '/ma/addresses/list',
		type: 'POST',
		data: search,
		success: function (resp) {
			if (!rcheck(resp)) return false;
			groups = resp.data;
			load_groups();
		}
	});
}

function load_groups() {
	$addresses_list.empty();
	if (!groups.length) return false;
	for (var i=0,group; group = groups[i]; i++) {
		var $row = skel('address_row');
		$row.text(group.group_name);
		$row.data('group', group);
		$row.click(load_group);
		$addresses_list.append($row);
	}
	if ($address_list.find('a').length) {
		$address_list.find('a:first').click();
	}
}

function create_tmp_group() {
	var group_name = $new_address.name.val();
	$new_address.name.val('');
	if (!group_name) return false;
	$addresses_list.find('a').removeClass('active');
	var group = {
		name: '',
		e_address: '',
		e_unit_type: 'none',
		e_unit_number: '',
		e_city: '',
		e_state: 'AL',
		e_zip: '',
		group_name: group_name
	};
	var $row = skel('address_row');
	$row.text(group_name);
	$row.data('group', group);
	$row.click(load_group);
	$addresses_list.append($row);
	$row.click();
}

function update_group() {
	var group = $(this).data('group');
	var $ai = $address_info;
	group.name = $ai.name.val();
	group.e_address = $ai.address.val();
	group.e_unit_type = $ai.unit_type.val();
	group.e_unit_number = (group.e_unit_type == 'none') ? null : $ai.unit_number.val();
	group.e_city = $ai.city.val();
	group.e_state = $ai.state.val();
	group.e_zip = $ai.zip.val();
	var url = (typeof group.id == 'undefined') ? '/ma/addresses/create' : '/ma/addresses/modify';
	$.ajax({
		url: url,
		type: 'POST',
		data: group,
		success: function (resp) {
			if (!rcheck(resp)) return false;
			growl('Successfully saved changes');
		}
	})
}

function load_group() {
	var group = $(this).data('group');
	$ai = $address_info;
	for (var k in group) {
		if (typeof $ai[k] != 'undefined') {
			$ai[k].val(group[k]);
		}
	}
	$viewing.text(group.group_name);
	$ai.update_submit.data('group', group);
	$ai.unit_type.change();
	$(this).addClass('active');
}
