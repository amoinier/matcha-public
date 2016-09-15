var pagesearch = 0;

function search(pagesearch) {
	if (document.getElementById('order').value == 'ASC' || document.getElementById('order').value == 'DESC') {
		if (document.getElementById('filtra').value == 'Age') {
			if (document.getElementById('order').value == 'ASC') {
				adsearch('`birthdate` DESC', pagesearch);
			}
			else {adsearch('`birthdate` ASC', pagesearch);}
		}
		else if (document.getElementById('filtra').value == 'Name') {
			adsearch('`name` '+document.getElementById('order').value, pagesearch);
		}
		else if (document.getElementById('filtra').value == 'Popularity Score') {
			adsearch('`popscore` '+document.getElementById('order').value, pagesearch);
		}
		else if (document.getElementById('filtra').value == 'Tags') {
			adsearch('`tags` '+document.getElementById('order').value, pagesearch);
		}
		else if (document.getElementById('filtra').value == 'Localisation') {
			adsearch('`postalcode` '+document.getElementById('order').value, pagesearch);
		}
	}
}

$("#prev").click(function() {
  if (pagesearch > 0) {
	  pagesearch -= 1;
  }
  search(pagesearch);
});

$("#next").click(function() {
	if (stoppage - 1 > pagesearch) {
  	  pagesearch += 1;
    }
    search(pagesearch);
});

$('#filtra').change(function () {
	pagesearch = 0;
	stoppage = 0;
	search(pagesearch);
});

$('#order').change(function () {
	pagesearch = 0;
	stoppage = 0;
	search(pagesearch);
});
