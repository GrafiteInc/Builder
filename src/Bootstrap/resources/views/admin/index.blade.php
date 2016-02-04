@extends('dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Account Admin</h1>

            <div class="col-md-12">
                @include('partials.errors')
                @include('partials.message')

                <table class="table table-striped raw-margin-top-24">

                    <thead>
                        <th>Email</th>
                        <th class="text-right">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)

                            @if ($account->id !== Auth::id())
                                <tr>
                                    <td>{{ $account->email }}</td>
                                    <td>
                                        <a class="btn btn-danger btn-sm raw-margin-left-16 pull-right" href="{{ url('admin/accounts/'.$account->id.'/delete') }}" onclick="return confirm('Are you sure you want to delete this user?')"><span class="fa fa-edit"> Delete</span></a>
                                        <a class="btn btn-warning btn-sm pull-right" href="{{ url('admin/accounts/'.$account->id.'/edit') }}"><span class="fa fa-edit"> Edit</span></a>
                                    </td>
                                </tr>
                            @endif

                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>

@stop
