<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('menu.user_profile')}} · {{trans('app.name')}}</title>
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
                        <div class="col-md-2">
                            <div class="text-center">
                                <img src="http://placehold.it/100" class="avatar img-circle" alt="Avatar"/>
                                
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="username-group" class="form-group mb5">
                                <label class="col-md-3 control-label">{{trans('form.username')}}</label>
                                <div class="col-md-6">
                                    <input id="username" name="username" type="text" class="form-control" placeholder="{{trans('form.username')}}">
                                    <span class="help-block">
                                        <strong id="username-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <!--
                            <div id="password-group" class="form-group mb5">
                                <label class="col-md-3 control-label">{{trans('form.password')}}</label>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="showResetPasswordForm('{{$id}}')"><i class="fa fa-unlock-alt fa-fw" aria-hidden="true"></i> {{trans('button.reset_password')}}</button>
                                </div>
                            </div>
                            -->
                            <div id="email-group" class="form-group mb5">
                                <label class="col-md-3 control-label">{{trans('form.email')}}</label>
                                <div class="col-md-6">
                                    <input id="email" name="email" type="email" class="form-control" placeholder="{{trans('form.email')}}">
                                    <span class="help-block">
                                        <strong id="email-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="full-name-group" class="form-group mb5">
                                <label class="col-md-3 control-label">{{trans('form.full_name')}}</label>
                                <div class="col-md-4">
                                    <input id="first_name" name="first_name" type="text" class="form-control" placeholder="{{trans('form.first_name')}}">
                                    <span class="help-block">
                                        <strong id="first-name-help" class="help-text"></strong>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <input id="last_name" name="last_name" type="text" class="form-control" placeholder="{{trans('form.last_name')}}">
                                    <span class="help-block">
                                        <strong id="last-name-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="device-code-group" class="form-group mb5">
                                <label class="col-md-3 control-label">{{trans('form.device_code')}}</label>
                                <div class="col-md-6">
                                    <input id="device_code" name="device_code" type="text" class="form-control" placeholder="{{trans('form.device_code')}}">
                                    <span class="help-block">
                                        <strong id="device-code-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="mobile-group" class="form-group mb5">
                                <label class="col-md-3 control-label">{{trans('form.mobile')}}</label>
                                <div class="col-md-6">
                                    <input id="mobile" name="mobile" type="numeric" class="form-control" placeholder="{{trans('form.mobile')}}">
                                    <span class="help-block">
                                        <strong id="mobile-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="status-group" class="form-group mb5">
                                <div class="col-md-offset-3 col-md-6">
                                    <input type="checkbox" id="status" name="status" disabled> {{trans('form.active')}}
                                    <span class="help-block">
                                        <strong id="status-help" class="help-text"></strong>
                                    </span>
                                </div>
                            </div>
                            <div id="status-group" class="form-group mb5">
                                <div class="col-md-offset-3 col-md-6">
                                    <div class="btn-group-sm">
                                        <button id="btn-edit" type="button" class="btn btn-warning" onclick="doRedirectEditUser('{{$id}}')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i> {{trans('button.edit')}}</button>
                                        <button id="btn-activate" type="button" class="btn btn-success" onclick="doChangeStatusUserForm('{{$id}}','activate')"><i class="fa fa-check-square-o fa-fw" aria-hidden="true"></i> {{trans('button.activate')}}</button>
                                        <button id="btn-deactivate" type="button" class="btn btn-danger" onclick="doChangeStatusUserForm('{{$id}}','deactivate')"><i class="fa fa-square-o fa-fw" aria-hidden="true"></i> {{trans('button.deactivate')}}</button>
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

        <script>
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
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
                        $('#username').prop('disabled', true);
                        $('#email').val(data.email);
                        $('#email').prop('disabled', true);
                        $('#first_name').val(data.first_name);
                        $('#first_name').prop('disabled', true);
                        $('#last_name').val(data.last_name);
                        $('#last_name').prop('disabled', true);
                        $('#device_code').val(data.device_code);
                        $('#device_code').prop('disabled', true);
                        $('#mobile').val(data.mobile);
                        $('#mobile').prop('disabled', true);
                        if(data.status == 1) {
                            $('#status').prop('checked', true);
                            $('#btn-activate').hide();
                        } else {
                            $('#status').prop('checked', false);
                            $('#btn-deactivate').hide();
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                    }
                });
            }

            function doRedirectEditUser(id) {
                window.location.href = "{{url('/user/edit')}}/" + id;
            }

            function doChangeStatusUserForm(id, action) {
                var data = new FormData();
                data.append('id', id);
                data.append('action', action);
                $.ajax({
                    url: '{{url('/json/user/status')}}',
                    type: 'POST',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        $('#modal-loading').modal('hide');
                        $('#modal-user').modal('hide');
                        showNotification(response.message, false, doRedirectDetailUser, response);
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                        showNotification(json.errors, true, null, null);
                    }
                });
            }
            
            function doRedirectDetailUser(params) {
                if(params.code == 202) {
                    window.location.href = "{{url('/user/')}}/" + params.data.id;
                }
            }

            function showUserDeleteConfirmation(id) {
                $.ajax({
                    type: 'GET',
                    url: '{{url('/json/user/')}}/' + id,
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
                        message = 'Are you sure for reset password user <strong>' + data.username + "</strong> ?";
                        $('#modal-confirmation').on('hidden.bs.modal', function(event) {
                            $('#button-delete-ok').off("click");
                        });
                        $('#modal-confirmation').modal('show');
                        $('#text-confirmation').html(message);
                        $('#text-confirmation').addClass('text-danger');
                        $('#btn-ok').on('click', function(event) {
                            doResetPasswordUserForm(data.id);
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                    }
                });
            }

            function doResetPasswordUserForm(id) {
                var data = new FormData();
                data.append('id', id);
                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/user/reset')}}',
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
        </script>
    </body>
</html>