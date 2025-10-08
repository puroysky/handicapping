@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <!-- Compact Modern Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="header-title">Users Management</h6>
                    <p class="header-subtitle">
                        <i class="fas fa-users me-2"></i>
                        Manage system users and their golf profiles
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" onclick="exportUsers()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-plus me-2"></i>Add New User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="modern-table-container">
                <!-- Search Bar -->
                <div class="table-search-section">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="search-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="search-input" id="userSearch" placeholder="Search users..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="table-info">
                                <small class="text-muted">
                                    Showing <span id="showing-count">{{ count($users) }}</span> of <span id="total-count">{{ count($users) }}</span> users
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table modern-golf-table mb-0">
                        <thead class="golf-header">
                            <tr>
                                <th class="sortable" data-column="0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-user me-2"></i>Name</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-envelope me-2"></i>Email</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-user-tag me-2"></i>Role</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-toggle-on me-2"></i>Status</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span><i class="fas fa-calendar me-2"></i>Joined</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-cogs me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach ($users as $user)
                            <tr class="table-row">
                                <td class="name-cell">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            @if($user->profile->avatar ?? false)
                                            <img src="{{ $user->profile->avatar }}" alt="Avatar" class="avatar-img">
                                            @else
                                            <div class="avatar-placeholder">
                                                {{ strtoupper(substr($user->profile->first_name, 0, 1) . substr($user->profile->last_name, 0, 1)) }}
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="user-name">{{ $user->profile->first_name }} {{ $user->profile->last_name }}</div>
                                            @if($user->profile->phone)
                                            <small class="user-phone">{{ $user->profile->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="email-cell">
                                    <span class="user-email">{{ $user->email }}</span>
                                </td>
                                <td class="role-cell">
                                    <span class="role-badge role-{{ $user->role }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="status-cell">
                                    @if ($user->active)
                                    <span class="status-badge status-active">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </span>
                                    @else
                                    <span class="status-badge status-inactive">
                                        <i class="fas fa-times-circle me-1"></i>Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="date-cell">
                                    <span class="join-date">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                    <small class="join-time d-block">{{ \Carbon\Carbon::parse($user->created_at)->format('g:i A') }}</small>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="btn-group action-group" role="group">
                                        <button class="btn btn-outline-primary btn-action" onclick="viewRecord({{ $user->id }})" title="View User">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" onclick="editRecord({{ $user->id }})" title="Edit User">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" onclick="deleteRecord({{ $user->id }})" title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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

    function exportUsers() {
        console.log('Export users functionality');
        // Implement export logic here
    }

    // Search functionality
    document.getElementById('userSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#userTableBody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        document.getElementById('showing-count').textContent = visibleCount;
    });

    // Sort functionality
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const column = parseInt(this.dataset.column);
            const tbody = document.getElementById('userTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const isAscending = !this.classList.contains('sort-asc');

            // Reset all sort icons
            document.querySelectorAll('.sortable').forEach(h => {
                h.classList.remove('sort-asc', 'sort-desc');
            });

            // Set current sort direction
            this.classList.add(isAscending ? 'sort-asc' : 'sort-desc');

            rows.sort((a, b) => {
                const aVal = a.cells[column].textContent.trim();
                const bVal = b.cells[column].textContent.trim();

                return isAscending ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            });

            rows.forEach(row => tbody.appendChild(row));
        });
    });
</script>

<style>
    /* Golf Theme Color Variables */
    :root {
        --golf-dark-green: #2F4A3C;
        --golf-medium-green: #5E7C4C;
        --golf-light-green: #8DA66E;
        --golf-white: #FFFFFF;
        --golf-black: #000000;
    }

    /* Modern Header Card */




    .header-title {
        font-size: 1.5rem;
        font-weight: 400;
        color: var(--golf-dark-green);
        margin-bottom: 0.25rem;
        line-height: 1.2;
    }

    .header-subtitle {
        color: var(--golf-medium-green);
        margin-bottom: 0;
        font-size: 0.875rem;
    }

    .btn-modern {
        border-radius: 6px;
        padding: 0.375rem 1rem;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .btn-primary.btn-modern {
        background: linear-gradient(135deg, var(--golf-dark-green), var(--golf-medium-green));
        border: none;
    }

    .btn-primary.btn-modern:hover {
        background: linear-gradient(135deg, var(--golf-medium-green), var(--golf-dark-green));
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(47, 74, 60, 0.25);
    }

    /* Modern Table Container */
    .modern-table-container {
        background: var(--golf-white);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(47, 74, 60, 0.1);
        overflow: hidden;
        border: 1px solid var(--golf-light-green);
    }

    /* Search Section */
    .table-search-section {
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #f8f9fc 0%, var(--golf-white) 100%);
        border-bottom: 1px solid var(--golf-light-green);
    }

    .search-wrapper {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 0.875rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--golf-medium-green);
        z-index: 10;
        font-size: 0.875rem;
    }

    .search-input {
        border: 1px solid var(--golf-light-green);
        border-radius: 8px;
        padding: 0.5rem 0.875rem 0.5rem 2.25rem;
        width: 100%;
        transition: all 0.3s ease;
        background: var(--golf-white);
        font-size: 0.875rem;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--golf-medium-green);
        box-shadow: 0 0 0 3px rgba(94, 124, 76, 0.1);
    }

    /* Golf Header - Light Theme */
    .golf-header {
        background: linear-gradient(135deg, #f8f9fc 0%, var(--golf-white) 100%);
        border: none;
        border-bottom: 2px solid var(--golf-light-green);
    }

    .golf-header th {
        background: transparent;
        border: none;
        color: var(--golf-dark-green);
        font-weight: 600;
        font-size: 0.875rem;
        padding: .63rem .5rem;
        position: relative;
        user-select: none;
        vertical-align: middle;
    }

    .golf-header th.sortable {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .golf-header th.sortable:hover {
        background-color: rgba(47, 74, 60, 0.05);
    }

    .sort-icon {
        font-size: 0.75rem;
        opacity: 0.6;
        transition: all 0.2s ease;
        color: var(--golf-medium-green);
    }

    .golf-header th.sortable:hover .sort-icon {
        opacity: 1;
        color: var(--golf-dark-green);
    }

    .golf-header th.sort-asc .sort-icon:before {
        content: "\f0de";
        /* fa-sort-up */
    }

    .golf-header th.sort-desc .sort-icon:before {
        content: "\f0dd";
        /* fa-sort-down */
    }

    /* Table Body */
    .modern-golf-table tbody tr {
        border: none;
        transition: all 0.2s ease;
    }

    .modern-golf-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fc 0%, rgba(141, 166, 110, 0.05) 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(47, 74, 60, 0.08);
    }

    .modern-golf-table td {
        border: none;
        border-bottom: 1px solid rgba(141, 166, 110, 0.2);
        padding: .4rem;
        vertical-align: middle;
    }

    /* Avatar Styles */
    .user-avatar {
        width: 44px;
        height: 44px;
        position: relative;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--golf-light-green);
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--golf-medium-green), var(--golf-light-green));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--golf-white);
        font-weight: 600;
        font-size: 0.875rem;
        border: 2px solid var(--golf-light-green);
    }

    /* User Info */
    .user-name {
        font-weight: 600;
        color: var(--golf-dark-green);
        margin-bottom: 0.125rem;
    }

    .user-phone {
        color: var(--golf-medium-green);
        font-size: 0.75rem;
    }

    .user-email {
        color: var(--golf-medium-green);
        font-weight: 500;
    }

    /* Badges */
    .role-badge {
        display: inline-block;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .role-admin {
        background: linear-gradient(135deg, var(--golf-dark-green), var(--golf-medium-green));
        color: var(--golf-white);
    }

    .role-user {
        background: linear-gradient(135deg, var(--golf-light-green), var(--golf-medium-green));
        color: var(--golf-white);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-active {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }

    .status-inactive {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
    }

    /* Date Display */
    .join-date {
        color: var(--golf-dark-green);
        font-weight: 500;
    }

    .join-time {
        color: var(--golf-medium-green);
        font-size: 0.75rem;
    }

    /* Action Buttons */
    .action-group {
        box-shadow: none;
    }

    .btn-action {
        padding: 0.5rem;
        border-radius: 8px;
        margin: 0 0.125rem;
        transition: all 0.2s ease;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-outline-primary.btn-action {
        border-color: var(--golf-medium-green);
        color: var(--golf-medium-green);
    }

    .btn-outline-primary.btn-action:hover {
        background: var(--golf-medium-green);
        border-color: var(--golf-medium-green);
    }



    /* Responsive Design */
    @media (max-width: 1400px) {

        .header-title {
            font-size: 1rem;
            margin-bottom: 0.125rem;

        }

        .header-subtitle {
            font-size: 0.59rem;
        }

        .btn-modern {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }

        .table-search-section {
            padding: 0.75rem 1rem;
        }

        .search-input {
            padding: 0.375rem 0.75rem 0.375rem 2rem;
            font-size: 0.75rem;
        }

        .search-icon {
            left: 0.75rem;
            font-size: 0.75rem;
        }

        .modern-golf-table td {
            padding: 0.625rem 0.5rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
        }
    }
</style>
@endsection