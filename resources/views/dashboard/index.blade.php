@extends('layouts.app')

@section('title', 'crossenergy | Dashboard')

@section('content')
    <div id="content" class="app-content" style="position: relative">
        <div id="trigger">
            <button id="open_results" class="btn btn-success">Drivers</button>
        </div>
        <style>
            .results_block {
                height: 600px;
                overflow-y: scroll;
            }
            .driver_card__title {
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 5px;
            }
            .driver_card__text {
                font-size: 12px;
                margin-bottom: 5px;
            }
        </style>
        <style>
            body {
                overflow-x: hidden;
            }
            #mydiv {
                position: absolute;
                right: 40px;
                top: 40px;
                z-index: 9999;
                width: 500px;
                background-color: #f1f1f1;
                border: 1px solid #d3d3d3;
                padding: 0;
                display: none;
                border-radius: 0.25rem;
            }

            #mydivheader {
                padding: 10px;
                cursor: move;
                z-index: 10;
                background-color: #424344;
                color: #fff;
                border-radius: 0.25rem;
                margin-bottom: 10px;

            }
            #trigger {
                position: absolute;
                z-index: 9999;
                left: 85px;
                top: 44px;
                width: 125px;
            }
            #findbox {
                padding: 10px;
            }
            #map {
                width: 100%;
                height: 85vh;
            }
            .offcanvas-end {
                width: 40% !important;
            }
        </style>
        <div id="mydiv">
            <div id="mydivheader">
                <p class="text-center mb-0">Drag and move</p>
            </div>

            <div class="m-16">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="input-group">
                            <div class="col-12">
                                <div id="findbox"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="input-group" style="padding: 10px">
                            <select id="miles" class="form-control mb-8" aria-label="Radius">
                                <option value='150'>150 miles</option>
                                <option value='200'>200 miles</option>
                                <option value='300' selected>300 miles</option>
                                <option value='600'>600 miles</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="drivers_list" style="padding: 10px;height: 500px;overflow: auto"></div>

        </div>
        <div id="map"></div>
    </div>

    @include('parts.offc')

@endsection

@push('css')
    <link href="{{asset('assets/css/leaflet.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/esri-leaflet-geocoder.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/leaflet-routing-machine.css')}}" rel="stylesheet">
@endpush
@push('js')
    <script src="{{asset('assets/js/leaflet.js')}}"></script>
    <script src="{{asset('assets/js/esri-leaflet.js')}}"></script>
    <script src="{{asset('assets/js/esri-leaflet-geocoder.js')}}"></script>
    <script src="{{asset('assets/js/leaflet-routing-machine.js')}}"></script>
    <script src="{{asset('assets/js/moment.js')}}"></script>

    <script>
        dragElement(document.getElementById("mydiv"));
        function dragElement(el) {
            let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
            if (document.getElementById(el.id + "header")) {
                /* if present, the header is where you move the DIV from:*/
                document.getElementById(el.id + "header").onmousedown = dragMouseDown;
            } else {
                /* otherwise, move the DIV from anywhere inside the DIV:*/
                el.onmousedown = dragMouseDown;
            }

            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                el.style.top = (el.offsetTop - pos2) + "px";
                el.style.left = (el.offsetLeft - pos1) + "px";
            }

            function closeDragElement() {
                /* stop moving when mouse button is released:*/
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }
    </script>
    <script>
        let url = '{{route('driver.getdrivers')}}';
        console.log(url);
        let theMarker;
        let theCircle;
        let geojsonLayer;

        let routes;


        let map = L.map('map').setView([39.0, -98.26], 5);

        let searchControl = L.esri.Geocoding.geosearch({
            zoomToResult: false,
            collapseAfterResult: false,
            expanded: true,
            placeholder: 'Enter adress or zipcode',
        }).addTo(map);

        document.getElementById('findbox').appendChild(
            document.querySelector(".geocoder-control")
        );


        let results = L.layerGroup().addTo(map);


        searchControl.on('results', function (data) {
            results.clearLayers();
            ProcessClick(data.results[0].latlng.lat, data.results[0].latlng.lng);
            results.clearLayers();
        });

        let mapbox = L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/dark-v10/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoicnVzbGFuaWFzIiwiYSI6ImNsZHdzbjA1NTA5ZXkzb3AweXUzcWhmbHAifQ.XW1kq9eYfqPlM_SAfVp2Dw', {
            attribution: '&copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
            maxZoom: 16,
            tileSize: 512,
            zoomOffset: -1,
        }).addTo(map);

        let baseMaps = {
            "Mapbox Dark": mapbox,
        };

        // Set style function that sets fill color property
        function style(feature) {
            return {
                fillColor: setColor(feature.properties.availability),
                fillOpacity: 0.5,
                weight: 2,
                opacity: 1,
                color: '#ffffff',
                dashArray: '3'
            };
        }

        let carDriver;

        function getDriverPhotos(id) {
            let url = "{{route('driver.getImages', '')}}" + "/" + id;
            let photosBlock = document.querySelector('.car-photos');
            photosBlock.innerHTML = '<div class="text-center">\n' +
                '    <div class="spinner-border" role="status">\n' +
                '        <span class="visually-hidden">Loading...</span>\n' +
                '    </div>\n' +
                '</div>';

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    photosBlock.innerHTML = '';
                    if(response.data.length > 0) {
                        for (i = 0; i < response.data.length; i++) {
                            let filename = response.data[i]['filename'];
                            //console.log(filename);
                            let img = '/storage/' + filename;
                            photosBlock.innerHTML +=
                                '<div class="col-lg-6 col-12">\n' +
                                '<a href="'+ img +'" target="_blank"><img class="img-fluid" src="'+ img +'"></a>\n' +
                                '</div>';
                        }
                    }
                    else {
                        photosBlock.innerHTML = '<div class="alert alert-danger" role="alert">\n' +
                            'Photos not found!\n' +
                            '</div>'
                    }
                },
                error: function(e) {

                }
            });
        }

        // Get GeoJSON data and create features.
        $.getJSON(url, function(data) {
            //console.log(data);
            carDriver = L.geoJson(data, {
                pointToLayer: function(feature, latlng) {
                    return L.circleMarker(latlng, {
                        radius:4,
                        opacity: .5,
                        color: feature.properties.vehicle_type_color,
                        fillColor:  feature.properties.vehicle_type_color,
                        fillOpacity: 0.8

                    });  //.bindTooltip(feature.properties.fullname);
                },
                onEachFeature: function (feature, layer) {
                    layer._leaflet_id = feature.properties.fullname;

                    let availability = '';
                    feature.properties.availability ? availability = '<span style="color: #06aff3">Available</span>' : availability = '<span style="color: #f32424">Not available</span>';

                    let popupContent = "<div style='font-size: 14px'>" +
                        "<span style='font-size:16px;color:"+ feature.properties.vehicle_type_color +"'><b>" + feature.properties.fullname + "</b></span>" + "</br>" +
                        "Phone: " + feature.properties.phone + "</br>" +
                        "Location: " + feature.properties.location + ', ' + feature.properties.zipcode + "</br>" +
                        "Vehicle: " + feature.properties.vehicle_type + "</br>" +
                        "Dimension: " + feature.properties.dimension + "</br>" +
                        "Capacity: " + feature.properties.capacity + "</br>" +
                        availability + "</br>" +
                        '<a class="photo-icon" data-id="' + feature.properties.id + '" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample" onclick="getDriverPhotos(' + feature.properties.id + ')"> <i class="bi bi-image fs-20px"></i> </a>' +
                        "<hr>" +
                        feature.properties.note + "</br>" +
                        '</div>' ;

                    if (feature.properties && feature.properties.popupContent) {
                        popupContent += feature.properties.popupContent;
                    }
                    layer.bindPopup(popupContent);

                }
            }).addTo(map);
        });

        map.on('click',function(e){
            lat = e.latlng.lat;
            lon = e.latlng.lng;
            ProcessClick(lat,lon)
        });

        function dropPin(lat, lon) {
            map.panTo([lat,lon]);
            ProcessClick(lat,lon);
        }

        $('#miles').on('change', function(){
            SelectPoints(theMarker._latlng.lat,theMarker._latlng.lng);
        })

        //marker icon
        let flag = L.icon({
            iconUrl: '{{asset('assets/img/flag_marker.png')}}',
            iconSize:     [48, 48], // size of the icon
            iconAnchor:   [22, 48], // point of the icon which will correspond to marker's location
            popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
        });

        function ProcessClick(lat,lon){
            //Clear existing marker, circle, and selected points if selecting new points
            if (theCircle !== undefined) {
                map.removeLayer(theCircle);
            }
            if (theMarker !== undefined) {
                map.removeLayer(theMarker);
            }
            if (geojsonLayer !== undefined) {
                map.removeLayer(geojsonLayer);
            }

            //Add a marker to show where you clicked.
            theMarker = L.marker([lat,lon], {icon: flag}).addTo(map);

            SelectPoints(lat,lon);
            $("#mydiv").show();
        }
        let selPts = [];

        function SelectPoints(lat,lon){
            let dist = document.getElementById("miles").value;
            let xy = [lat,lon];  //center point of circle
            let theRadius = parseInt(dist) * 1609.34 ; //1609.34 meters in a mile
            selPts.length = 0;  //Reset the array if selecting new points
            carDriver.eachLayer(function (layer) {
                // Lat, long of current point as it loops through.
                let layer_lat_long = layer.getLatLng();
                // Distance from our circle marker To current point in meters
                let distance_from_centerPoint = layer_lat_long.distanceTo(xy);
                // See if meters is within radius, add the to array
                if (distance_from_centerPoint <= theRadius) {
                    selPts.push(layer.feature);
                }

            });

            //Take array of features and make a GeoJSON feature collection
            let GeoJS = { type: "FeatureCollection",  features: selPts   };
            let driverList = document.querySelector('.drivers_list');
            driverList.innerHTML = '';

            for (let i = 0; i < selPts.length; i++) {

                // Distance between marker and driver in miles
                // let toDriver = [parseFloat(selPts[i].properties.latitude), parseFloat(selPts[i].properties.longitude)];
                // let distance = map.distance(toDriver, xy);
                // let miles_to_driver = Math.round((distance.toFixed(0)/1000) / 1.609);

                routes = L.Routing.control({
                    waypoints: [
                        L.latLng([parseFloat(selPts[i].properties.latitude), parseFloat(selPts[i].properties.longitude)]),
                        L.latLng(xy)
                    ],
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: false,

                    createMarker: function() { return null; },
                    lineOptions: {
                        styles: [{color: 'transparent', opacity: 0, weight: 0}]
                    }
                }).addTo(map);

                routes._container.style.display = "None";

                routes.on('routesfound', function(e) {

                    route_distance = Math.round((e.routes[0].summary.totalDistance.toFixed(0)/1000) / 1.609)
                    console.log(route_distance)

                    let equip = '';
                    let future_datetime;
                    let future_timestamp = new Date(selPts[i].properties.future_datetime).getTime();
                    let future_format = moment(future_timestamp).format('MMM, DD h:hh A');

                    if (selPts[i].properties.availability === false && future_timestamp > Date.now()) {
                        future_datetime = '<div class="badge text-danger" style="display: block;white-space: normal">' + future_format + '</div>';
                    } else {
                        future_datetime = '';
                    }

                    let available = selPts[i].properties.availability ?
                        '<span class="text-success fw-bold" style="font-size: 12px;">Available</span>' :
                        '<span class="text-danger fw-bold" style="font-size: 12px;">Not available</span>';

                    let NABgColor = selPts[i].properties.availability ?
                        '' :
                        'style="background-color: #ffe5e5"';

                    let dnu = selPts[i].properties.dnu ?
                        '<span class="badge bg-warning py-5px">DNU</span>' :
                        '';

                    for (let j = 0; j < selPts[i].properties.equipments.length; j++)
                    {
                        equip += '<span class="badge bg-indigo py-5px me-1">'+ selPts[i].properties.equipments[j] +'</span>';
                    }

                    let haveEquip = '';
                    if(selPts[i].properties.equipments.length > 0) {
                        haveEquip = '<div class="row">\n' +
                            '<div class="col-4">\n' +
                            '<p class="driver_card__title">Equipment:</p>\n' +
                            '</div>\n' +
                            '<div class="col-8">\n' +
                            '' + equip + ' \n' +
                            '</div>\n' +
                            '</div>\n'
                    }

                    //console.log(selPts[i].properties.owner_name)

                    driverList.innerHTML += '<div class="card rounded border p-2 mb-3 driver_card"'+NABgColor+'>\n' +
                        '<div class="row">\n' +
                        '    <div class="col-12">\n' +
                        '        <div class="row align-items-start justify-content-between">\n' +
                        '            <div class="" data-id="' + selPts[i].properties.id + '" data-name="' + selPts[i].properties.fullname + '"><h5 class=" hp-cursor-pointer mb-8" style="color: '+selPts[i].properties.vehicle_type_color+'">' + selPts[i].properties.number + ' ' + selPts[i].properties.fullname + ' ' + dnu +'</h5></div>\n' +
                        '        </div>\n' +
                        '    </div>\n' +
                        '    <div class="col-12">\n' +
                        '        <div class="row g-16 justify-content-between">\n' +
                        '            <div class="col-12">\n' +
                        '                <div class="row justify-content-between">\n' +
                        '                    <div class="col-lg-9">\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Owner:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.owner_name + ' ' + selPts[i].properties.owner_number + '</span>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Location:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.location + ' , ' + selPts[i].properties.zipcode + '</span>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Dimensions:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.dimension + '</span>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Capacity:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.capacity + '</span>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Status:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.citizenship + '</span>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Registration exp date:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.register_expdate + '</span>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '\n' +
                        haveEquip +
                        '\n' +
                        '                        <div class="row">\n' +
                        '                            <div class="col-4">\n' +
                        '                                <p class="driver_card__title">Note:</p>\n' +
                        '                            </div>\n' +
                        '                            <div class="col-8">\n' +
                        '                                <span class="driver_card__text">' + selPts[i].properties.note + '</span>\n' +
                        '                            </div>\n' +
                        '                        </div>\n' +
                        '                    </div>\n' +
                        '\n' +
                        '                    <div class="col-lg-3 text-center">\n' +
                        '                        <div class="badge mb-12 border-0 fs-12px" style="display: block;white-space: normal;color:#fff;background-color: ' +selPts[i].properties.vehicle_type_color+ '">' + selPts[i].properties.vehicle_type + '</div>\n' +
                        '                        <h5 class="mileValue mt-2">' + route_distance + ' mi</h5>\n' +
                        '                        <div>'+ available + '</div>\n' +
                        '                        '+ future_datetime +'\n' +
                        '                            <a class="photo-icon" data-id="' + selPts[i].properties.id + '" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample" onclick="getDriverPhotos(' + selPts[i].properties.id + ')"> <i class="bi bi-image fs-20px"></i> </a>\n' +
                        '                    </div>\n' +
                        '                </div>\n' +
                        '            </div>\n' +
                        '\n' +
                        '        </div>\n' +
                        '    </div>\n' +
                        '  </div>\n' +
                        '</div>';

                    sortDriversByDistance();

                })


            }


            function sortDriversByDistance(){
                let sections = $(".drivers_list").children().detach();
                sections.sort(function(a, b) {
                    let aa = parseInt($(a).find(".mileValue").text());
                    let bb = parseInt($(b).find(".mileValue").text());
                    if (aa > bb) return 1;
                    if (aa < bb) return -1;
                    return 0;
                });
                $(".drivers_list").append(sections);
            }

        }	//end of SelectPoints function

        $("#open_results").click(function () {
            $("#mydiv").toggle();
        });
    </script>

@endpush
