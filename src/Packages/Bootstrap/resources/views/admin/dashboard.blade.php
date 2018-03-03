@extends('dashboard')

@section('pageTitle') Admin Dashboard @stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (Session::get('original_user'))
                <a class="btn btn-secondary pull-right" href="/users/switch-back">Return to your Login</a>
            @endif
        </div>
    </div>

    <div class="row raw-margin-top-24">
        <div class="col-md-4">
            <h2 class="text-center">Server</h2>
            <table class="table table-striped raw-margin-top-48">
                <tr>
                    <td>PHP Version</td>
                    <td class="text-right">{{ phpversion() }}</td>
                </tr>
                <tr>
                    <td>Server Address</td>
                    <td class="text-right">{{ request()->server('SERVER_ADDR') }}</td>
                </tr>
                <tr>
                    <td>Server Name</td>
                    <td class="text-right">{{ request()->server('SERVER_NAME') }}</td>
                </tr>
                <tr>
                    <td>Server Software</td>
                    <td class="text-right">{{ request()->server('SERVER_SOFTWARE') }}</td>
                </tr>
                <tr>
                    <td>Server Port</td>
                    <td class="text-right">{{ request()->server('SERVER_PORT') }}</td>
                </tr>
                <tr>
                    <td>Server Protocol</td>
                    <td class="text-right">{{ request()->server('SERVER_PROTOCOL') }}</td>
                </tr>
                <tr>
                    <td>Your Address</td>
                    <td class="text-right">{{ request()->server('REMOTE_ADDR') }}</td>
                </tr>
            </table>
        </div>
        <div class="col-md-4">
            <h2 class="text-center">Database</h2>
            <table class="table table-striped raw-margin-top-48">
                <tr>
                    <td>Type</td>
                    <td class="text-right">{{ ucfirst(app(Illuminate\Database\Connection::class)->getName()) }}</td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td class="text-right">{{ app(Illuminate\Database\Connection::class)->getDatabaseName() }}</td>
                </tr>
                @foreach (DB::select('SHOW VARIABLES LIKE "%version%";') as $dbVar)
                    <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $dbVar->Variable_name)) }}</td>
                        <td class="text-right">{{ $dbVar->Value }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-md-4">
            <h2 class="text-center">Tables</h2>

            <table class="table table-striped raw-margin-top-48">
                <tr>
                    <td>Users</td>
                    <td class="text-right">{{ app(App\Models\User::class)->count() }}</td>
                </tr>
                <tr>
                    <td>Roles</td>
                    <td class="text-right">{{ app(App\Models\Role::class)->count() }}</td>
                </tr>
            </table>
        </div>
    </div>

@stop
