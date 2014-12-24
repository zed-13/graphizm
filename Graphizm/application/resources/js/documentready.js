var processData = function(filepath, htmlZone) {
	"use strict";

	$.getJSON(filepath, function(data) {
		// Analysis subjects
		var age = 0,
			nbRecords = data.length;
		for(var $i = 0; $i < nbRecords; $i++) {
			age += data[$i].age;
		}
		age = age / nbRecords;
		$("#age-" + htmlZone).html(age);
		$("#nb-records-" + htmlZone).html(nbRecords);
		
		//Data processing
		
	});
};

var pieChart = function(data, colors) {
	var size = data.length;
		
};

$(document).ready(
	function() {
	"use strict";

		$("#launch-process").click(function(){
			processData("data/data-dna-healthy.json", "healthy");
			processData("data/data-dna-sick.json", "sick");
			$("#toto").show(800,"linear");
		});
		$("#contact").click(function(){$("#dialog").dialog();});
		$("#blabla").click(function(){$("#resources").dialog();});
		$("#export").click(function(){$("#exports").dialog();});

	}
);