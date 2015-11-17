/**
 *
 * A JQUERY GOOGLE MAPS LATITUDE AND LONGITUDE LOCATION PICKER
 * version 1.2
 *
 * Supports multiple maps. Works on touchscreen. Easy to customize markup and CSS.
 *
 * To see a live demo, go to:
 * http://www.wimagguc.com/projects/jquery-latitude-longitude-picker-gmaps/
 *
 * by Richard Dancsi
 * http://www.wimagguc.com/
 *
 */

(function($) {

// for ie9 doesn't support debug console >>>
if (!window.console) window.console = {};
if (!window.console.log) window.console.log = function () { };
// ^^^

$.fn.gMapsLatLonPicker = (function() {

	var _self = this;

	///////////////////////////////////////////////////////////////////////////////////////////////
	// PARAMETERS (MODIFY THIS PART) //////////////////////////////////////////////////////////////
	_self.params = {
		defLat : 0,
		defLng : 0,
		defZoom : 1,
		queryLocationNameWhenLatLngChanges: true,
		queryElevationWhenLatLngChanges: true,
		mapOptions : {
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
			disableDoubleClickZoom: true,
			zoomControlOptions: true,
			streetViewControl: false
		},
		strings : {
			markerText : "Drag this Marker",
			error_empty_field : "Couldn't find coordinates for this place",
			error_no_results : "Couldn't find coordinates for this place"
		}
	};


	///////////////////////////////////////////////////////////////////////////////////////////////
	// VARIABLES USED BY THE FUNCTION (DON'T MODIFY THIS PART) ////////////////////////////////////
	_self.vars = {
		ID : null,
		LATLNG : null,
		map : null,
		marker : null,
		geocoder : null
	};

	///////////////////////////////////////////////////////////////////////////////////////////////
	// PRIVATE FUNCTIONS FOR MANIPULATING DATA ////////////////////////////////////////////////////
	var setPosition = function(position) {
		_self.vars.marker.setPosition(position);
		_self.vars.map.panTo(position);

		$(_self.vars.cssID + ".gllpZoom").val( _self.vars.map.getZoom() );
		$(_self.vars.cssID + ".gllpLongitude").val( position.lng() );
		$(_self.vars.cssID + ".gllpLatitude").val( position.lat() );

		$(_self.vars.cssID).trigger("location_changed", $(_self.vars.cssID));

		if (_self.params.queryLocationNameWhenLatLngChanges) {
			getLocationName(position);
		}
		if (_self.params.queryElevationWhenLatLngChanges) {
			getElevation(position);
		}
	};

	// for reverse geocoding
	var getLocationName = function(position) {
		var latlng = new google.maps.LatLng(position.lat(), position.lng());
		_self.vars.geocoder.geocode({'latLng': latlng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK && results[1]) {
				$(_self.vars.cssID + ".gllpLocationName").val(results[1].formatted_address);
			} else {
				$(_self.vars.cssID + ".gllpLocationName").val("");
			}
			$(_self.vars.cssID).trigger("location_name_changed", $(_self.vars.cssID));
		});
	};

	// for getting the elevation value for a position
	var getElevation = function(position) {
		var latlng = new google.maps.LatLng(position.lat(), position.lng());

		var locations = [latlng];

		var positionalRequest = { 'locations': locations };

		_self.vars.elevator.getElevationForLocations(positionalRequest, function(results, status) {
			if (status == google.maps.ElevationStatus.OK) {
				if (results[0]) {
					$(_self.vars.cssID + ".gllpElevation").val( results[0].elevation.toFixed(3));
				} else {
					$(_self.vars.cssID + ".gllpElevation").val("");
				}
			} else {
				$(_self.vars.cssID + ".gllpElevation").val("");
			}
			$(_self.vars.cssID).trigger("elevation_changed", $(_self.vars.cssID));
		});
	};

	// search function
	var performSearch = function(string, silent) {
		if (string == "") {
			if (!silent) {
				displayError( _self.params.strings.error_empty_field );
			}
			return;
		}
		_self.vars.geocoder.geocode(
			{"address": string},
			function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					$(_self.vars.cssID + ".gllpZoom").val(11);
					_self.vars.map.setZoom( parseInt($(_self.vars.cssID + ".gllpZoom").val()) );
					setPosition( results[0].geometry.location );
				} else {
					if (!silent) {
						displayError( _self.params.strings.error_no_results );
					}
				}
			}
		);
	};

	// error function
	var displayError = function(message) {
		alert(message);
	};

	///////////////////////////////////////////////////////////////////////////////////////////////
	// PUBLIC FUNCTIONS  //////////////////////////////////////////////////////////////////////////
	var publicfunc = {

		// INITIALIZE MAP ON DIV //////////////////////////////////////////////////////////////////
		init : function(object) {

			if ( !$(object).attr("id") ) {
				if ( $(object).attr("name") ) {
					$(object).attr("id", $(object).attr("name") );
				} else {
					$(object).attr("id", "_MAP_" + Math.ceil(Math.random() * 10000) );
				}
			}

			_self.vars.ID = $(object).attr("id");
			_self.vars.cssID = "#" + _self.vars.ID + " ";

			_self.params.defLat  = $(_self.vars.cssID + ".gllpLatitude").val()  ? $(_self.vars.cssID + ".gllpLatitude").val()		: _self.params.defLat;
			_self.params.defLng  = $(_self.vars.cssID + ".gllpLongitude").val() ? $(_self.vars.cssID + ".gllpLongitude").val()	    : _self.params.defLng;
			_self.params.defZoom = $(_self.vars.cssID + ".gllpZoom").val()      ? parseInt($(_self.vars.cssID + ".gllpZoom").val()) : _self.params.defZoom;

			_self.vars.LATLNG = new google.maps.LatLng(_self.params.defLat, _self.params.defLng);

			_self.vars.MAPOPTIONS		 = _self.params.mapOptions;
			_self.vars.MAPOPTIONS.zoom   = _self.params.defZoom;
			_self.vars.MAPOPTIONS.center = _self.vars.LATLNG;

			_self.vars.map = new google.maps.Map($(_self.vars.cssID + ".gllpMap").get(0), _self.vars.MAPOPTIONS);
			_self.vars.geocoder = new google.maps.Geocoder();
			_self.vars.elevator = new google.maps.ElevationService();

			_self.vars.marker = new google.maps.Marker({
				position: _self.vars.LATLNG,
				map: _self.vars.map,
				title: _self.params.strings.markerText,
				draggable: true
			});

			// Set position on doubleclick
			google.maps.event.addListener(_self.vars.map, 'dblclick', function(event) {
				setPosition(event.latLng);
			});

			// Set position on marker move
			google.maps.event.addListener(_self.vars.marker, 'dragend', function(event) {
				setPosition(_self.vars.marker.position);
			});

			// Set zoom feld's value when user changes zoom on the map
			google.maps.event.addListener(_self.vars.map, 'zoom_changed', function(event) {
				$(_self.vars.cssID + ".gllpZoom").val( _self.vars.map.getZoom() );
				$(_self.vars.cssID).trigger("location_changed", $(_self.vars.cssID));
			});

			// Update location and zoom values based on input field's value
			$(_self.vars.cssID + ".gllpUpdateButton").bind("click", function() {
				var lat = $(_self.vars.cssID + ".gllpLatitude").val();
				var lng = $(_self.vars.cssID + ".gllpLongitude").val();
				var latlng = new google.maps.LatLng(lat, lng);
				_self.vars.map.setZoom( parseInt( $(_self.vars.cssID + ".gllpZoom").val() ) );
				setPosition(latlng);
			});

			// Search function by search button
			$(_self.vars.cssID + ".gllpSearchButton").bind("click", function() {
				performSearch( $(_self.vars.cssID + ".gllpSearchField").val(), false );
			});

			// Search function by gllp_perform_search listener
			$(document).bind("gllp_perform_search", function(event, object) {
				performSearch( $(object).attr('string'), true );
			});

			// Zoom function triggered by gllp_perform_zoom listener
			$(document).bind("gllp_update_fields", function(event) {
				var lat = $(_self.vars.cssID + ".gllpLatitude").val();
				var lng = $(_self.vars.cssID + ".gllpLongitude").val();
				var latlng = new google.maps.LatLng(lat, lng);
				_self.vars.map.setZoom( parseInt( $(_self.vars.cssID + ".gllpZoom").val() ) );
				setPosition(latlng);
			});
		}

	}




	return publicfunc;
});

$(document).ready( function() {
	$(".gllpLatlonPicker").each(function() {
		$(document).gMapsLatLonPicker().init( $(this) );
	});
});

$(document).bind("location_changed", function(event, object) {
	console.log("changed: " + $(object).attr('id') );
});
}(jQuery));

//# sourceMappingURL=data:application/json;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIm1haW4uanMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBIiwiZmlsZSI6ImJ1bmRsZS5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8qKlxuICpcbiAqIEEgSlFVRVJZIEdPT0dMRSBNQVBTIExBVElUVURFIEFORCBMT05HSVRVREUgTE9DQVRJT04gUElDS0VSXG4gKiB2ZXJzaW9uIDEuMlxuICpcbiAqIFN1cHBvcnRzIG11bHRpcGxlIG1hcHMuIFdvcmtzIG9uIHRvdWNoc2NyZWVuLiBFYXN5IHRvIGN1c3RvbWl6ZSBtYXJrdXAgYW5kIENTUy5cbiAqXG4gKiBUbyBzZWUgYSBsaXZlIGRlbW8sIGdvIHRvOlxuICogaHR0cDovL3d3dy53aW1hZ2d1Yy5jb20vcHJvamVjdHMvanF1ZXJ5LWxhdGl0dWRlLWxvbmdpdHVkZS1waWNrZXItZ21hcHMvXG4gKlxuICogYnkgUmljaGFyZCBEYW5jc2lcbiAqIGh0dHA6Ly93d3cud2ltYWdndWMuY29tL1xuICpcbiAqL1xuXG4oZnVuY3Rpb24oJCkge1xuXG4vLyBmb3IgaWU5IGRvZXNuJ3Qgc3VwcG9ydCBkZWJ1ZyBjb25zb2xlID4+PlxuaWYgKCF3aW5kb3cuY29uc29sZSkgd2luZG93LmNvbnNvbGUgPSB7fTtcbmlmICghd2luZG93LmNvbnNvbGUubG9nKSB3aW5kb3cuY29uc29sZS5sb2cgPSBmdW5jdGlvbiAoKSB7IH07XG4vLyBeXl5cblxuJC5mbi5nTWFwc0xhdExvblBpY2tlciA9IChmdW5jdGlvbigpIHtcblxuXHR2YXIgX3NlbGYgPSB0aGlzO1xuXG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXG5cdC8vIFBBUkFNRVRFUlMgKE1PRElGWSBUSElTIFBBUlQpIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXG5cdF9zZWxmLnBhcmFtcyA9IHtcblx0XHRkZWZMYXQgOiAwLFxuXHRcdGRlZkxuZyA6IDAsXG5cdFx0ZGVmWm9vbSA6IDEsXG5cdFx0cXVlcnlMb2NhdGlvbk5hbWVXaGVuTGF0TG5nQ2hhbmdlczogdHJ1ZSxcblx0XHRxdWVyeUVsZXZhdGlvbldoZW5MYXRMbmdDaGFuZ2VzOiB0cnVlLFxuXHRcdG1hcE9wdGlvbnMgOiB7XG5cdFx0XHRtYXBUeXBlSWQ6IGdvb2dsZS5tYXBzLk1hcFR5cGVJZC5ST0FETUFQLFxuXHRcdFx0bWFwVHlwZUNvbnRyb2w6IGZhbHNlLFxuXHRcdFx0ZGlzYWJsZURvdWJsZUNsaWNrWm9vbTogdHJ1ZSxcblx0XHRcdHpvb21Db250cm9sT3B0aW9uczogdHJ1ZSxcblx0XHRcdHN0cmVldFZpZXdDb250cm9sOiBmYWxzZVxuXHRcdH0sXG5cdFx0c3RyaW5ncyA6IHtcblx0XHRcdG1hcmtlclRleHQgOiBcIkRyYWcgdGhpcyBNYXJrZXJcIixcblx0XHRcdGVycm9yX2VtcHR5X2ZpZWxkIDogXCJDb3VsZG4ndCBmaW5kIGNvb3JkaW5hdGVzIGZvciB0aGlzIHBsYWNlXCIsXG5cdFx0XHRlcnJvcl9ub19yZXN1bHRzIDogXCJDb3VsZG4ndCBmaW5kIGNvb3JkaW5hdGVzIGZvciB0aGlzIHBsYWNlXCJcblx0XHR9XG5cdH07XG5cblxuXHQvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBWQVJJQUJMRVMgVVNFRCBCWSBUSEUgRlVOQ1RJT04gKERPTidUIE1PRElGWSBUSElTIFBBUlQpIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHRfc2VsZi52YXJzID0ge1xuXHRcdElEIDogbnVsbCxcblx0XHRMQVRMTkcgOiBudWxsLFxuXHRcdG1hcCA6IG51bGwsXG5cdFx0bWFya2VyIDogbnVsbCxcblx0XHRnZW9jb2RlciA6IG51bGxcblx0fTtcblxuXHQvLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHQvLyBQUklWQVRFIEZVTkNUSU9OUyBGT1IgTUFOSVBVTEFUSU5HIERBVEEgLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHR2YXIgc2V0UG9zaXRpb24gPSBmdW5jdGlvbihwb3NpdGlvbikge1xuXHRcdF9zZWxmLnZhcnMubWFya2VyLnNldFBvc2l0aW9uKHBvc2l0aW9uKTtcblx0XHRfc2VsZi52YXJzLm1hcC5wYW5Ubyhwb3NpdGlvbik7XG5cblx0XHQkKF9zZWxmLnZhcnMuY3NzSUQgKyBcIi5nbGxwWm9vbVwiKS52YWwoIF9zZWxmLnZhcnMubWFwLmdldFpvb20oKSApO1xuXHRcdCQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMb25naXR1ZGVcIikudmFsKCBwb3NpdGlvbi5sbmcoKSApO1xuXHRcdCQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMYXRpdHVkZVwiKS52YWwoIHBvc2l0aW9uLmxhdCgpICk7XG5cblx0XHQkKF9zZWxmLnZhcnMuY3NzSUQpLnRyaWdnZXIoXCJsb2NhdGlvbl9jaGFuZ2VkXCIsICQoX3NlbGYudmFycy5jc3NJRCkpO1xuXG5cdFx0aWYgKF9zZWxmLnBhcmFtcy5xdWVyeUxvY2F0aW9uTmFtZVdoZW5MYXRMbmdDaGFuZ2VzKSB7XG5cdFx0XHRnZXRMb2NhdGlvbk5hbWUocG9zaXRpb24pO1xuXHRcdH1cblx0XHRpZiAoX3NlbGYucGFyYW1zLnF1ZXJ5RWxldmF0aW9uV2hlbkxhdExuZ0NoYW5nZXMpIHtcblx0XHRcdGdldEVsZXZhdGlvbihwb3NpdGlvbik7XG5cdFx0fVxuXHR9O1xuXG5cdC8vIGZvciByZXZlcnNlIGdlb2NvZGluZ1xuXHR2YXIgZ2V0TG9jYXRpb25OYW1lID0gZnVuY3Rpb24ocG9zaXRpb24pIHtcblx0XHR2YXIgbGF0bG5nID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhwb3NpdGlvbi5sYXQoKSwgcG9zaXRpb24ubG5nKCkpO1xuXHRcdF9zZWxmLnZhcnMuZ2VvY29kZXIuZ2VvY29kZSh7J2xhdExuZyc6IGxhdGxuZ30sIGZ1bmN0aW9uKHJlc3VsdHMsIHN0YXR1cykge1xuXHRcdFx0aWYgKHN0YXR1cyA9PSBnb29nbGUubWFwcy5HZW9jb2RlclN0YXR1cy5PSyAmJiByZXN1bHRzWzFdKSB7XG5cdFx0XHRcdCQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMb2NhdGlvbk5hbWVcIikudmFsKHJlc3VsdHNbMV0uZm9ybWF0dGVkX2FkZHJlc3MpO1xuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0JChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscExvY2F0aW9uTmFtZVwiKS52YWwoXCJcIik7XG5cdFx0XHR9XG5cdFx0XHQkKF9zZWxmLnZhcnMuY3NzSUQpLnRyaWdnZXIoXCJsb2NhdGlvbl9uYW1lX2NoYW5nZWRcIiwgJChfc2VsZi52YXJzLmNzc0lEKSk7XG5cdFx0fSk7XG5cdH07XG5cblx0Ly8gZm9yIGdldHRpbmcgdGhlIGVsZXZhdGlvbiB2YWx1ZSBmb3IgYSBwb3NpdGlvblxuXHR2YXIgZ2V0RWxldmF0aW9uID0gZnVuY3Rpb24ocG9zaXRpb24pIHtcblx0XHR2YXIgbGF0bG5nID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhwb3NpdGlvbi5sYXQoKSwgcG9zaXRpb24ubG5nKCkpO1xuXG5cdFx0dmFyIGxvY2F0aW9ucyA9IFtsYXRsbmddO1xuXG5cdFx0dmFyIHBvc2l0aW9uYWxSZXF1ZXN0ID0geyAnbG9jYXRpb25zJzogbG9jYXRpb25zIH07XG5cblx0XHRfc2VsZi52YXJzLmVsZXZhdG9yLmdldEVsZXZhdGlvbkZvckxvY2F0aW9ucyhwb3NpdGlvbmFsUmVxdWVzdCwgZnVuY3Rpb24ocmVzdWx0cywgc3RhdHVzKSB7XG5cdFx0XHRpZiAoc3RhdHVzID09IGdvb2dsZS5tYXBzLkVsZXZhdGlvblN0YXR1cy5PSykge1xuXHRcdFx0XHRpZiAocmVzdWx0c1swXSkge1xuXHRcdFx0XHRcdCQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBFbGV2YXRpb25cIikudmFsKCByZXN1bHRzWzBdLmVsZXZhdGlvbi50b0ZpeGVkKDMpKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHQkKF9zZWxmLnZhcnMuY3NzSUQgKyBcIi5nbGxwRWxldmF0aW9uXCIpLnZhbChcIlwiKTtcblx0XHRcdFx0fVxuXHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0JChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscEVsZXZhdGlvblwiKS52YWwoXCJcIik7XG5cdFx0XHR9XG5cdFx0XHQkKF9zZWxmLnZhcnMuY3NzSUQpLnRyaWdnZXIoXCJlbGV2YXRpb25fY2hhbmdlZFwiLCAkKF9zZWxmLnZhcnMuY3NzSUQpKTtcblx0XHR9KTtcblx0fTtcblxuXHQvLyBzZWFyY2ggZnVuY3Rpb25cblx0dmFyIHBlcmZvcm1TZWFyY2ggPSBmdW5jdGlvbihzdHJpbmcsIHNpbGVudCkge1xuXHRcdGlmIChzdHJpbmcgPT0gXCJcIikge1xuXHRcdFx0aWYgKCFzaWxlbnQpIHtcblx0XHRcdFx0ZGlzcGxheUVycm9yKCBfc2VsZi5wYXJhbXMuc3RyaW5ncy5lcnJvcl9lbXB0eV9maWVsZCApO1xuXHRcdFx0fVxuXHRcdFx0cmV0dXJuO1xuXHRcdH1cblx0XHRfc2VsZi52YXJzLmdlb2NvZGVyLmdlb2NvZGUoXG5cdFx0XHR7XCJhZGRyZXNzXCI6IHN0cmluZ30sXG5cdFx0XHRmdW5jdGlvbihyZXN1bHRzLCBzdGF0dXMpIHtcblx0XHRcdFx0aWYgKHN0YXR1cyA9PSBnb29nbGUubWFwcy5HZW9jb2RlclN0YXR1cy5PSykge1xuXHRcdFx0XHRcdCQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBab29tXCIpLnZhbCgxMSk7XG5cdFx0XHRcdFx0X3NlbGYudmFycy5tYXAuc2V0Wm9vbSggcGFyc2VJbnQoJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscFpvb21cIikudmFsKCkpICk7XG5cdFx0XHRcdFx0c2V0UG9zaXRpb24oIHJlc3VsdHNbMF0uZ2VvbWV0cnkubG9jYXRpb24gKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHRpZiAoIXNpbGVudCkge1xuXHRcdFx0XHRcdFx0ZGlzcGxheUVycm9yKCBfc2VsZi5wYXJhbXMuc3RyaW5ncy5lcnJvcl9ub19yZXN1bHRzICk7XG5cdFx0XHRcdFx0fVxuXHRcdFx0XHR9XG5cdFx0XHR9XG5cdFx0KTtcblx0fTtcblxuXHQvLyBlcnJvciBmdW5jdGlvblxuXHR2YXIgZGlzcGxheUVycm9yID0gZnVuY3Rpb24obWVzc2FnZSkge1xuXHRcdGFsZXJ0KG1lc3NhZ2UpO1xuXHR9O1xuXG5cdC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXG5cdC8vIFBVQkxJQyBGVU5DVElPTlMgIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vXG5cdHZhciBwdWJsaWNmdW5jID0ge1xuXG5cdFx0Ly8gSU5JVElBTElaRSBNQVAgT04gRElWIC8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vLy8vL1xuXHRcdGluaXQgOiBmdW5jdGlvbihvYmplY3QpIHtcblxuXHRcdFx0aWYgKCAhJChvYmplY3QpLmF0dHIoXCJpZFwiKSApIHtcblx0XHRcdFx0aWYgKCAkKG9iamVjdCkuYXR0cihcIm5hbWVcIikgKSB7XG5cdFx0XHRcdFx0JChvYmplY3QpLmF0dHIoXCJpZFwiLCAkKG9iamVjdCkuYXR0cihcIm5hbWVcIikgKTtcblx0XHRcdFx0fSBlbHNlIHtcblx0XHRcdFx0XHQkKG9iamVjdCkuYXR0cihcImlkXCIsIFwiX01BUF9cIiArIE1hdGguY2VpbChNYXRoLnJhbmRvbSgpICogMTAwMDApICk7XG5cdFx0XHRcdH1cblx0XHRcdH1cblxuXHRcdFx0X3NlbGYudmFycy5JRCA9ICQob2JqZWN0KS5hdHRyKFwiaWRcIik7XG5cdFx0XHRfc2VsZi52YXJzLmNzc0lEID0gXCIjXCIgKyBfc2VsZi52YXJzLklEICsgXCIgXCI7XG5cblx0XHRcdF9zZWxmLnBhcmFtcy5kZWZMYXQgID0gJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscExhdGl0dWRlXCIpLnZhbCgpICA/ICQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMYXRpdHVkZVwiKS52YWwoKVx0XHQ6IF9zZWxmLnBhcmFtcy5kZWZMYXQ7XG5cdFx0XHRfc2VsZi5wYXJhbXMuZGVmTG5nICA9ICQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMb25naXR1ZGVcIikudmFsKCkgPyAkKF9zZWxmLnZhcnMuY3NzSUQgKyBcIi5nbGxwTG9uZ2l0dWRlXCIpLnZhbCgpXHQgICAgOiBfc2VsZi5wYXJhbXMuZGVmTG5nO1xuXHRcdFx0X3NlbGYucGFyYW1zLmRlZlpvb20gPSAkKF9zZWxmLnZhcnMuY3NzSUQgKyBcIi5nbGxwWm9vbVwiKS52YWwoKSAgICAgID8gcGFyc2VJbnQoJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscFpvb21cIikudmFsKCkpIDogX3NlbGYucGFyYW1zLmRlZlpvb207XG5cblx0XHRcdF9zZWxmLnZhcnMuTEFUTE5HID0gbmV3IGdvb2dsZS5tYXBzLkxhdExuZyhfc2VsZi5wYXJhbXMuZGVmTGF0LCBfc2VsZi5wYXJhbXMuZGVmTG5nKTtcblxuXHRcdFx0X3NlbGYudmFycy5NQVBPUFRJT05TXHRcdCA9IF9zZWxmLnBhcmFtcy5tYXBPcHRpb25zO1xuXHRcdFx0X3NlbGYudmFycy5NQVBPUFRJT05TLnpvb20gICA9IF9zZWxmLnBhcmFtcy5kZWZab29tO1xuXHRcdFx0X3NlbGYudmFycy5NQVBPUFRJT05TLmNlbnRlciA9IF9zZWxmLnZhcnMuTEFUTE5HO1xuXG5cdFx0XHRfc2VsZi52YXJzLm1hcCA9IG5ldyBnb29nbGUubWFwcy5NYXAoJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscE1hcFwiKS5nZXQoMCksIF9zZWxmLnZhcnMuTUFQT1BUSU9OUyk7XG5cdFx0XHRfc2VsZi52YXJzLmdlb2NvZGVyID0gbmV3IGdvb2dsZS5tYXBzLkdlb2NvZGVyKCk7XG5cdFx0XHRfc2VsZi52YXJzLmVsZXZhdG9yID0gbmV3IGdvb2dsZS5tYXBzLkVsZXZhdGlvblNlcnZpY2UoKTtcblxuXHRcdFx0X3NlbGYudmFycy5tYXJrZXIgPSBuZXcgZ29vZ2xlLm1hcHMuTWFya2VyKHtcblx0XHRcdFx0cG9zaXRpb246IF9zZWxmLnZhcnMuTEFUTE5HLFxuXHRcdFx0XHRtYXA6IF9zZWxmLnZhcnMubWFwLFxuXHRcdFx0XHR0aXRsZTogX3NlbGYucGFyYW1zLnN0cmluZ3MubWFya2VyVGV4dCxcblx0XHRcdFx0ZHJhZ2dhYmxlOiB0cnVlXG5cdFx0XHR9KTtcblxuXHRcdFx0Ly8gU2V0IHBvc2l0aW9uIG9uIGRvdWJsZWNsaWNrXG5cdFx0XHRnb29nbGUubWFwcy5ldmVudC5hZGRMaXN0ZW5lcihfc2VsZi52YXJzLm1hcCwgJ2RibGNsaWNrJywgZnVuY3Rpb24oZXZlbnQpIHtcblx0XHRcdFx0c2V0UG9zaXRpb24oZXZlbnQubGF0TG5nKTtcblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBTZXQgcG9zaXRpb24gb24gbWFya2VyIG1vdmVcblx0XHRcdGdvb2dsZS5tYXBzLmV2ZW50LmFkZExpc3RlbmVyKF9zZWxmLnZhcnMubWFya2VyLCAnZHJhZ2VuZCcsIGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0XHRcdHNldFBvc2l0aW9uKF9zZWxmLnZhcnMubWFya2VyLnBvc2l0aW9uKTtcblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBTZXQgem9vbSBmZWxkJ3MgdmFsdWUgd2hlbiB1c2VyIGNoYW5nZXMgem9vbSBvbiB0aGUgbWFwXG5cdFx0XHRnb29nbGUubWFwcy5ldmVudC5hZGRMaXN0ZW5lcihfc2VsZi52YXJzLm1hcCwgJ3pvb21fY2hhbmdlZCcsIGZ1bmN0aW9uKGV2ZW50KSB7XG5cdFx0XHRcdCQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBab29tXCIpLnZhbCggX3NlbGYudmFycy5tYXAuZ2V0Wm9vbSgpICk7XG5cdFx0XHRcdCQoX3NlbGYudmFycy5jc3NJRCkudHJpZ2dlcihcImxvY2F0aW9uX2NoYW5nZWRcIiwgJChfc2VsZi52YXJzLmNzc0lEKSk7XG5cdFx0XHR9KTtcblxuXHRcdFx0Ly8gVXBkYXRlIGxvY2F0aW9uIGFuZCB6b29tIHZhbHVlcyBiYXNlZCBvbiBpbnB1dCBmaWVsZCdzIHZhbHVlXG5cdFx0XHQkKF9zZWxmLnZhcnMuY3NzSUQgKyBcIi5nbGxwVXBkYXRlQnV0dG9uXCIpLmJpbmQoXCJjbGlja1wiLCBmdW5jdGlvbigpIHtcblx0XHRcdFx0dmFyIGxhdCA9ICQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMYXRpdHVkZVwiKS52YWwoKTtcblx0XHRcdFx0dmFyIGxuZyA9ICQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBMb25naXR1ZGVcIikudmFsKCk7XG5cdFx0XHRcdHZhciBsYXRsbmcgPSBuZXcgZ29vZ2xlLm1hcHMuTGF0TG5nKGxhdCwgbG5nKTtcblx0XHRcdFx0X3NlbGYudmFycy5tYXAuc2V0Wm9vbSggcGFyc2VJbnQoICQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBab29tXCIpLnZhbCgpICkgKTtcblx0XHRcdFx0c2V0UG9zaXRpb24obGF0bG5nKTtcblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBTZWFyY2ggZnVuY3Rpb24gYnkgc2VhcmNoIGJ1dHRvblxuXHRcdFx0JChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscFNlYXJjaEJ1dHRvblwiKS5iaW5kKFwiY2xpY2tcIiwgZnVuY3Rpb24oKSB7XG5cdFx0XHRcdHBlcmZvcm1TZWFyY2goICQoX3NlbGYudmFycy5jc3NJRCArIFwiLmdsbHBTZWFyY2hGaWVsZFwiKS52YWwoKSwgZmFsc2UgKTtcblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBTZWFyY2ggZnVuY3Rpb24gYnkgZ2xscF9wZXJmb3JtX3NlYXJjaCBsaXN0ZW5lclxuXHRcdFx0JChkb2N1bWVudCkuYmluZChcImdsbHBfcGVyZm9ybV9zZWFyY2hcIiwgZnVuY3Rpb24oZXZlbnQsIG9iamVjdCkge1xuXHRcdFx0XHRwZXJmb3JtU2VhcmNoKCAkKG9iamVjdCkuYXR0cignc3RyaW5nJyksIHRydWUgKTtcblx0XHRcdH0pO1xuXG5cdFx0XHQvLyBab29tIGZ1bmN0aW9uIHRyaWdnZXJlZCBieSBnbGxwX3BlcmZvcm1fem9vbSBsaXN0ZW5lclxuXHRcdFx0JChkb2N1bWVudCkuYmluZChcImdsbHBfdXBkYXRlX2ZpZWxkc1wiLCBmdW5jdGlvbihldmVudCkge1xuXHRcdFx0XHR2YXIgbGF0ID0gJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscExhdGl0dWRlXCIpLnZhbCgpO1xuXHRcdFx0XHR2YXIgbG5nID0gJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscExvbmdpdHVkZVwiKS52YWwoKTtcblx0XHRcdFx0dmFyIGxhdGxuZyA9IG5ldyBnb29nbGUubWFwcy5MYXRMbmcobGF0LCBsbmcpO1xuXHRcdFx0XHRfc2VsZi52YXJzLm1hcC5zZXRab29tKCBwYXJzZUludCggJChfc2VsZi52YXJzLmNzc0lEICsgXCIuZ2xscFpvb21cIikudmFsKCkgKSApO1xuXHRcdFx0XHRzZXRQb3NpdGlvbihsYXRsbmcpO1xuXHRcdFx0fSk7XG5cdFx0fVxuXG5cdH1cblxuXG5cblxuXHRyZXR1cm4gcHVibGljZnVuYztcbn0pO1xuXG4kKGRvY3VtZW50KS5yZWFkeSggZnVuY3Rpb24oKSB7XG5cdCQoXCIuZ2xscExhdGxvblBpY2tlclwiKS5lYWNoKGZ1bmN0aW9uKCkge1xuXHRcdCQoZG9jdW1lbnQpLmdNYXBzTGF0TG9uUGlja2VyKCkuaW5pdCggJCh0aGlzKSApO1xuXHR9KTtcbn0pO1xuXG4kKGRvY3VtZW50KS5iaW5kKFwibG9jYXRpb25fY2hhbmdlZFwiLCBmdW5jdGlvbihldmVudCwgb2JqZWN0KSB7XG5cdGNvbnNvbGUubG9nKFwiY2hhbmdlZDogXCIgKyAkKG9iamVjdCkuYXR0cignaWQnKSApO1xufSk7XG59KGpRdWVyeSkpO1xuIl0sInNvdXJjZVJvb3QiOiIvc291cmNlLyJ9