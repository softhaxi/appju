<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('menu.user_profile')}} · {{trans('button.edit')}} · {{trans('app.name')}}</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css"> 
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
                    <li class="active"><a href="{{url('user')}}">{{trans('menu.user')}}</a></li>
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
                <h3>{{trans('menu.user_profile')}}</h3>
                <form id="form-user" class="form-horizontal" role="form">    
                    <div class="row">
                        <div class="col-md-offset-2 col-md-7">
                            <div id="error-panel" class="alert alert-danger alert-dismissable"></div>
                            <input type="hidden" name="id" value="{{$id}}"/>
                            <div id="username-group" class="form-group input-sm mb5">
                                <label class="col-md-3 control-label">{{trans('form.username')}}</label>
                                <div class="col-md-5">
                                    <input id="username" name="username" type="text" class="form-control" placeholder="{{trans('form.username')}}">
                                    <span class="help-block">
                                        <strong id="username-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="email-group" class="form-group input-sm mb5">
                                <label class="col-md-3 control-label">{{trans('form.email')}}</label>
                                <div class="col-md-5">
                                    <input id="email" name="email" type="email" class="form-control" placeholder="{{trans('form.email')}}">
                                    <span class="help-block">
                                        <strong id="email-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="full-name-group" class="form-group input-sm mb5">
                                <label class="col-md-3 control-label">{{trans('form.full_name')}}</label>
                                <div class="col-md-3">
                                    <input id="first_name" name="first_name" type="text" class="form-control" placeholder="{{trans('form.first_name')}}">
                                    <span class="help-block">
                                        <strong id="first-name-help" class="help-text"></strong>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <input id="middle_name" name="middle_name" type="text" class="form-control" placeholder="{{trans('form.middle_name')}}">
                                    <span class="help-block">
                                        <strong id="middle-name-help" class="help-text"></strong>
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <input id="last_name" name="last_name" type="text" class="form-control" placeholder="{{trans('form.last_name')}}">
                                    <span class="help-block">
                                        <strong id="last-name-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="device-code-group" class="form-group input-sm mb5">
                                <label class="col-md-3 control-label">{{trans('form.device_code')}}</label>
                                <div class="col-md-4">
                                    <input id="device_code" name="device_code" type="text" class="form-control" placeholder="{{trans('form.device_code')}}">
                                    <span class="help-block">
                                        <strong id="device-code-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="mobile-group" class="form-group input-sm mb5">
                                <label class="col-md-3 control-label">{{trans('form.mobile')}}</label>
                                <div class="col-md-4">
                                    <input id="mobile" name="mobile" type="numeric" class="form-control" placeholder="{{trans('form.mobile')}}">
                                    <span class="help-block">
                                        <strong id="mobile-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="status-group" class="form-group input-sm mb5">
                                <div class="col-md-offset-3 col-md-6">
                                    <input type="checkbox" id="status-checkbox"> {{trans('form.active')}}
                                    <input type="hidden" id="status" name="status"/>
                                    <span class="help-block">
                                        <strong id="status-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="status-group" class="form-group mb5">
                                <div class="col-md-offset-3 col-md-6">
                                    <div class="btn-group-sm">
                                        <button id="btn-submit" type="submit" class="btn btn-success"><i class="fa fa-save fa-fw" aria-hidden="true"></i> {{trans('button.save')}}</button>
                                        <button id="btn-cancel" type="button" class="btn btn-danger" onclick="doRedirectUserList();"><i class="fa fa-remove fa-fw" aria-hidden="true"></i> {{trans('button.cancel')}}</button>
                                    </div>
                                </div>
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

        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                loadDetailUser();

                $('#error-panel').html('');
                $('#error-panel').hide();
                $('#form-user').on('submit', function(event) {
                    event.preventDefault();
                    $('#modal-loading').modal('show');

                    $('#error-panel').html('');
                    $('#error-panel').hide();
                    $.ajax({
                        url: '{{url('/json/user/update')}}',
                        type: 'POST',
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            $('#modal-loading').modal('hide');
                            showNotification(response.message, false, doRedirectDetailUser, response);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            $('#modal-loading').modal('hide');
                            var json = JSON.parse(xhr.responseText);
                            var errors = '<ul>';
                            if(json.errors['username']== null) {
                                $('#username-group').removeClass('has-error');
                            } else {
                                $('#username-group').addClass('has-error');
                                errors += '<li>' + json.errors['username'] + '</li>';
                            }

                            if(json.errors['email']== null) {
                                $('#email-group').removeClass('has-error');
                            } else {
                                $('#email-group').addClass('has-error');
                                errors += '<li>' + json.errors['email'] + '</li>';
                            }

                            if(json.errors['first_name']== null) {
                                $('#full-name-group').removeClass('has-error');
                            } else {
                                $('#full-name-group').addClass('has-error');
                                errors += '<li>' + json.errors['first_name'] + '</li>';
                            }

                            if(json.errors['last_name']== null) {
                                $('#full-name-group').removeClass('has-error');
                            } else {
                                $('#full-name-group').addClass('has-error');
                                errors += '<li>' + json.errors['last_name'] + '</li>';
                            }

                            if(json.errors['device_code']== null) {
                                $('#device-code-group').removeClass('has-error');
                            } else {
                                $('#device-code-group').addClass('has-error');
                                errors += '<li>' + json.errors['device_code'] + '</li>';
                            }

                            if(json.errors['mobile']== null) {
                                $('#mobile-group').removeClass('has-error');
                            } else {
                                $('#mobile-group').addClass('has-error');
                                errors += '<li>' + json.errors['mobile'] + '</li>';
                            }

                            errors += '</ul>';
                            $('#error-panel').show();
                            $('#error-panel').html(errors);
                        }
                    });
                });
            });

            function showNotification(message, status, callback, params) {
                $('#modal-notification').on('show.bs.modal', function(event) {
                    if(!status) {
                        $('#text-message').addClass('text-primary');
                    } else {
                        $('#text-message').addClass('text-danger');
                    }
                    $('#text-message').html(message);
                });
                $('#modal-notification').on('hidden.bs.modal', function(event) {
                    if(!status) {
                        $('#text-message').removeClass('text-primary');
                    } else {
                        $('#text-message').removeClass('text-danger');
                    }
                    if(params != null) {
                        callback(params);
                    } else {
                        callback();
                    }
                    $('#modal-notification').off('show.bs.modal');
                    $('#modal-notification').off('hidden.bs.modal');
                });
                $('#modal-notification').modal('show');
            }

            function loadDetailUser() {
                $('#modal-loading').modal('show');
                $.ajax({
                    type: 'GET',
                    url: '{{url('/json/user/')}}/{{$id}}',
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
                        $('#username').val(data.username);
                        $('#username').prop('readonly', true);
                        $('#email').val(data.email);
                        $('#first_name').val(data.first_name);
                        $('#middle_name').val(data.middle_name);
                        $('#last_name').val(data.last_name);
                        $('#device_code').val(data.device_code);
                        $('#mobile').val(data.mobile);
                        if(data.status == 1) {
                            $('#status-checkbox').prop('checked', true);
                        } else {
                            $('#status-checkbox').prop('checked', false);
                        }
                        $('#status').val(data.status);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                    }
                });
            }

            function doRedirectDetailUser(params) {
                if(params.code == 202) {
                    window.location.href = "{{url('/user/')}}/" + params.data.id;
                }
            }

            function doRedirectUserList() {
                window.location.href = "{{url('/user/')}}";
            }
        </script>
    </body>
</html>