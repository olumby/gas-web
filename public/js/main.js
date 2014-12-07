L.mapbox.accessToken = 'pk.eyJ1Ijoib2x1bWJ5IiwiYSI6IjFDTWM3V2sifQ.KXc-98r7KCMVsboqLE9nFA';

var map = L.mapbox.map('map', 'olumby.ke3hc51l', {
	attributionControl: false,
	infoControl: true
});
map.setView([40.0, -3.4], 6)
	.addControl(L.mapbox.geocoderControl('mapbox.places-v1', {
		keepOpen: false,
		position: 'topright',
	}));

var featureLayer = L.mapbox.featureLayer('examples.map-h61e8o8e').on('ready', function (e) {

	var markers = new L.MarkerClusterGroup({
		spiderfyOnMaxZoom: true,
		showCoverageOnHover: false,
		maxClusterRadius: 60,
		disableClusteringAtZoom: 13,
	});

	e.target.eachLayer(function (layer) {
		markers.addLayer(layer);
	});
	map.addLayer(markers);

})
	.loadURL('http://gas.dev/api/prices/geojson/GPR');