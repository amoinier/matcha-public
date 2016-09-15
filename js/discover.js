var nbpage = 0;

function filtre(nbpage) {
	if (document.getElementById('order').value == 'ASC' || document.getElementById('order').value == 'DESC') {
		if (document.getElementById('filtra').value == 'Age') {
			if (document.getElementById('order').value == 'ASC') {
				discover('`birthdate` DESC', nbpage);
			}
			else {discover('`birthdate` ASC', nbpage);}
		}
		else if (document.getElementById('filtra').value == 'Name') {
			discover('`name` '+document.getElementById('order').value, nbpage);
		}
		else if (document.getElementById('filtra').value == 'Popularity Score') {
			discover('`popscore` '+document.getElementById('order').value, nbpage);
		}
		else if (document.getElementById('filtra').value == 'Default') {
			discover('X', nbpage);
		}
		else if (document.getElementById('filtra').value == 'Tags') {
			discover('`tags` '+document.getElementById('order').value, nbpage);
		}
		else if (document.getElementById('filtra').value == 'Localisation') {
			discover('`postalcode` '+document.getElementById('order').value, nbpage);
		}
	}
}

function change_val() {
	nbpage = 0;
	stoppage = 0;
	filtre(nbpage);
}

$('#discopeo').ready(function() {
	discover('X', nbpage);
});

$("#prev").click(function() {
  if (nbpage > 0) {
	  nbpage -= 1;
  }
  filtre(nbpage);
});

$("#next").click(function() {
	if (stoppage - 1 > nbpage) {
  	  nbpage += 1;
    }
    filtre(nbpage);
});

$('#filtra').change(function () {
	change_val();
});

$('#order').change(function () {
	change_val();
});

// --- OPTIONS ---

$('#age1').change(function () {
	change_val();
});

$('#age2').change(function () {
	change_val();
});

$('#pop1').change(function () {
	change_val();
});

$('#pop2').change(function () {
	change_val();
});

$('#pop2').change(function () {
	change_val();
});

$("#searchloc").on("change keyup paste mouseout mousein mouse", function(){
    change_val();
})
