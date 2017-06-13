<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('menu.survey')}} · {{trans('app.name')}}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css"> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/css/bootstrap-datetimepicker.min.css">
        <style>
            html, body, .map { 
                height: 100%
            }
            body {
                width: 100%;
                margin: 0;
                padding: 55px 0px 0px 0px;
            }
            .navbar-container {
                padding: 0px 10px 0px 0px;
            }
            .main-container {
                height: 100%;
                left: 0px;
                right: 0px;
                top: 50px;
                bottom: 30px;
            }
            .content-container {
                top: 50px;
                bottom: 30px;
                overflow: auto;
                position: absolute;
                left: 0px;
                right: 0px;
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
                        <li class="dropdown">
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
                        <li class="active"><a href="{{url('survey')}}">{{trans('menu.survey')}}</a></li>
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
                <h3>{{trans('menu.survey')}}</h3>
                <div class="row">
                    <form id="form-search" class="form-horizontal">
                        <input type="hidden" name="status" value="1"/>
                        <div class="form-group">
                            <label for="date" class="col-md-2 control-label">{{trans('form.date_filter')}}</label>
                            <div class="col-md-3">
                                <div id="from-date" class='input-group date' >
                                    <input name="from_date" type='text' class="form-control" placeholder="{{trans('form.from_date')}}"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div id="to-date" class='input-group date' >
                                    <input name="to_date" type='text' class="form-control" placeholder="{{trans('form.to_date')}}"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <div class="btn-group-sm">
                                    <button type="button" class="btn btn-default" onclick="doSearchForm();"><i class="fa fa-search fa-fw" aria-hidden="true"></i> {{trans('form.search')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="table-master" class="table table-striped table-hover"
                        data-pagination="true"
                        data-pagination-loop="false">
                        <thead> 
                            <tr>
                                <th class="col-md-2" data-field="date_time" data-sortable="true" data-formatter="dateTimeFormatter">{{trans('form.date_time')}}</th>
                                <th class="col-md-1" data-field="action">{{trans('form.action')}}</th>
                                <th data-formatter="customerFormatter">{{trans('form.customer')}}</th>
                                <th class="col-md-1" data-field="created_by">{{trans('form.created_by')}}</th>
                                <!--<th class="col-md-1" data-field="status" data-formatter="statusFormatter">{{trans('form.status')}}</th>-->
                                <th class="col-md-1" data-formatter="actionFormatter"></th>
                            </tr> 
                        </thead> 
                        <tbody></tbody> 
                    </table>
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
                                </span> Confirmation
                            </h4>
                        </div>
                        <div class="modal-body">
                            <p id="text-message" class="text-message"></p>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.4/numeral.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#from-date').datetimepicker({
                    format: 'DD-MM-YYYY',
                    useCurrent: true
                });
                $('#to-date').datetimepicker({
                    format: 'DD-MM-YYYY',
                    useCurrent: true
                });
                initTables();
                doSearchForm();
            });

            function initTables() {
                $('#table-master').bootstrapTable({
                    locale: 'en_US',
                    classes: 'table table-striped table-hover table-borderless',
                    formatLoadingMessage: function () {
                        return '<span class="glyphicon glyphicon glyphicon-repeat glyphicon-animate"></span>';
                    }
                }); 
            }

            function doSearchForm() {
                var data = new FormData();
                data.append('status', $('input[name=status]').val());
                data.append('from_date', $('input[name=from_date]').val());
                data.append('to_date', $('input[name=to_date]').val());

                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/survey/search')}}',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#modal-loading').modal('hide');
                        
                        $('#table-master').bootstrapTable('removeAll');
                        $('#table-master').bootstrapTable('load',response.data);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                    }
                });
            }
            
            function doApproveSurveyConfirmation(id) {
                
            }
            
            function dateTimeFormatter(value, row, index) {
                return '<a href="' + row.url + '">' + value + '</a>';
            }
            
            function customerFormatter(value, row, index) {
                if(row.customer_code != null) {
                    return row.customer_code + ' - ' + row.customer_name;
                } else {
                    return row.customer_name;
                }
            }
            
            function statusFormatter(value, row, index) {
                if(row.status == 0) {
                    return '<p class="text-primary text-center">{{trans('form.new')}}</p>';
                } else if(row.status == 2){
                    return '<p class="text-danger text-center">{{trans('form.reject')}}</p>';
                }
            }
            
            function actionFormatter(value, row, index) {
                var buttons = '<div class="btn-group-xs text-center">';
                /*
                if(row.status == 0) {
                    buttons += ' <button class="btn btn-primary" onclick="doApproveSurveyConfirmation(' + 
                        "'" + row.id + "'" + ')"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i></button>';
                } else if(row.status == 2) {
                    buttons += ' <button class="btn btn-warning" onclick="doSurveyReapproveConfirmation(' + 
                        "'" + row.id + "'" + ')"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i></button>';
                }
                */
                buttons += ' <button class="btn btn-danger" onclick="showSurveyDeleteConfirmation(' + 
                        "'" + row.id + "'" + ')"><span class="glyphicon glyphicon-trash"></span></button>';
                buttons += '</div>';
                return buttons;
            }
        </script>
    </body>
</html>
