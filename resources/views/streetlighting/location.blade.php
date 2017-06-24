<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('heading.street_lighting_detail')}} · {{trans('form.location')}} · {{trans('app.name')}}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
        <style>
            html { 
                height: 100%
            }
            body {
                height: 100%;
                width: 100%;
                margin: 0px 0px 50px 0px;
                padding: 55px 0px 50px 0px;
            }
            .navbar-container {
                padding: 0px 10px 0px 0px;
            }
            .main-container {
                margin-bottom: 50px;
            }
            footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                z-index: 999;
                padding: 10px 0;
                box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.37);
                background-color: #ffffff;
            }
        </style>
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
                        <li><a href="{{url('home')}}">{{trans('menu.home')}}</a></li>
                        <li><a href="{{url('user')}}">{{trans('menu.user')}}</a></li>
                        <li class="dropdown active">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{trans('menu.street_lighting')}}  <span class="caret"></span>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="clearfix">
                                        <a href="{{url('streetlighting')}}">{{trans('menu.registered_street_lighting')}}</a></li>
                                    </li>
                                    <li class="clearfix">
                                        <a href="{{url('streetlighting/unregistered')}}">{{trans('menu.unregistered_street_lighting')}}</a></li>
                                    </li>
                                </ul>
                            </a>
                        </li>
                        <li><a href="{{url('survey')}}">{{trans('menu.survey')}}</a></li>
                        <li><a href="{{url('report')}}">{{trans('menu.report')}}</a></li>
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
                <h3>{{trans('heading.street_lighting_detail')}} - {{trans('form.location')}}</h3>
                <div class="col-md-12">
                <form id="form-search" class="form-horizontal">
                    <input type="hidden" name="id" value="{{$id}}"/>
                    <div class="row">
                        <div class="col-md-4">
                            <div id="photo" class="text-center">
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="code-group" class="form-group mb5">
                                <label class="col-md-4 control-label">{{trans('form.customer_number')}}</label>
                                <div class="col-md-4">
                                    <input id="code" name="code" type="text" class="form-control" placeholder="{{trans('form.customer_number')}}">
                                    <span class="help-block">
                                        <strong id="code-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="name-group" class="form-group mb5">
                                <label class="col-md-4 control-label">{{trans('form.customer_name')}}</label>
                                <div class="col-md-8">
                                    <input id="name" name="name" type="text" class="form-control" placeholder="{{trans('form.customer_name')}}">
                                    <span class="help-block">
                                        <strong id="name-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="survey-date-group" class="form-group mb5">
                                <label class="col-md-4 control-label">{{trans('form.survey_date')}}</label>
                                <div class="col-md-8">
                                    <input id="survey_date" name="survey_date" type="text" class="form-control" placeholder="{{trans('form.survey_date')}}">
                                    <span class="help-block">
                                        <strong id="survey-date-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="location-group" class="form-group mb5">
                                <label class="col-md-4 control-label">{{trans('form.location')}}</label>
                                <div class="col-md-4">
                                    <input id="latitude" name="latitude" type="number" class="form-control" placeholder="{{trans('form.latitude')}}">
                                    <span class="help-block">
                                        <strong id="latitude-help" class="help-text"></strong>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <input id="longitude" name="longitude" type="number" class="form-control" placeholder="{{trans('form.longitude')}}">
                                    <span class="help-block">
                                        <strong id="longitude-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="number-of-lamp-group" class="form-group mb5">
                                <label class="col-md-4 control-label">{{trans('form.number_of_lamp')}}</label>
                                <div class="col-md-2">
                                    <input id="number_of_lamp" name="number_of_lamp" type="number" class="form-control" placeholder="{{trans('form.number_of_lamp')}}">
                                    <span class="help-block">
                                        <strong id="number-of-lamp-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="btn-group-sm pull-right">
                        <button id="btn-edit" type="button" class="btn btn-warning" onclick="doRedirectEditStreetLighting('{{$id}}')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> {{trans('button.edit')}}</button>
                        <button id="btn-activate" type="button" class="btn btn-success" onclick="doChangeStatusStreetLightingForm('{{$id}}','activate')"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i> {{trans('button.activate')}}</button>
                        <button id="btn-deactivate" type="button" class="btn btn-danger" onclick="doChangeStatusStreetLightingForm('{{$id}}','deactivate')"><i class="fa fa-square-o fa-fw" aria-hidden="true"></i> {{trans('button.deactivate')}}</button>
                    </div>
                    -->
                </form>
                </div>

                <div class="col-md-12">
                    <h4>{{trans('heading.lamp_details')}}</h4>
                    <div class="table-responsive">
                        <table id="table-detail" class="table table-striped table-hover"
                            data-pagination="true"
                            data-pagination-loop="false">
                            <thead> 
                                <tr>
                                    <th class="col-md-2" data-formatter="imageFormatter">{{trans('form.picture')}}</th>
                                    <th class="col-md-2" data-field="code">{{trans('form.code')}}</th>
                                    <th class="col-md-2" data-field="type">{{trans('form.type')}}</th>
                                    <th class="col-md-1" data-field="power">{{trans('form.power')}}</th>
                                    <th data-field="remark">{{trans('form.description')}}</th>
                                </tr> 
                            </thead> 
                            <tbody></tbody> 
                        </table>
                    </div>
                </div>
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

            <div class="modal fade" id="modal-notification" tabindex="-1" 
                role="dialog" aria-labelledby="notification-label" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title">
                                <span class="glyphicon glyphicon-info-sign">
                                </span> Notification
                            </h4>
                        </div>
                        <div class="modal-body">
                            <p id="text-notification" class="text-message"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-success" data-dismiss="modal">OK</button>
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
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table-locale-all.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            
                initTables();
                loadDetailLocation();
            });

            function showNotification(message, status, callback, params) {
                $('#modal-notification').on('show.bs.modal', function(event) {
                    if(!status) {
                        $('#text-notification').addClass('text-primary');
                    } else {
                        $('#text-notification').addClass('text-danger');
                    }
                    $('#text-notification').html(message);
                });
                $('#modal-notification').on('hidden.bs.modal', function(event) {
                    if(!status) {
                        $('#text-notification').removeClass('text-primary');
                    } else {
                        $('#text-notification').removeClass('text-danger');
                    }
                    if(callback != null) {
                        if(params != null) {
                            callback(params);
                        } else {
                            callback();
                        }
                    }
                    $('#modal-notification').off('show.bs.modal');
                    $('#modal-notification').off('hidden.bs.modal');
                });
                $('#modal-notification').modal('show');
            }

            function initTables() {
                $('#table-detail').bootstrapTable({
                    locale: 'en_US',
                    classes: 'table table-striped table-hover table-borderless',
                    formatLoadingMessage: function () {
                        return '<span class="glyphicon glyphicon glyphicon-repeat glyphicon-animate"></span>';
                    }
                });
            }

            function loadDetailLocation() {
                $('#modal-loading').modal('show');
                $.ajax({
                    type: 'GET',
                    url: '{{url('/json/streetlighting/location/')}}/{{$id}}',
                    async: false,
                    beforeSend: function (xhr) {
                        if (xhr && xhr.overrideMimeType) {
                            xhr.overrideMimeType('application/json;charset=utf-8');
                        }
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('#modal-loading').modal('hide');
                        var data = response.data;
                        $('#code').val(data.customer_code);
                        $('#code').prop('disabled', true);
                        $('#name').val(data.customer_name);
                        $('#name').prop('disabled', true);
                        $('#survey_date').val(data.survey_date);
                        $('#survey_date').prop('disabled', true);
                        $('#latitude').val(data.latitude);
                        $('#latitude').prop('disabled', true);
                        $('#longitude').val(data.longitude);
                        $('#longitude').prop('disabled', true);
                        $('#number_of_lamp').val(data.number_of_lamp);
                        $('#number_of_lamp').prop('disabled', true);
                        
                        $('#table-detail').bootstrapTable('removeAll');
                        $('#table-detail').bootstrapTable('load',response.data.lamps);
                        
                        var photo = '<img src="http://placehold.it/200" class="avatar img-circle" alt="Avatar"/>';
                        
                        if(data.photo != null) {
                            photo = '<img id="" src="' + data.photo +'" class="avatar img-circle" alt="Avatar" width="200" height="200"/>';
                        }
                        $('#photo').html(photo);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                    }
                });
            }

            function imageFormatter(value, row, index) {
                return '<div class="text-center"><img src="http://placehold.it/80" class="avatar img-square" alt="Avatar"/></div>';
            }
        </script>
    </body>
</html>
