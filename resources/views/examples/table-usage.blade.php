{{-- Example usage of the modern table component --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-1">Users Management</h2>
                    <p class="text-muted mb-0">Manage system users and their permissions</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New User
                </a>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('M d, Y') }}</td>
                        <td>
                            @if ($user['status'] === 'active')
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" onclick="viewRecord({{ $user['id'] }})">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning" onclick="editRecord({{ $user['id'] }})">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteRecord({{ $user['id'] }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

{{-- Custom Action Functions --}}
<script>
    function viewRecord(id) {
        window.location.href = `/admin/users/${id}`;
    }

    function editRecord(id) {
        window.location.href = `/admin/users/${id}/edit`;
    }

    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            // Create a form and submit it for DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/users/${id}`;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add method override for DELETE
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection

{{--
Controller Example:

public function index()
{
    $users = User::all()->map(function ($user) {
        return [
            'id' => $user->id,
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'status' => $user->active ? 'active' : 'inactive',
            'role' => $user->role,
            'avatar' => $user->avatar_url ?? null,
        ];
    });

    return view('admin.users.index', compact('users'));
}
--}}