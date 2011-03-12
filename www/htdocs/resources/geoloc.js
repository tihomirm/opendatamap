function geoloc()
{
//    document.getElementById('workarea').innerHTML = '<div style="text-decoration:blink;font-size:200%">Geo-locating</div><div> this might take a little while...</div>';
//    document.getElementById('geobutton').style.display = 'none';
    navigator.geolocation.getCurrentPosition(
       function(position) {        
             if( position.coords.accuracy < 5000 )
             {
		map.setCenter(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
	     }
             else
	     {
                alert('Sorry, geo location wildly inaccurate ('+ position.coords.accuracy+" meters)"); 
//                document.getElementById('workarea').innerHTML = 'Geo location wildly inaccurate ('+ position.coords.accuracy+" meters)"; 
             }
//             document.getElementById('geobutton').style.display = 'block';
       },        
       function(e) {
             alert('Sorry, geo location failed'); 
//             document.getElementById('workarea').innerHTML = 'Geo location failed'; 
//             document.getElementById('geobutton').style.display = 'block';
       }
    );                                                                  
}
function initgeoloc()
{
	if (navigator.geolocation) {
		var geobutton = document.getElementById('geobutton');
		geobutton.style.display = 'block';
		geobutton.index = 1;
		map.controls[google.maps.ControlPosition.TOP_RIGHT].push(geobutton);
	}
}       