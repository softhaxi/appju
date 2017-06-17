<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('app.name')}} · {{trans('app.full_name')}}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
        <style>
            html, body, .map { 
                height: 100%
            }
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .navbar-container {
                padding: 0px 10px 0px 0px;
            }
            .main-container {
                height: 100%;
                left: 0px;
                right: 0px;
            }
            .map-content {
                top: 50px;
                bottom: 30px;
                overflow: auto;
                position: absolute;
                left: 0px;
                right: 0px;
            }
            .search-container {
                width: 400px;
                position: absolute;
                top: 70px;
                left: 10px;
                padding: 16px 16px 0px 16px;
                background-color: #ffffff;
                box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.46);
                z-index: 0;
            }
            footer {
                bottom: 0;
                left: 0;
                right: 0;
                position: fixed;
                width: 100%;
                z-index: 999;
                padding: 5px 0;
                box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.37);
                background-color: #ffffff;
            }
        </style>
        <link href="{{url('/css/typeahead.css')}}" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{url('/home')}}">{{trans('app.name')}}</a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="{{url('home')}}">{{trans('menu.home')}}</a></li>
                        <li><a href="{{url('user')}}">{{trans('menu.user')}}</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{trans('menu.street_lighting')}}  <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="clearfix">
                                    <a href="{{url('streetlighting')}}">{{trans('menu.registered_street_lighting')}}</a></li>
                                </li>
                                <li class="clearfix">
                                    <a href="{{url('streetlighting/unregistered')}}">{{trans('menu.unregistered_street_lighting')}}</a></li>
                                </li>
                            </ul>
                        </li>
                        <li><a href="{{url('survey')}}">{{trans('menu.survey')}}</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}  <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li class="clearfix">
                                    <a href="{{ url('/signout') }}"
                                        onclick="event.preventDefault();
                                            document.getElementById('signout-form').submit();">
                                        <i class="fa fa-power-off fa-fw pull-right"></i> {{trans('button.sign_out')}}
                                    </a>
                                    <form id="signout-form" action="{{ url('/signout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-container">
            <div class="map-content">
                <div id="map" class="map"></div>
            </div>
            <div class="search-container">
                <form id="form-search-data" class="form-horizontal" role="form">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="{{trans('form.find_street_lighting')}}">                                
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="btn-group-sm pull-right">
                                <!--<button type="button" class="btn btn-default"><i class="fa fa-search fa-fw" aria-hidden="true"></i> {{trans('button.search')}}</button>-->
                                <button type="button" class="btn btn-danger" onclick="doPrintMap();"><i class="fa fa-print fa-fw" aria-hidden="true"></i> {{trans('button.print_map')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal fade bs-example-modal-sm" id="modal-loading" tabindex="-1" 
                role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">
                                <span class="glyphicon glyphicon-time">
                                </span> Please Wait
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="progress">
                                <div class="progress-bar progress-bar-info
                                progress-bar-striped active"
                                style="width: 100%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <footer class="text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; Softh Axi 2017
                    </div>
                </div>
            </div>
        </footer>
        
        <script>
            var map, currentInfoWindow;
            var markers = [];
            
            function showMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 5,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true
                });
                /**
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        infoWindow.setPosition(pos);
                        infoWindow.setContent('Location found.');
                        //infoWindow.open(map);
                        map.setCenter(pos);
                }, function() {
                        handleLocationError(true, infoWindow, map.getCenter());
                    });
                } else {
                    handleLocationError(false, infoWindow, map.getCenter());
                }
                */
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(browserHasGeolocation ?
                                    'Error: The Geolocation service failed.' :
                                    'Error: Your browser doesn\'t support geolocation.');
                //infoWindow.open(map);
            }
            
            function doPrintMap() {
                var $body = $('body');
                var $mapContainer = $('#map-container');
                var $mapContainerParent = $mapContainer.parent();
                var $printContainer = $('<div style="position:relative;">');

                $printContainer
                    .height($mapContainer.height())
                    .append($mapContainer)
                    .prependTo($body);

                var $content = $body
                    .children()
                    .not($printContainer)
                    .not('script')
                    .detach();

                var $patchedStyle = $('<style media="print">')
                    .text(
                    'img { max-width: none !important; }' +
                    'a[href]:after { content: ""; }'
                    )
                    .appendTo('head');

                window.print();

                $body.prepend($content);
                $mapContainerParent.prepend($mapContainer);

                $printContainer.remove();
                $patchedStyle.remove();
            }
            
            function showStreetLightingMarkers(id) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                markers = [];
                var data = new FormData();
                if(id != null) {
                    data.append('customer', id);
                }
                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/streetlighting/location')}}',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-loading').modal('hide');
                        var data = response.data;
                        var bounds = new google.maps.LatLngBounds();
                        data.forEach(function(item) {
                            var point = new google.maps.LatLng(
                                parseFloat(item.latitude),    
                                parseFloat(item.longitude));
                            var marker = new google.maps.Marker({
                                map: map,
                                position: point
                            });
                            var content = '<h5>' + item.customer_name + '</h5>'
                                        + '<p>Latitude : <strong>' + item.latitude + '</strong></p>'
                                        + '<p>Longitude : <strong>' + item.longitude + '</strong></p>'
                                        + '<p>Number of Lamp : <strong>' + item.number_of_lamp + '</strong></p>'
                                        + '<a href="{{url('/streetlighting')}}/' + item.customer_id +'">More Info</a>';
                            var infoWindow = new google.maps.InfoWindow();
                            google.maps.event.addListener(marker,'click', (function(marker,content,infoWindow){ 
                                return function() {
                                    if(currentInfoWindow) currentInfoWindow.close();
                                    infoWindow.setContent(content);
                                    infoWindow.open(map,marker);
                                    currentInfoWindow = infoWindow;
                                };
                            })(marker,content,infoWindow)); 
                            markers.push(marker);
                            bounds.extend(point);
                        });
                        map.fitBounds(bounds);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                    }
                });
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js'></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFIGWd-ZciDro507btwASHQi-sasDBvBo&callback=showMap" async defer></script>
        <script type="text/javascript">
            var registeredCustomerEngine = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.name);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: '{{url('/json/streetlighting/search')}}/',
                    filter: function (result) {
                        return $.map(result.data, function (index) {
                            return {
                                id: index.id,
                                code: index.code,
                                name: index.name,
                                link: '{{url('/streetlighting/')}}/' + index.id
                            };
                        });
                    },
                    replace: function (url, query) {
                        return url + '?query=' + query;
                    }
                }
            });
            var unregisteredCustomerEngine = new Bloodhound({
                datumTokenizer: function (datum) {
                    return Bloodhound.tokenizers.whitespace(datum.name);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: '{{url('/json/streetlighting/unregistered/search')}}/',
                    filter: function (result) {
                        return $.map(result.data, function (index) {
                            return {
                                id: index.id,
                                name: index.name,
                                link: '{{url('/streetlighting/')}}/' + index.id
                            };
                        });
                    },
                    replace: function (url, query) {
                        return url + '?query=' + query;
                    }
                }
            });
            $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                registeredCustomerEngine.initialize();
                unregisteredCustomerEngine.initialize();
                $('#keyword').typeahead({
                    hint: true,
                    highlight: true,
                    minLenght: 1
                }, {
                    name: 'registeredCustome',
                    display: 'name',
                    source: registeredCustomerEngine.ttAdapter(),
                    templates: {
                        //header: '<h4 style="padding-left:5px">{{trans('menu.registered_street_lighting')}}</h4>',
                        /*
                        empty: [
                            '<div class="noitems">',
                            'No Result Found',
                            '</div>'
                        ].join('\n'),
                        suggestion: function (data) {
                            return '<p>' + data.name  + '</p>';
                        }
                        */
                    }
                }, {
                    name: 'unregisteredCustome',
                    display: 'name',
                    source: unregisteredCustomerEngine.ttAdapter(),
                    templates: {
                        //header: '<h4 style="padding-left:5px">{{trans('menu.registered_street_lighting')}}</h4>',
                        /*
                        empty: [
                            '<div class="noitems">',
                            'No Result Found',
                            '</div>'
                        ].join('\n'),
                        suggestion: function (data) {
                            return '<p>' + data.name  + '</p>';
                        }
                        */
                    }
                }).bind('typeahead:select', function (event, suggestion) {
                    //suggestion.id;
                    showStreetLightingMarkers(suggestion.id);
                });
                showStreetLightingMarkers(null)
            });
        </script>
    </body>
</html>
