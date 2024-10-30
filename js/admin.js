$j=jQuery.noConflict();
$j(function() {
	$j('#words tr').each(function() {
		addDelButton(this);
	});
	$j('#w-add').focus(function() {
		if ($j(this).val() == 'Add new phrase...') {
			$j(this).val('');
		}
	}).blur(function() {
		if ($j(this).val().length == 0) {
			$j(this).val('Add new phrase...');
		}
	});
	$j('#w-add-button').click(function() {
		addPhrase();		
	});
});
function addDelButton(spec) {
	var id;
	id = $j('input', spec).attr('id');
	id = id.split("-")[1];
	$j('input', spec).after('<input class="button-secondary delete" type="submit" id="' + id + '-del" value="Del" />');
	$j('#' + id + '-del', spec).click(function() {
		$j.ajax({
			type: "POST",
			url: ajaxpost,
			data: {action: 'jqs_random_del', data: id, cookie: encodeURIComponent(document.cookie)},
			success: function(xml) {
				//test for errors
				if ($j(xml).find('v').text() == 'false') {
					$j(xml).find('err').each(function() {
						alert($j(this).text());
					});
				} else { //Data Accepted...
					$j('#w-' + id).parent("#words tr").remove();
				}
			}
		});
	
	});
}

function addPhrase() {
	data = $j('#w-add').val();
	if (data.length >0 && data != 'Add new phrase...') {
		$j('#w-add-button').unbind("click");

		$j.ajax({
			type: "POST",
			url: ajaxpost,
			data: {action: 'jqs_random_add', data: data, cookie: encodeURIComponent(document.cookie)},
			success: function(xml) {
				//test for errors
				if ($j(xml).find('v').text() == 'false') {
					$j(xml).find('err').each(function() {
						alert($j(this).text());
					});
				} else { //Data Accepted...
					v = $j(xml).find('v').text();
					//Update page
					//Insert new row with data
					$j('#words').append('<tr><td><input type="text" name="words" id="w-' + v + '" value="' + data + '" style="width: 300px;"/></td></tr>');
					addDelButton($j('#w-' + v).parent("#words tr"));
					//Flash update message
					//Reset the add field...
					$j('#w-add').val('Add new phrase...');
				}
			}
		
		
		});

		$j('#w-add-button').click(function() {
			addPhrase();		
		});
	}

}

function addPhrase2() {
	data = $j('#w-add').val();
	if (data.length >0 && data != 'Add new phrase...') {
		$j('#w-add-button').unbind("click");

		mySack = new sack(ajaxpost);
		mySack.execute = 1;
		mySack.method = "POST";
		mySack.setVar("action", "jqs-random-add");
		mySack.setVar("data", data);
		mySack.setVar("jqs", "Mine!");
		mySack.encVar("cookie", document.cookie, false);
		mySack.onError = function() {alert('There was some sort of error...');};
		mySack.runAJAX();

		$j('#w-add-button').click(function() {
			addPhrase();		
		});
	}

}
