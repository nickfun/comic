/**
 * Comic Application
 *
 * @todo really need to re-think how many modules I need and what their function is
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

app.module('Main', function(oForm, oApp) {

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
		callProcess(options);
	}

	function callProcess( options ) {
		url = 'process.php' + oApp.utils.buildQueryString(options);
		$.getJSON(url, ajax_success);
	}
	
	function ajax_fail( oServerResponse ) {
		console.error("AJAX failed :-(");
	}
	
	/**
	 * @todo use a document fragment instead of appending each tag
	 */
	function ajax_success( oServerResponse ) {
		var i, list = oServerResponse.list;
		var $img;
		$('#output').empty();
		var bLinksOnly = $('#linksOnly').prop('checked');
		if( bLinksOnly ) {
			// add A tags
			for( i in list ) {
				$anchor = $('<li><a href="' + list[i] + '">' + list[i] + '</a></li>');
				$('#output').append($anchor);
			}
		} else {
			// add IMG tags
				for( i in list ) {
				$img = $('<li><img src="' + list[i] + '"></li>');
				$('#output').append($img);
			}
		}
	}
});

app.module("utils", function( oModule, oApp ) {
	
	/**
	 * Given key/value pairs, build query string for url
	 * @since 2013-03-28
	 */
	oModule.buildQueryString = function( options ) {
		if( _.isEmpty( options ) ) {
			return "";
		}
		// turn into '&key=value'
		var temp = _.map(options, function( value, key ) {
			return '&' + encodeURIComponent(key) + '=' + encodeURIComponent(value);
		});
		// string them all together
		var query = _.reduce(temp, function(previous, current) {
			return previous + current;
		});
		// replace first & with ?
		return '?' + query.slice(1);
	};
});

app.start();
















