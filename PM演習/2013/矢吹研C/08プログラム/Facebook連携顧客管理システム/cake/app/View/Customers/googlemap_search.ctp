<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<style type="text/css">
			html { height: 90% }
			body { height: 100%; margin: 0px; padding: 10px }
			#map_canvas { height: 100% }
		</style>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=ja">
		</script>
		<script type="text/javascript">
			var geocoder;
			var map;

			function map_initialize() {
				geocoder = new google.maps.Geocoder();
				var latlng = new google.maps.LatLng(-34.397, 150.644);
				var myOptions = {
					zoom: 18,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				}
				map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
				
				var address = "<?php echo __($googlemap_searchs_word);?>";
				
				geocoder.geocode( { 'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						map.setCenter(results[0].geometry.location);
						var marker = new google.maps.Marker({
							map: map,
							position: results[0].geometry.location
						});
						
						var contentString = '<div id="content">'+
						    '<div id="siteNotice"></div><h3 id="firstHeading" class="firstHeading">'+address+'</h3><div id="bodyContent"></div></div>';

						var infowindow = new google.maps.InfoWindow({
						    content: contentString
						});
						
						/** クリックイベント追加 */
						google.maps.event.addListener(marker, 'click', function() {
							infowindow.open(map,marker);
						});
						/** 初期表示 */
						infowindow.open(map,marker);
					} else {
						if (status == "ZERO_RESULTS") {
							alert('一致する情報は見つかりませんでした：' + address);
							window.close();
						} else {
							alert('エラーが発生しました：' + status);
						}
					}
				});
				

			}
		</script>
	</head>
	<body onload="map_initialize()">
		<div id="map_canvas" style="width:70%;border:1px #000000 solid;">
	</body>
</html>

