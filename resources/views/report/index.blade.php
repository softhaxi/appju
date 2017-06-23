<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('menu.report')}} · {{trans('form.search')}} · {{trans('app.name')}}</title>
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
                        <li><a href="{{url('survey')}}">{{trans('menu.survey')}}</a></li>
                        <li class="active"><a href="{{url('report')}}">{{trans('menu.report')}}</a></li>
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
                <h3>{{trans('menu.report')}}</h3>
                <div class="row">
                    <form id="form-search" class="form-horizontal" method="POST" action="{{url('/report/streetlightinglocation')}}">
                        {{ csrf_field() }}
                        <div id="report-group" class="form-group form-group-sm">
                            <label class="col-md-2 control-label">{{trans('form.report_name')}}</label>
                            <div class="col-md-3">
                                <select id="report" name="report" class="selectpicker form-control">
                                    <option value="{{url('/report/streetlightinglocation')}}">Street Lighting Location</option>
                                    <!--<option value="{{url('/report/surveyactivities')}}">Survey Activities</option>
                                    <option value="{{url('/report/surveyor')}}">Surveyor List</option>-->
                                </select>
                                <span class="help-block">
                                    <strong id="report-help" class="help-text"></strong>
                                </span>
                            </div>
                        </div>
                        <!--
                        <div id="street-lighting-group" class="form-group form-group-sm">
                            <label class="col-md-2 control-label">{{trans('form.street_lighting')}}</label>
                            <div class="col-md-3">
                                <select id="street_lighting" name="street_lighting" class="selectpicker form-control">
                                    <option value="all">All Street Lighting</option>
                                    <option value="registered">Registered Only</option>
                                    <option value="unregistered">Unregistered Only</option>
                                </select>
                                <span class="help-block">
                                    <strong id="street-lighting-help" class="help-text"></strong>
                                </span>
                            </div>
                        </div>
                        -->
                        <div id="customer-name-group" class="form-group form-group-sm">
                            <label class="col-md-2 control-label">{{trans('form.customer_name')}}</label>
                            <div class="col-md-4">
                                <input id="customer_name" name="customer_name" type="text" class="form-control" placeholder="{{trans('form.customer_name_or_leave_blank_for_all_customer')}}">
                                <span class="help-block">
                                    <strong id="customer-name-help" class="help-text"></strong>
                                </span>
                            </div>
                        </div>
                        <!--
                        <div id="surveyor-group" class="form-group form-group-sm">
                            <label class="col-md-2 control-label">{{trans('form.surveyor')}}</label>
                            <div class="col-md-4">
                                <input id="surveyor" name="surveyor" type="text" class="form-control" placeholder="{{trans('form.surveyor_ro_leave_blank_for_all_surveyor')}}">
                                <span class="help-block">
                                    <strong id="surveyor-help" class="help-text"></strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group form-group-sm">
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
                        <div id="status-group" class="form-group form-group-sm">
                            <label class="col-md-2 control-label">{{trans('form.status')}}</label>
                            <div class="col-md-3">
                                <select id="street_lighting" name="street_lighting" class="selectpicker form-control">
                                    <option value="all">All Status</option>
                                    <option value="0">Active Only</option>
                                    <option value="1">Inactive Only</option>
                                    <option value="2">Reset Only</option>
                                </select>
                                <span class="help-block">
                                    <strong id="status-help" class="help-text"></strong>
                                </span>
                            </div>
                        </div>
                        -->
                        <div id="format-group" class="form-group form-group-sm">
                            <label class="col-md-2 control-label">{{trans('form.format_type')}}</label>
                            <div class="col-md-2">
                                <select id="format" name="format" class="selectpicker form-control">
                                    <option value="pdf">PDF</option>
                                    <!--<option value="xls">Microsoft Excel</option>
                                    <option value="csv">CSV</option>-->
                                </select>
                                <span class="help-block">
                                    <strong id="rate-help" class="help-text"></strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <div class="btn-group-sm">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-download fa-fw" aria-hidden="true"></i> {{trans('form.download')}}</button>
                                    <button type="reset" class="btn btn-default"><i class="fa fa-refresh fa-fw" aria-hidden="true"></i> {{trans('form.reset')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
        <script src='https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js'></script>
        <script>
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
                    }, 
                    cache: false
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
                    }, 
                    cache: false
                }
            });
            
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                $('#report').on('changed.bs.select', function (e, clickedIndex, newValue, oldValue) {
                    $('#form-search').prop('action', $(this).val());
                });
                registeredCustomerEngine.initialize();
                unregisteredCustomerEngine.initialize();
                $('#customer_name').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 1,
                    limit: 10,
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
                });
            });
        </script>
    </body>
</html>
