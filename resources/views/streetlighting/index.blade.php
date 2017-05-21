<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('menu.street_lighting')}} · {{trans('app.name')}}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css"> 
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
                        <li><a href="{{url('home')}}">{{trans('menu.home')}}</a></li>
                        <li><a href="{{url('user')}}">{{trans('menu.user')}}</a></li>
                        <li class="active"><a href="{{url('streetlighting')}}">{{trans('menu.legal_street_lighting')}}</a></li>
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
                <h3>{{trans('menu.street_lighting')}}</h3>
                <div class="row">
                    <form id="form-search" class="form-horizontal">
                        <div class="form-group">
                            <label for="code" class="col-md-2 control-label">{{trans('form.customer_number')}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="code" id="code" placeholder="{{trans('form.customer_number')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-md-2 control-label">{{trans('form.name')}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="name" id="name" placeholder="{{trans('form.name')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <div class="btn-group-sm">
                                    <button type="button" class="btn btn-default" onclick="doSearchForm();"><i class="fa fa-search fa-fw" aria-hidden="true"></i> {{trans('form.search')}}</button>
                                    <button type="button" class="btn btn-success" onclick="showNewStreetLightingForm();"><i class="fa fa-plus fa-fw" aria-hidden="true"></i> {{trans('form.add')}}</button>
                                    <button type="button" class="btn btn-primary" onclick="showImportStreetLightingForm();"><i class="fa fa-upload fa-fw" aria-hidden="true"></i> {{trans('form.import')}}</button>
                                <!--<a href="{{url('/user/import')}}" class="btn btn-success" role="button">{{trans('form.import')}}</a>-->
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table id="table-master" class="table table-striped table-hover"
                        data-pagination="true"
                        data-pagination-loop="false">
                        <thead class="text-center"> 
                            <tr>
                                <th class="col-sm-2" data-field="code" data-sortable="true" data-formatter="codeFormatter">{{trans('form.customer_number')}}</th>
                                <th data-field="name" data-sortable="true">{{trans('form.name')}}</th>
                                <th data-field="full_address">{{trans('form.address')}}</th>
                                <th class="col-sm-1" data-field="power">{{trans('form.power')}}</th>
                                <th class="col-sm-1" data-field="status" data-formatter="statusFormatter">{{trans('form.status')}}</th>
                                <th class="col-sm-1" data-formatter="actionFormatter"></th>
                            </tr> 
                        </thead> 
                        <tbody></tbody> 
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal-street-lighting" tabindex="-1" role="dialog" aria-labelledby="street-lighting-label" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-lg" role="document">
                    <form id="form-street-lighting" class="form-horizontal" role="form">         
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="street-lighting-label">{{trans('form.create_street_lighting')}}</h4>
                        </div>
                        <div class="modal-body">
                            <div id="error-panel" class="alert alert-danger alert-dismissable"></div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="code-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.customer_number')}}</label>
                                        <div class="col-md-8">
                                            <input id="code" name="code" type="text" class="form-control" placeholder="{{trans('form.customer_number')}}">
                                            <span class="help-block">
                                                <strong id="code-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="name-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.name')}}</label>
                                        <div class="col-md-8">
                                            <input id="name" name="name" type="text" class="form-control" placeholder="{{trans('form.name')}}">
                                            <span class="help-block">
                                                <strong id="name-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="address-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.address')}}</label>
                                        <div class="col-md-8">
                                            <input id="address" name="address" type="text" class="form-control" placeholder="{{trans('form.address')}}">
                                            <span class="help-block">
                                                <strong id="address-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="address2-group" class="form-group mb5">
                                        <div class="col-md-offset-4 col-md-8">
                                            <input id="address2" name="address2" type="text" class="form-control">
                                            <span class="help-block">
                                                <strong id="address2-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="address3-group" class="form-group mb5">
                                        <div class="col-md-offset-4 col-md-8">
                                            <input id="address3" name="address3" type="text" class="form-control">
                                            <span class="help-block">
                                                <strong id="address3-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="rate-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.rate')}}</label>
                                        <div class="col-md-4">
                                            <select id="rate" name="rate" class="selectpicker form-control">
                                                <option value="P1">P1</option>
                                                <option value="P2">P2</option>
                                                <option value="P3">P3</option>
                                            </select>
                                            <span class="help-block">
                                                <strong id="rate-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="power-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.power')}}</label>
                                        <div class="col-md-4">
                                            <input id="power" name="power" type="number" class="form-control" placeholder="{{trans('form.power')}}">
                                            <span class="help-block">
                                                <strong id="power-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="stand-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.stand')}}</label>
                                        <div class="col-md-4">
                                            <input id="stand_start" name="stand_start" type="number" class="form-control" placeholder="{{trans('form.start')}}">
                                            <span class="help-block">
                                                <strong id="stand-start-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                        <div class="col-md-4">
                                            <input id="stand_end" name="stand_end" type="number" class="form-control" placeholder="{{trans('form.end')}}">
                                            <span class="help-block">
                                                <strong id="stand-end-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="kwh-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.kwh')}}</label>
                                        <div class="col-md-4">
                                            <input id="kwh" name="kwh" type="number" class="form-control" placeholder="{{trans('form.kwh')}}">
                                            <span class="help-block">
                                                <strong id="kwh-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="ptl-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.ptl')}}</label>
                                        <div class="col-md-4">
                                            <input id="ptl" name="ptl" type="number" class="form-control" placeholder="{{trans('form.ptl')}}">
                                            <span class="help-block">
                                                <strong id="ptl-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="stamp-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.stamp')}}</label>
                                        <div class="col-md-4">
                                            <input id="stamp" name="stamp" type="number" class="form-control" placeholder="{{trans('form.stamp')}}">
                                            <span class="help-block">
                                                <strong id="stamp-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="bank-fee-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.bank_fee')}}</label>
                                        <div class="col-md-4">
                                            <input id="bank_fee" name="bank_fee" type="number" class="form-control" placeholder="{{trans('form.bank_fee')}}">
                                            <span class="help-block">
                                                <strong id="bank-fee-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="ppn-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.ppn')}}</label>
                                        <div class="col-md-4">
                                            <input id="ppn" name="ppn" type="number" class="form-control" placeholder="{{trans('form.ppn')}}">
                                            <span class="help-block">
                                                <strong id="ppn-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="monthly-bill-group" class="form-group mb5">
                                        <label class="col-md-4 control-label">{{trans('form.bill')}}</label>
                                        <div class="col-md-4">
                                            <input id="monthly_bill" name="monthly_bill" type="number" class="form-control" placeholder="{{trans('form.bill')}}">
                                            <span class="help-block">
                                                <strong id="monthly-bill-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div id="status-group" class="form-group mb5">
                                        <div class="col-md-offset-4 col-md-6">
                                            <input type="checkbox" name="status" checked disabled> {{trans('form.active')}}
                                            <span class="help-block">
                                                <strong id="status-help" class="help-text"></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group-sm">
                                <button id="btn-submit" type="submit" class="btn btn-success"><i class="fa fa-save fa-fw" aria-hidden="true"></i> {{trans('button.save')}}</button>
                                <button id="btn-activate" type="button" class="btn btn-success"><i class="fa fa-check-sequare-o fa-fw" aria-hidden="true"></i> {{trans('button.activate')}}</button>
                                <button id="btn-deactivate" type="button" class="btn btn-danger"><i class="fa fa-sequare-o fa-fw" aria-hidden="true"></i> {{trans('button.deactivate')}}</button>
                                <button id="btn-cancel" type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove fa-fw" aria-hidden="true"></i> {{trans('button.cancel')}}</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="import-label" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <form id="form-import" class="form-horizontal" role="form" enctype="multipart/form-data">         
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="import-label">{{trans('form.import_file')}}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="import-file-group" class="form-group">
                                    <label class="col-md-4 control-label">{{trans('form.imported_file_xls_csv')}}</label>
                                    <div class="col-md-8">
                                        <input id="import-file" name="file" type="file" accept=".csv,.xls">
                                        <span class="help-block">
                                            <strong id="import-file-help" class="help-text"></strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="btn-group-sm">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload fa-fw" aria-hidden="true"></i> {{trans('form.import')}}</button>
                                    <button id="btn-cancel" type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove fa-fw" aria-hidden="true"></i> {{trans('button.cancel')}}</button>
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
            
            <div class="modal fade" id="modal-confirmation" tabindex="-1" 
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
                            <p id="text-confirmation" class="text-message"></p>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group-sm">
                                <button id="btn-ok" type="button" class="btn btn-success"><i class="fa fa-check fa-fw" aria-hidden="true"></i> OK</button>
                                <button id="btn-cancel" type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-remove fa-fw" aria-hidden="true"></i> {{trans('button.cancel')}}</button>
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
                data.append('code', $('input[name=code]').val());
                data.append('name', $('input[name=name]').val());

                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/streetlighting/search')}}',
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

            function showNewStreetLightingForm() {
                $('#modal-street-lighting').on('show.bs.modal', function(event) {
                    $('#error-panel').html('');
                    $('#error-panel').hide();
                    $("#street-lighting .form-group").each(function() {
                        $(this).removeClass('has-error');
                    });
                    $('#status-group').hide();
                    $('#btn-activate').hide();
                    $('#btn-deactivate').hide();
                    $('#form-street-lighting').on('submit', function(event) {
                        event.preventDefault();
                        $('#modal-loading').modal('show');

                        $('#error-panel').html('');
                        $('#error-panel').hide();
                        $("#form-user .form-group").each(function() {
                            $(this).removeClass('has-error');
                        });
                        $.ajax({
                            url: '{{url('/json/streetlighting')}}',
                            type: 'POST',
                            data: new FormData(this),
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                $('#modal-loading').modal('hide');
                                $('#modal-street-lighting').modal('hide');
                                showNotification(response.message, false, doRedirectDetailStreetLighting, response);
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                $('#modal-loading').modal('hide');
                                var json = JSON.parse(xhr.responseText);
                                var errors = '<ul>';
                                if(json.errors['code']== null) {
                                    $('#code-group').removeClass('has-error');
                                } else {
                                    $('#code-group').addClass('has-error');
                                    errors += '<li>' + json.errors['code'] + '</li>';
                                }

                                if(json.errors['name']== null) {
                                    $('#name-group').removeClass('has-error');
                                } else {
                                    $('#name-group').addClass('has-error');
                                    errors += '<li>' + json.errors['name'] + '</li>';
                                }

                                if(json.errors['addres']== null) {
                                    $('#addres-group').removeClass('has-error');
                                } else {
                                    $('#addres-group').addClass('has-error');
                                    errors += '<li>' + json.errors['addres'] + '</li>';
                                }

                                errors += '</ul>';
                                $('#error-panel').show();
                                $('#error-panel').html(errors);
                            }
                        });
                    });
                });
                $('#modal-street-lighting').on('hide.bs.modal', function(event) {
                    $('#form-street-lighting')[0].reset();
                });
                $('#modal-street-lighting').on('hidden.bs.modal', function(event) {
                    $('#status-group').show();
                    $('#btn-activate').show();
                    $('#btn-deactivate').show();
                    $('#form-street-lighting').off('submit');
                    $('#modal-street-lighting').off('show.bs.modal');
                    $('#modal-street-lighting').off('hide.bs.modal');
                    $('#modal-street-lighting').off('hidden.bs.modal');
                });
                $('#modal-street-lighting').modal('show');
            }

            function showImportStreetLightingForm() {
                $('#modal-import').on('show.bs.modal', function(event) {
                    $('#form-import').on('submit', function(event) {
                        event.preventDefault();
                        $('#modal-loading').modal('show');
                        $.ajax({
                            url: '{{url('/json/streetlighting/import')}}',
                            type: 'POST',
                            data: new FormData(this),
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                $('#modal-loading').modal('hide');
                                $('#modal-import').modal('hide');
                                showNotification(response.message, false, doSearchForm, null);
                            },
                            error: function (xhr, ajaxOptions, thrownError) {
                                $('#modal-loading').modal('hide');
                                var json = JSON.parse(xhr.responseText);
                            }
                        });
                    });
                });
                $('#modal-import').on('hide.bs.modal', function(event) {
                    $('#form-import')[0].reset();
                });
                $('#modal-import').on('hidden.bs.modal', function(event) {
                    $('#form-import').off('submit');
                    $('#modal-import').off('show.bs.modal');
                    $('#modal-import').off('hide.bs.modal');
                    $('#modal-import').off('hidden.bs.modal');
                });
                $('#modal-import').modal('show');
            }

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

            function showStreetLightingDeleteConfirmation(id) {
                $.ajax({
                    type: 'GET',
                    url: '{{url('/json/streetlighting/')}}/' + id,
                    async: false,
                    beforeSend: function (xhr) {
                        if (xhr && xhr.overrideMimeType) {
                            xhr.overrideMimeType('application/json;charset=utf-8');
                        }
                    },
                    dataType: 'json',
                    success: function (response) {
                        var message = '';
                        var data = response.data;
                        message = 'Are you sure for delete street lighting <strong>' + data.code + ' - ' + data.name + "</strong> ?";
                        $('#modal-confirmation').on('hidden.bs.modal', function(event) {
                            $('#button-delete-ok').off("click");
                        });
                        $('#modal-confirmation').modal('show');
                        $('#text-confirmation').html(message);
                        $('#text-confirmation').addClass('text-danger');
                        $('#btn-ok').on('click', function(event) {
                            doDeleteStreetLightingForm(data.id);
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                    }
                });
            }

            function doDeleteStreetLightingForm(id) {
                var data = new FormData();
                data.append('id', id);
                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/streetlighting/delete')}}',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#modal-loading').modal('hide');
                        $('#modal-confirmation').modal('hide');
                        $('#btn-ok').off("click");
                        showNotification(response.message, false, doSearchForm, null);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                    }
                });
            }

            function doRedirectDetailStreetLighting(params) {
                if(params.code == 202) {
                    window.location.href = "{{url('/streetlighting/')}}/" + params.data.id;
                }
            }

            function doRedirectEditStreetLighting(id) {
                window.location.href = "{{url('/streetlighting/edit')}}/" + id;
            }

            function codeFormatter(value, row, index) {
                return '<div class="text-center"><a href="{{url('/streetlighting')}}/' +row.id+'">'+value+'</a></div>';
            }

            function statusFormatter(value, row, index) {
                if(row.status == 1) {
                    return '<p class="text-primary text-center">{{trans('form.active')}}</p>';
                } else {
                    return '<p class="text-danger text-center">{{trans('form.inactive')}}</p>';
                }
            }

            function actionFormatter(value, row, index) {
                var buttons = '<div class="btn-group-xs text-center">';
                buttons += ' <button class="btn btn-warning" onclick="doRedirectEditStreetLighting(' + 
                        "'" + row.id + "'" + ')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></button>';
                buttons += ' <button class="btn btn-danger" onclick="showStreetLightingDeleteConfirmation(' + 
                        "'" + row.id + "'" + ')"><span class="glyphicon glyphicon-trash"></span></button>';
                buttons += '</div>';
                return buttons;
            }
        </script>
    </body>
</html>
