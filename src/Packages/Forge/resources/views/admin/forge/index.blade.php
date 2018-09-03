@extends('admin.dashboard')

@section('pageTitle') Forge @stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card mt-4">
                    <div class="card-header">
                        Settings
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <td>Name</td>
                                <td class="text-right">{{ $settings->name }}</td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td class="text-right">{{ $settings->size }}</td>
                            </tr>
                            <tr>
                                <td>Region</td>
                                <td class="text-right">{{ $settings->region }}</td>
                            </tr>
                            <tr>
                                <td>PHP Verision</td>
                                <td class="text-right">{{ $settings->phpVersion }}</td>
                            </tr>
                            <tr>
                                <td>Public IP</td>
                                <td class="text-right">{{ $settings->ipAddress }}</td>
                            </tr>
                            <tr>
                                <td>Private IP</td>
                                <td class="text-right">{{ $settings->privateIpAddress }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        Scheduled Jobs
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <td>Count</td>
                                <td class="text-right">{{ $jobCount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        Workers
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <tr>
                                <td>Count</td>
                                <td class="text-right">{{ $workerCount }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mt-4">
                    <div class="card-header">
                        Firewalls
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            @foreach ($firewalls as $firewall)
                            <tr>
                                <td>{{ $firewall->name }}</td>
                                <td class="text-right">{{ $firewall->port }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        Sites
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            @foreach ($sites as $site)
                            <tr>
                                <td>{{ $site->name }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
