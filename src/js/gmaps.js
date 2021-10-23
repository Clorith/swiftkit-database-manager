// Check to see if the map container exists, if it does build the map
if ($("#map").length > 0) {

    var centreLat=0.0;
    var centreLon=0.0;
    var initialZoom=3;
    var imageWraps=false; //SET THIS TO false TO PREVENT THE IMAGE WRAPPING AROUND

    var map; //the GMap3 itself
    var markersArray = []; // array for the map markers we place
    mapLoaded = false;
    var gmicMapType;

    var sendposX, sendposY; // Variables for later when we need to send data to get RS coordinates

    function GMICMapType() {
        this.Cache = Array();
        this.opacity = 1.0;
    }
    GMICMapType.prototype.tileSize = new google.maps.Size(256, 256);
    GMICMapType.prototype.maxZoom = 19;
    GMICMapType.prototype.getTile = function(coord, zoom, ownerDocument) {
        var c = Math.pow(2, zoom);
        var c = Math.pow(2, zoom);
        var tilex=coord.x,tiley=coord.y;
        if (imageWraps) {
            if (tilex<0) tilex=c+tilex%c;
            if (tilex>=c) tilex=tilex%c;
            if (tiley<0) tiley=c+tiley%c;
            if (tiley>=c) tiley=tiley%c;
        }
        else {
            if ((tilex<0)||(tilex>=c)||(tiley<0)||(tiley>=c))
            {
                var blank = ownerDocument.createElement('DIV');
                blank.style.width = this.tileSize.width + 'px';
                blank.style.height = this.tileSize.height + 'px';
                return blank;
            }
        }
        var img = ownerDocument.createElement('IMG');
        var d = tilex;
        var e = tiley;
        var f = "t";
        for (var g = 0; g < zoom; g++) {
            c /= 2;
            if (e < c) {
                if (d < c) { f += "q" }
                else { f += "r"; d -= c }
            }
            else {
                if (d < c) { f += "t"; e -= c }
                else { f += "s"; d -= c; e -= c }
            }
        }
        img.id = "t_" + f;
        img.style.width = this.tileSize.width + 'px';
        img.style.height = this.tileSize.height + 'px';
        img.src = "/images/rsmap/"+f+".jpg";
        this.Cache.push(img);
        return img;
    }
    GMICMapType.prototype.realeaseTile = function(tile) {
        var idx = this.Cache.indexOf(tile);
        if(idx!=-1) this.Cache.splice(idx, 1);
        tile=null;
    }
    GMICMapType.prototype.name = "SwiftKit";
    GMICMapType.prototype.alt = "SwiftKit";
    GMICMapType.prototype.setOpacity = function(newOpacity) {
        this.opacity = newOpacity;
        for (var i = 0; i < this.Cache.length; i++) {
            this.Cache[i].style.opacity = newOpacity; //mozilla
            this.Cache[i].style.filter = "alpha(opacity=" + newOpacity * 100 + ")"; //ie
        }
    }

    function getWindowHeight() {
        if (window.self&&self.innerHeight) {
            return self.innerHeight;
        }
        if (document.documentElement&&document.documentElement.clientHeight) {
            return document.documentElement.clientHeight;
        }
        return 0;
    }

    function genCoords (map,x,y){
        return map.getProjection().fromPointToLatLng(new google.maps.Point(x,y));
    }
    function addMarker(location) {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
        markersArray.push(marker);
    }
    function clearOverlays() {
        if (markersArray) {
            for (i in markersArray) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }
    }
    function load() {
        var myOptions = {
            zoom: 4,
            minZoom: 0,
            maxZoom: 6,
            panControl: false,
            zoomControl: true,
            center: new google.maps.LatLng(0,0),
            mapTypeControl: true,
            scaleControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            mapTypeControlOptions: { mapTypeIds: ["ImageCutter"] },
            mapTypeId: "ImageCutter"
        }
        map = new google.maps.Map(document.getElementById("map"), myOptions);

        var infoWindow = new google.maps.InfoWindow();

        gmicMapType = new GMICMapType();
        map.mapTypes.set("ImageCutter",gmicMapType);

        google.maps.event.addListener(map, 'click', function (coords) {
            clearOverlays();
            addMarker(coords.latLng);
            sendposX = coords.ga.x;
            sendposY = coords.ga.y;
        });
    };
}