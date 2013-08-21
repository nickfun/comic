/**
 * Comic Application
 *
 * @author nickfun
 * @date 2013-08-20
 */

window.app = new Backbone.Marionette.Application();

app.addInitializer(function() {
	console.log('comic app has init');
});

// Form Module
// ===========

app.module('Form', function(oForm, oApp) {

	// listen for when the form is submitted
	$(function() {
		$('#main').submit(function(e){
			e.preventDefault();
			formWasSubmitted();
		});
	});
	
	// deal with the form being submitted
	function formWasSubmitted() {
		console.log('i heard about the form!');
		var options = {
			template: $('#template').val(),
			pattern:  $('#pattern').val(),
			linksOnly: $('#linksOnly').prop('checked')
		};
		console.log(options);
	}
});

app.start();
