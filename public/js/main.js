$(document).ready(function () {

	var selectedFuel = 'GPR',
		coordinates = [40.0, -3.4],
		zoom = 6,
		priceTable = $('#priceTable');

	if(getUrlParameter("lat") && getUrlParameter("lng")) {
		coordinates = [getUrlParameter("lat"),getUrlParameter("lng")]
	}

	if(getUrlParameter("zoom")) {
		zoom = getUrlParameter("zoom");
	}

	console.log(getUrlParameter("lats"));

	L.mapbox.accessToken = 'pk.eyJ1Ijoib2x1bWJ5IiwiYSI6IjFDTWM3V2sifQ.KXc-98r7KCMVsboqLE9nFA';

	var geocoder = L.mapbox.geocoder('mapbox.places-v1');
	var map = L.mapbox.map('map', 'olumby.ke3hc51l', {
		attributionControl: false,
		infoControl: true,
		center: coordinates,
		zoom: zoom
	});

	var featureLayer = L.mapbox.featureLayer()
		.setStyle({color: 'red'});

	map.on('zoomend moveend', function () {
		updateMap()
	});

	updateMap()

	$('#fuelButtons button').on('click', function () {
		selectedFuel = $(this).data('fuel');
		updateMap()
		$('#fuelButtons button').each(function () {
			if ($(this).data('fuel') == selectedFuel) {
				$(this).addClass('selected')
				$(this).removeClass('deselected')
			} else {
				$(this).addClass('deselected')
				$(this).removeClass('selected')
			}
		});
	});

	function updateMap() {
		if (map.getZoom() > 12) {
			priceTable.children('tbody').empty()
			var coor = map.getCenter();
			var url = "http://gas.dev/api/prices/geojson/" + selectedFuel + "/" + coor.lat + "," + coor.lng + ",12";
			featureLayer.loadURL(url)
				.addTo(map)
				.on('ready', function (layer) {
					this.eachLayer(function (marker) {
						marker.setIcon(L.mapbox.marker.icon({
							'marker-color': '#CC0000',
							'marker-symbol': 'fuel'
						}));
						marker.bindPopup("<table><tr><td>Name</td><td>" + marker.feature.properties.name + "</td></tr><tr><td>Price</td><td>&euro;" + marker.feature.properties.price + "</td></tr><tr><td>Hours</td><td>" + marker.feature.properties.hours + "</td></tr></table>")
						var tr = priceTable.children('tbody').append('<tr>');
						tr.append('<td>' + marker.feature.properties.name + '</td>');
						tr.append('<td>' + marker.feature.properties.price + '</td>');
						tr.click(function() {
							map.setView(marker.getLatLng(), 14);
							marker.openPopup();
						});
					});
				});
		}
	}

	function getUrlParameter(sParam)
	{
		var sPageURL = window.location.hash.substring(1);

		var sURLVariables = sPageURL.split('#');
		for (var i = 0; i < sURLVariables.length; i++)
		{
			var sParameterName = sURLVariables[i].split('=');
			if (sParameterName[0] == sParam)
			{
				return sParameterName[1];
			}
		}
	}

});