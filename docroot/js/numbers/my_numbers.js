var numbers = [];
var search = {
	limit: 100,
	offset: 0
};

$(function () {
	$('[data-toggle="tooltip"]').tooltip();
	get_numbers();
});

function get_numbers() {
	$.ajax({
		url: '/numbers/list',
		type: 'POST',
		data: search,
		success: function (resp) {
			if (!rcheck(resp)) return false;
			numbers = resp.data;
			load_numbers();
		}
	});
}

function load_numbers() {
	$numbers_list.empty();
	if (!numbers.length) {
		$numbers_list.append(skel('no_numbers'));
		return false;
	}

	for (var i=0,number; number = numbers[i]; i++) {
		var ma = number.ma;
		var $row = skel('number_row');
		$row.number.text(parseNum(number.number));
		var $a = $('<a href="#" data-toggle="tooltip" data-placement="right">' + ma.group_name + '</a>');
		$a.attr('tittle', ma.e_address + 
			(ma.e_unit_type == 'none') ? '' : ' ' + ma.e_unit_type + 
			(ma.e_unit_number == 'none') ? '' : ' ' + ma.e_unit_number + '<br>' + 
			ma.e_city + ', ' + 
			ma.e_state + ', ' + 
			ma.e_zip
		);
		$row.address.append($a);
		$row.action.change(action_select);
	}
}

function action_select() {
	var value = $(this).val();
	$(this).val('0');
	switch (value) {
		default: return false;
	}
}
