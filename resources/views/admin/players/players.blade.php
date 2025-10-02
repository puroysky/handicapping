@extends('layouts.app')

@section('content')
<h1 class="my-4">Player Management</h1>
<a href="{{ route('admin.players.create') }}" class="btn btn-primary mb-3">Add New Player</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Handicap</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>john.doe@example.com</td>
            <td>10.5</td>
            <td>
                <a href="#" class="btn btn-sm btn-warning">Edit</a>
                <a href="#" class="btn btn-sm btn-danger">Delete</a>
            </td>
        </tr>
        <tr></tr>
        <td>Jane Smith</td>
        <td>jane.smith@example.com</td>
        <td>12.3</td>
        <td>
            <a href="#" class="btn btn-sm btn-warning">Edit</a>
            <a href="#" class="btn btn-sm btn-danger">Delete</a>
        </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total Players: 2</th>
        </tr>
    </tfoot>
</table>
@endsection