<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <div class="main-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="mt10">&nbsp;</div>
                        <div class="mt10 visible-xs">&nbsp;</div>
                        <div class="mt30 hidden-xs">&nbsp;</div>
                        <div class="text-center mb20">
                            <div class="row">
                                <div class="col-xs-12 text-left">
                                    <h3><a href="{{url('/')}}">{{trans('app.name')}} · {{trans('app.full_name')}}</a></h3>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @if (session('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                <h4 class="mb0 mt0 ">{{trans("heading.administrator_signin_form")}}</h4>
                                <div class="mt10">&nbsp;</div>
                                <form class="form-horizontal" role="form" method="POST" action="{{ url('/signin') }}">
                                    {{ csrf_field() }}
                                    <div class="form-group input-sm {{ $errors->has('login') ? ' has-error' : '' }}">
                                        <label for="login" class="col-md-4 control-label">{{trans('form.username_or_email')}}</label>
                                        <div class="col-md-8">
                                            <input id="email" type="text" class="form-control" name="login" value="{{ old('login') }}" placeholder="{{trans('form.username_or_email')}}" required autofocus>
                                            <span class="help-block">
                                            @if ($errors->has('login'))
                                                <strong>{{ $errors->first('login') }}</strong>
                                            @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group input-sm {{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">{{trans('form.password')}}</label>
                                        <div class="col-md-8">
                                            <input id="password" type="password" class="form-control" name="password" placeholder="{{trans('form.password')}}" required>
                                            <span class="help-block">
                                            @if ($errors->has('password'))
                                                <strong>{{ $errors->first('password') }}</strong>
                                            @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-8 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                {{trans('button.sign_in')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
    </body>
</html>
