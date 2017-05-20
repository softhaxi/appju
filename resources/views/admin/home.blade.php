<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="0">
        <title>{{trans('app.name')}} · {{trans('app.full_name')}}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
        <style>
            html { 
                height: 100%
            }

            body {
                height: 100%;
                width: 100%;
                margin: 0;
                padding: 55px 0px 0px 0px;
            }
            .main-container {
                margin-bottom: 50px;
            }

            .map {
                height: 450px;
            }

            footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 999;
                padding: 5px 0;
                box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.37);
                background-color: #ffffff;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container">
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
                        <li><a href="{{url('streetlighting')}}">{{trans('menu.legal_street_lighting')}}</a></li>
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
            <div class="container">
                <h3>{{trans('heading.survey_result_on_map')}}</h3>
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <form id="form-search-data" class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-md-4 control-label">{{trans('form.customer_number')}}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="customer_number" name="customer_number" placeholder="{{trans('form.customer_number')}}">                                
                                </div>
                            </div>
                            <div id="type-group" class="form-group mb5">
                                <label class="col-md-4 control-label">{{trans('form.street_lighting')}}</label>
                                <div class="col-md-6">
                                    <select id="add-class" name="type" class="selectpicker form-control">
                                        <option value="-1">{{trans('form.all_lighting')}}</option>
                                        <option value="0">{{trans('form.legal_lighting')}}</option>
                                        <option value="1">{{trans('form.illegal_lighting')}}</option>
                                    </select>
                                    <span class="help-block">
                                        <strong id="type-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="btn-group-sm pull-right">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search fa-fw" aria-hidden="true"></i> {{trans('button.search')}}</button>
                                        <button type="button" class="btn btn-danger" onclick="doPrintMap();"><i class="fa fa-print fa-fw" aria-hidden="true"></i> {{trans('button.print_map')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-8">
                        <div id="map" class="map"></div>
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
            var map, infoWindow;

            function showMap() {
                map = new google.maps.Map(document.getElementById('map'), {
                    center: {lat: -34.397, lng: 150.644},
                    zoom: 6
                });
                infoWindow = new google.maps.InfoWindow;

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
            }

            function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                infoWindow.setPosition(pos);
                infoWindow.setContent(browserHasGeolocation ?
                                    'Error: The Geolocation service failed.' :
                                    'Error: Your browser doesn\'t support geolocation.');
                //infoWindow.open(map);
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAFIGWd-ZciDro507btwASHQi-sasDBvBo&callback=showMap" async defer></script>
        <script type="text/javascript">
            function doPrintMap() {
                var $body = $('body');
                var $mapContainer = $('#map');
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
        </script>
    </body>
</html>
