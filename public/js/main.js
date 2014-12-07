L.mapbox.accessToken = 'pk.eyJ1Ijoib2x1bWJ5IiwiYSI6IjFDTWM3V2sifQ.KXc-98r7KCMVsboqLE9nFA';
var map = L.mapbox.map('map', 'olumby.ke3hc51l')
	.setView([40, -74.50], 9)
	.addControl(L.mapbox.geocoderControl('mapbox.places-v1', {
		keepOpen: false
	}));

var featureLayer = L.mapbox.featureLayer()
	.loadURL('https://api.github.com/repos/mapbox/mapbox.js/contents/test/manual/example.geojson')
	.addTo(map);