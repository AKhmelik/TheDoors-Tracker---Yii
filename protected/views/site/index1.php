<!DOCTYPE html>
<html>
<head>
    <meta charset=utf-8 />
    <title>List markers and pan to clicked items</title>
    <meta name='viewport' content='initial-scale=1,maximum-scale=1,user-scalable=no' />
    <script src='https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.js'></script>
    <link href='https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.css' rel='stylesheet' />
    <style>
        body { margin:0; padding:0; }
        #map { position:absolute; top:0; bottom:0; width:100%; }
    </style>
</head>
<body>
<style>
    body { margin:0; padding:0; }
    #map { position:absolute; top:0; bottom:0; width:100%; }
    #marker-list {
        position:absolute;
        top:0; right:0; width:200px;
        bottom:0;
        overflow-x:auto;
        background:#fff;
        margin:0;
        padding:5px;
    }
    #marker-list li {
        padding:5px;
        margin:0;
        list-style-type:none;
    }
    #marker-list li:hover {
        background:#eee;
    }
</style>
<div id='map'></div>
<ul id='marker-list'></ul>
<script>

    L.mapbox.accessToken = 'pk.eyJ1IjoiZ2FsYXh5aXBjb20iLCJhIjoiTXpIWnp2dyJ9.b1OWpBE16Pr7ChqxMXjdlw';
    var map = L.mapbox.map('map', 'galaxyipcom.jk1jnj0c');
    var markerList = document.getElementById('marker-list');

    map.featureLayer.on('ready', function(e) {
        map.featureLayer.eachLayer(function(layer) {
            var item = markerList.appendChild(document.createElement('li'));
            item.innerHTML = layer.toGeoJSON().properties.title;
            item.onclick = function() {
                alert(1);
                map.setView(layer.getLatLng(), 7);
                layer.openPopup();
            };
        });
    });

    map.featureLayer.on('click', function(e) {
       console.log(e.layer);
    });

   




</script>
</body>
</html>













<script type="text/javascript">







//        begin



var side_bar_html = "";

var gmarkers = [];
var gicons = [];
var map = null;

var infowindow = new google.maps.InfoWindow(
{
size: new google.maps.Size(150,50)
});


gicons["red"] = new google.maps.MarkerImage("mapIcons/marker_red.png",
// This marker is 20 pixels wide by 34 pixels tall.
new google.maps.Size(20, 34),
// The origin for this image is 0,0.
new google.maps.Point(0,0),
// The anchor for this image is at 9,34.
new google.maps.Point(9, 34));
// Marker sizes are expressed as a Size of X,Y
// where the origin of the image (0,0) is located
// in the top left of the image.

// Origins, anchor positions and coordinates of the marker
// increase in the X direction to the right and in
// the Y direction down.

var iconImage = new google.maps.MarkerImage('mapIcons/marker_red.png',
// This marker is 20 pixels wide by 34 pixels tall.
new google.maps.Size(20, 34),
// The origin for this image is 0,0.
new google.maps.Point(0,0),
// The anchor for this image is at 9,34.
new google.maps.Point(9, 34));
var map= new google.maps.MarkerImage('http://www.google.com/mapfiles/shadow50.png',
// The shadow image is larger in the horizontal dimension
// while the position and offset are the same as for the main image.
new google.maps.Size(37, 34),
new google.maps.Point(0,0),
new google.maps.Point(9, 34));
// Shapes define the clickable region of the icon.
// The type defines an HTML &lt;area&gt; element 'poly' which
// traces out a polygon as a series of X,Y points. The final
// coordinate closes the poly by connecting to the first
// coordinate.
var iconShape = {
coord: [9,0,6,1,4,2,2,4,0,8,0,12,1,14,2,16,5,19,7,23,8,26,9,30,9,34,11,34,11,30,12,26,13,24,14,21,16,18,18,16,20,12,20,8,18,4,16,2,15,1,13,0],
type: 'poly'
};

function getMarkerImage(iconColor) {
if ((typeof(iconColor)=="undefined") || (iconColor==null)) {
iconColor = "red";
}
if (!gicons[iconColor]) {
gicons[iconColor] = new google.maps.MarkerImage("mapIcons/marker_"+ iconColor +".png",
// This marker is 20 pixels wide by 34 pixels tall.
new google.maps.Size(20, 34),
// The origin for this image is 0,0.
new google.maps.Point(0,0),
// The anchor for this image is at 6,20.
new google.maps.Point(9, 34));
}
return gicons[iconColor];

}

function category2color(category) {
var color = "red";
switch(category) {
case "theatre": color = "blue";
break;
case "golf":    color = "green";
break;
case "info":    color = "yellow";
break;
default:   color = "red";
break;
}
return color;
}

gicons["theatre"] = getMarkerImage(category2color("theatre"));
gicons["golf"] = getMarkerImage(category2color("golf"));
gicons["info"] = getMarkerImage(category2color("info"));

// A function to create the marker and set up the event window
function createMarker(latlng,name,html,category) {
var contentString = html;
var marker = new google.maps.Marker({
position: latlng,
icon: gicons[category],
shadow: iconShadow,
map: map,
title: name,
zIndex: Math.round(latlng.lat()*-100000)<<5
});
// === Store the category and name info as a marker properties ===
marker.mycategory = category;
marker.myname = name;
gmarkers.push(marker);

google.maps.event.addListener(marker, 'click', function() {
infowindow.setContent(contentString);
infowindow.open(map,marker);
});
}

// == shows all markers of a particular category, and ensures the checkbox is checked ==
function show(category) {
for (var i=0; i<gmarkers.length; i++) {
if (gmarkers[i].mycategory == category) {
gmarkers[i].setVisible(true);
}
}
// == check the checkbox ==
document.getElementById(category+"box").checked = true;
}

// == hides all markers of a particular category, and ensures the checkbox is cleared ==
function hide(category) {
for (var i=0; i<gmarkers.length; i++) {
if (gmarkers[i].mycategory == category) {
gmarkers[i].setVisible(false);
}
}
// == clear the checkbox ==
document.getElementById(category+"box").checked = false;
// == close the info window, in case its open on a marker that we just hid
infowindow.close();
}

// == a checkbox has been clicked ==
function boxclick(box,category) {
if (box.checked) {
show(category);
} else {
hide(category);
}
// == rebuild the side bar
makeSidebar();
}

function myclick(i) {
google.maps.event.trigger(gmarkers[i],"click");
}


// == rebuilds the sidebar to match the markers currently displayed ==
function makeSidebar() {
var html = "";
for (var i=0; i<gmarkers.length; i++) {
if (gmarkers[i].getVisible()) {
html += '<a href="javascript:myclick(' + i + ')">' + gmarkers[i].myname + '<\/a><br>';
    }
    }
    document.getElementById("side_bar").innerHTML = html;
    }

    function initialize() {
    var myOptions = {
    zoom: 11,
    center: new google.maps.LatLng(53.8363,-3.0377),
    mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map"), myOptions);


    google.maps.event.addListener(map, 'click', function() {
    infowindow.close();
    });



    // Read the data
    downloadUrl("categories.xml", function(doc) {
    var xml = xmlParse(doc);
    var markers = xml.documentElement.getElementsByTagName("marker");

    for (var i = 0; i < markers.length; i++) {
    // obtain the attribues of each marker
    var lat = parseFloat(markers[i].getAttribute("lat"));
    var lng = parseFloat(markers[i].getAttribute("lng"));
    var point = new google.maps.LatLng(lat,lng);
    var address = markers[i].getAttribute("address");
    var name = markers[i].getAttribute("name");
    var html = "<b>"+name+"<\/b><p>"+address;
            var category = markers[i].getAttribute("category");
            // create the marker
            var marker = createMarker(point,name,html,category);
            }

            // == show or hide the categories initially ==
            show("theatre");
            hide("golf");
            hide("info");
            // == create the initial sidebar ==
            makeSidebar();
            });
            }

            // This Javascript is based on code provided by the
            // Community Church Javascript Team
            // http://www.bisphamchurch.org.uk/
            // http://econym.org.uk/gmap/
            // from the v2 tutorial page at:
            // http://econym.org.uk/gmap/example_categories.htm
            //]]>

</script>
