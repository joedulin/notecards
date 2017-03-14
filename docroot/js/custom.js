$(function () {
	$('[id]').each(function (i,v) {
		window['$' + $(v).attr('id')] = $(v);
		$.each($(v).find('[data-id]'), function (index, value) {
			window['$' + $(v).attr('id')][$(value).attr('data-id')] = $(value);
		});
	});

	$(document).ajaxStart(function () {
		$('body').addClass('loading');
	});
	$(document).ajaxStop(function () {
		$('body').removeClass('loading');
	});
	$(document).trigger('run');
});

function rcheck(resp) {
	if (resp.code != 200) {
		if (typeof resp.data == 'string') {
			growl(resp.data, 'danger');
		}
		return false;
	}
	return true;
}

function growl(message, status) {
	$.notify({
		message: message,
	}, {
		type: status,
		allow_dismiss: true,
		newest_on_top: true,
		placement: {
			from: 'top',
			align: 'right'
		},
		offset: 20,
		spacing: 10,
		z_index: 2000,
		delay: 3000,
		mouse_over: 'pause'
	});
}

function skel(id) {
	var $skel = window['$' + 'skeleton_' + id];
	$skel = $skel.clone();
	$skel.removeAttr('id');
	$.each($skel.find('[data-id]'), function (i,v) {
		$skel[$(v).data('id')] = $(v);
	});
	return $skel;
}

function titleCase(string) {
	string = string.split(' ');
	var replace = [];
	for (var i=0,word; word = string[i]; i++) {
		word = word.toLowerCase();
		word = word.split('');
		word[0] = word[0].toUpperCase();
		replace.push(word.join(''));
	}
	return replace.join(' ');
}

function string_reverse(string) {
	return String(string).split('').reverse().join('');
}

function parseNum(number) {
	var reverse = string_reverse(number);
	var ret = {};
	ret.xxxx = string_reverse(reverse.substring(0, 4));
	ret.nxx = string_reverse(reverse.substring(4, 7));
	ret.npa = string_reverse(reverse.substring(7, 10));
	if (reverse.length > 10) {
		ret.country_code = string_reverse(reverse.substring(10, reverse.length));
	} else {
		ret.country_code = '1';
	}
	ret.display = '+' + ret.country_code + ' (' + ret.npa + ') ' + ret.nxx + '-' + ret.xxxx;
	ret.tendigit = String(ret.npa) + String(ret.nxx) + String(ret.xxxx);
	ret.e164 = '+' + String(number);
	return ret;
}
