<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{trans('menu.user')}} · {{trans('app.name')}}</title>
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
                <h3>{{trans('menu.user')}}</h3>
                <div class="row">
                    <form id="form-search" class="form-horizontal" method="post">
                        <div class="form-group input-sm">
                            <label for="username" class="col-md-2 control-label">{{trans('form.username')}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="username" placeholder="{{trans('form.username')}}">
                            </div>
                        </div>
                        <div class="form-group input-sm">
                            <label for="full_name" class="col-md-2 control-label">{{trans('form.name')}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="full_name" placeholder="{{trans('form.full_name')}}">
                            </div>
                        </div>
                        <div class="form-group input-sm">
                            <label for="email" class="col-md-2 control-label">{{trans('form.email')}}</label>
                            <div class="col-md-4">
                                <input type="email" class="form-control" name="email" placeholder="{{trans('form.email')}}">
                            </div>
                        </div>
                        <div class="form-group input-sm">
                            <label for="email" class="col-md-2 control-label">{{trans('form.device_code')}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="device_code" placeholder="{{trans('form.device_code')}}">
                            </div>
                        </div>
                        <div class="form-group input-sm">
                            <div class="col-md-offset-2 col-md-10">
                                <div class="btn-group-sm">
                                    <button type="button" class="btn btn-default" onclick="doSearchForm();"><i class="fa fa-search fa-fw" aria-hidden="true"></i> {{trans('form.search')}}</button>
                                    <button type="button" class="btn btn-success" onclick="showNewUserForm();"><i class="fa fa-plus fa-fw" aria-hidden="true"></i> {{trans('form.add')}}</button>
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
                        <thead> 
                            <tr>
                                <th class="col-sm-2" data-field="username" data-sortable="true" data-formatter="usernameFormatter">{{trans('form.username')}}</th>
                                <th data-field="full_name" data-sortable="true">{{trans('form.full_name')}}</th>
                                <th class="col-sm-2" data-field="email">{{trans('form.email')}}</th>
                                <th class="col-sm-1" data-field="mobile">{{trans('form.mobile')}}</th>
                                <th class="col-sm-1" data-field="device_code">{{trans('form.device_code')}}</th>
                                <th class="col-sm-1" data-field="status" data-formatter="statusFormatter">{{trans('form.status')}}</th>
                                <th class="col-sm-1" data-formatter="actionFormatter"></th>
                            </tr> 
                        </thead> 
                        <tbody></tbody> 
                    </table>
                </div>
            </div>

            <div class="modal fade" id="modal-user" tabindex="-1" role="dialog" aria-labelledby="user-label" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog" role="document">
                    <form id="form-user" class="form-horizontal" role="form">         
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="user-label"><i class="fa fa-user-plus" aria-hidden="true"></i> {{trans('form.create_new_user')}}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="error-panel" class="alert alert-danger alert-dismissable"></div>
                                <div id="username-group" class="form-group input-sm mb5">
                                    <label class="col-md-4 control-label">{{trans('form.username')}}</label>
                                    <div class="col-md-6">
                                        <input id="username" name="username" type="text" class="form-control" placeholder="{{trans('form.username')}}">
                                    </div>
                                </div>
                                <div id="email-group" class="form-group input-sm mb5">
                                    <label class="col-md-4 control-label">{{trans('form.email')}}</label>
                                    <div class="col-md-6">
                                        <input id="email" name="email" type="email" class="form-control" placeholder="{{trans('form.email')}}">
                                    </div>
                                </div>
                                <div id="full-name-group" class="form-group input-sm mb5">
                                    <label class="col-md-4 control-label">{{trans('form.full_name')}}</label>
                                    <div class="col-md-4">
                                        <input id="first-name" name="first_name" type="text" class="form-control" placeholder="{{trans('form.first_name')}}">
                                    </div>
                                    <div class="col-md-4">
                                        <input id="last-name" name="last_name" type="text" class="form-control" placeholder="{{trans('form.last_name')}}">
                                    </div>
                                </div>
                                <div id="device-code-group" class="form-group input-sm mb5">
                                    <label class="col-md-4 control-label">{{trans('form.device_code')}}</label>
                                    <div class="col-md-6">
                                        <input id="device-code" name="device_code" type="text" class="form-control" placeholder="{{trans('form.device_code')}}">
                                    </div>
                                </div>
                                <div id="mobile-group" class="form-group input-sm mb5">
                                    <label class="col-md-4 control-label">{{trans('form.mobile')}}</label>
                                    <div class="col-md-6">
                                        <input id="mobile" name="mobile" type="numeric" class="form-control" placeholder="{{trans('form.mobile')}}">
                                    </div>
                                </div>
                                <div id="status-group" class="form-group input-sm mb5">
                                    <div class="col-md-offset-4 col-md-6">
                                        <input type="checkbox" name="status" checked disabled> {{trans('form.active')}}
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
                            <div class="btn-group-sm">
                                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                            </div>
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

            function doSearchForm() {
                var data = new FormData();
                data.append('username', $('input[name=username]').val());
                data.append('full_name', $('input[name=full_name]').val());
                data.append('email', $('input[name=email]').val());
                data.append('device_code', $('input[name=device_code]').val());

                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/user/search')}}',
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

            function showNewUserForm() {
                $('#modal-user').on('show.bs.modal', function(event) {
                    $('#error-panel').html('');
                    $('#error-panel').hide();
                    $("#form-user .form-group").each(function() {
                        $(this).removeClass('has-error');
                    });
                    $('#status-group').hide();
                    $('#btn-activate').hide();
                    $('#btn-deactivate').hide();
                    $('#form-user').on('submit', function(event) {
                        event.preventDefault();
                        $('#modal-loading').modal('show');

                        $('#error-panel').html('');
                        $('#error-panel').hide();
                        $("#form-user .form-group").each(function() {
                            $(this).removeClass('has-error');
                        });

                        $.ajax({
                            url: '{{url('/json/user')}}',
                            type: 'POST',
                            data: new FormData(this),
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                $('#modal-loading').modal('hide');
                                $('#modal-user').modal('hide');
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
                $('#modal-user').on('hide.bs.modal', function(event) {
                    $('#form-user')[0].reset();
                });
                $('#modal-user').on('hidden.bs.modal', function(event) {
                    $('#status-group').show();
                    $('#btn-activate').show();
                    $('#btn-deactivate').show();
                    $('#form-user').off('submit');
                    $('#modal-user').off('show.bs.modal');
                    $('#modal-user').off('hide.bs.modal');
                    $('#modal-user').off('hidden.bs.modal');
                });
                $('#modal-user').modal('show');
            }

            function doRedirectDetailUser(params) {
                if(params.code == 202) {
                    window.location.href = "{{url('/user/')}}/" + params.data.id;
                }
            }

            function doRedirectEditUser(id) {
                window.location.href = "{{url('/user/edit')}}/" + id;
            }

            function usernameFormatter(value, row, index) {
                return '<div class="text-center"><a href="{{url('/user')}}/' +row.id+'">'+value+'</a></div>';
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
                        message = 'Are you sure for delete user <strong>' + data.username + "</strong> ?";
                        $('#modal-confirmation').on('hidden.bs.modal', function(event) {
                            $('#button-delete-ok').off("click");
                        });
                        $('#modal-confirmation').modal('show');
                        $('#text-confirmation').html(message);
                        $('#text-confirmation').addClass('text-danger');
                        $('#btn-ok').on('click', function(event) {
                            doDeleteUserForm(data.id);
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $('#modal-loading').modal('hide');
                        var json = JSON.parse(xhr.responseText);
                    }
                });
            }

            function doDeleteUserForm(id) {
                var data = new FormData();
                data.append('id', id);
                $('#modal-loading').modal('show');
                $.ajax({
                    url: '{{url('/json/user/delete')}}',
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

            function statusFormatter(value, row, index) {
                if(row.status == 1) {
                    return '<p class="text-primary text-center">{{trans('form.active')}}</p>';
                } else {
                    return '<p class="text-danger text-center">{{trans('form.inactive')}}</p>';
                }
            }

            function actionFormatter(value, row, index) {
                var buttons = '<div class="btn-group-xs text-center">';
                buttons += ' <button class="btn btn-warning" onclick="doRedirectEditUser(' + 
                        "'" + row.id + "'" + ')"><i class="fa fa-pencil fa-fw" aria-hidden="true"></i></button>';
                buttons += ' <button class="btn btn-danger" onclick="showUserDeleteConfirmation(' + 
                        "'" + row.id + "'" + ')"><span class="glyphicon glyphicon-trash"></span></button>';
                buttons += '</div>';
                return buttons;
            }
        </script>
    </body>
</html>
