<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('heading.street_lighting_detail')}} · {{trans('app.name')}}</title>
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
                <h3>{{trans('heading.street_lighting_detail')}}</h3>
                <div class="col-md-12">
                <form id="form-search" class="form-horizontal">
                    <input type="hidden" name="id" value="{{$id}}"/>
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
                        </div>
                        <div class="col-md-6">
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
                                    <input type="checkbox" id="status-checkbox" name="status-checkbox"> {{trans('form.active')}}
                                    <span class="help-block">
                                        <strong id="status-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-group-sm pull-right">
                        <button id="btn-edit" type="button" class="btn btn-warning" onclick="doRedirectEditStreetLighting('{{$id}}')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> {{trans('button.edit')}}</button>
                        <button id="btn-activate" type="button" class="btn btn-success" onclick="doChangeStatusStreetLightingForm('{{$id}}','activate')"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i> {{trans('button.activate')}}</button>
                        <button id="btn-deactivate" type="button" class="btn btn-danger" onclick="doChangeStatusStreetLightingForm('{{$id}}','deactivate')"><i class="fa fa-square-o fa-fw" aria-hidden="true"></i> {{trans('button.deactivate')}}</button>
                    </div>
                </form>
                </div>

                
                <div class="col-md-12">
                    <h4>{{trans('heading.street_lighting_location')}}</h4>
                    <div class="table-responsive">
                        <table id="table-detail" class="table table-striped table-hover"
                            data-pagination="true"
                            data-pagination-loop="false">
                            <thead> 
                                <tr>
                                    <th class="col-md-2" data-formatter="coordinatFormatter">{{trans('form.coordinat')}}</th>
                                    <th data-field='number-of_lamp'>{{trans('form.number_of_lamp')}}</th>
                                    <th data="survey_date" data-formatter="dateTimeFormatter">{{trans('form.survey_date')}}</th>
                                    <th class="col-md-1" data-field="status" data-formatter="statusFormatter">{{trans('form.status')}}</th>
                                    <th class="col-md-1" data-formatter="actionFormatter"></th>
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
                loadDetailUser();
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

            function loadDetailUser() {
                $('#modal-loading').modal('show');
                $.ajax({
                    type: 'GET',
                    url: '{{url('/json/streetlighting/')}}/{{$id}}',
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
                        $('#code').val(data.code);
                        $('#code').prop('disabled', true);
                        $('#name').val(data.name);
                        $('#name').prop('disabled', true);
                        $('#address').val(data.address);
                        $('#address').prop('disabled', true);
                        $('#address2').val(data.address2);
                        $('#address2').prop('disabled', true);
                        $('#address3').val(data.address3);
                        $('#address3').prop('disabled', true);
                        $('#rate').selectpicker('val', data.rate);
                        $('#rate').prop('disabled', true);
                        $('#power').val(data.power);
                        $('#power').prop('disabled', true);
                        $('#stand_start').val(data.stand_start);
                        $('#stand_start').prop('disabled', true);
                        $('#stand_end').val(data.stand_end);
                        $('#stand_end').prop('disabled', true);
                        $('#kwh').val(data.kwh);
                        $('#kwh').prop('disabled', true);
                        $('#ptl').val(data.ptl);
                        $('#ptl').prop('disabled', true);
                        $('#stamp').val(data.stamp);
                        $('#stamp').prop('disabled', true);
                        $('#bank_fee').val(data.bank_fee);
                        $('#bank_fee').prop('disabled', true);
                        $('#ppn').val(data.ppn);
                        $('#ppn').prop('disabled', true);
                        $('#monthly_bill').val(data.monthly_bill);
                        $('#monthly_bill').prop('disabled', true);
                        if(data.status == 1) {
                            $('#status-checkbox').prop('checked', true); 
                            $('#btn-activate').hide();
                        } else {
                            $('#status-checkbox').prop('checked', false);
                            $('#btn-deactivate').hide();
                        }
                        $('#status-checkbox').prop('disabled', true);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                    }
                });
            }

            function doRedirectDetailUser(params) {
                if(params.code == 202) {
                    window.location.href = "{{url('/streetlighting/')}}/" + params.data.id;
                }
            }

            function doRedirectEditStreetLighting(id) {
                window.location.href = "{{url('/streetlighting/edit')}}/" + id;
            }

            function doChangeStatusStreetLightingForm(id, action) {
                var data = new FormData();
                data.append('id', id);
                data.append('action', action);
                $.ajax({
                    url: '{{url('/json/streetlighting/status')}}',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-loading').modal('hide');
                        showNotification(response.message, false, doRedirectDetailUser, response);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                        showNotification(json.errors, true, null, null);
                    }
                });
            }
        </script>
    </body>
</html>
