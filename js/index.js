$("#seenoti").ready(function() {
	notification()
	setInterval(function() {
		notification()
	}, 4000);
});
