@props([
'headers' => [],
'data' => [],
'actions' => true,
'searchable' => true,
'paginated' => true,
'striped' => true,
'hover' => true,
'responsive' => true
])

<div class="table-container">
    @if($searchable)
    <!-- Search Bar -->
    <div class="table-search-bar mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control" id="tableSearch" placeholder="Search..." autocomplete="off">
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="table-info">
                    <small class="text-muted">
                        Showing <span id="showing-count">{{ count($data) }}</span> of <span id="total-count">{{ count($data) }}</span> entries
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="table-wrapper {{ $responsive ? 'table-responsive' : '' }}">
        <table class="table modern-table {{ $striped ? 'table-striped' : '' }} {{ $hover ? 'table-hover' : '' }}">
            <thead class="table-header">
                <tr>
                    @foreach($headers as $header)
                    <th scope="col" class="sortable" data-column="{{ $loop->index }}">
                        <div class="d-flex align-items-center justify-content-between">
                            <span>{{ $header }}</span>
                            <i class="fas fa-sort sort-icon ms-2"></i>
                        </div>
                    </th>
                    @endforeach
                    @if($actions)
                    <th scope="col" class="text-center" style="width: 120px;">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($data as $index => $row)
                <tr class="table-row" data-index="{{ $index }}">
                    @foreach($headers as $headerIndex => $header)
                    <td class="{{ $headerIndex === 0 ? 'fw-medium' : '' }}">
                        @if($headerIndex === 0 && isset($row['avatar']))
                        <!-- Name with Avatar -->
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-2">
                                @if($row['avatar'])
                                <img src="{{ $row['avatar'] }}" alt="Avatar" class="rounded-circle">
                                @else
                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center">
                                    {{ strtoupper(substr($row['name'] ?? 'U', 0, 1)) }}
                                </div>
                                @endif
                            </div>
                            <div>
                                <div class="fw-medium">{{ $row['name'] ?? 'N/A' }}</div>
                                @if(isset($row['role']))
                                <small class="text-muted">{{ ucfirst($row['role']) }}</small>
                                @endif
                            </div>
                        </div>
                        @elseif($headerIndex === 1 && strtolower($header) === 'email')
                        <!-- Email with Icon -->
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <span>{{ $row['email'] ?? 'N/A' }}</span>
                        </div>
                        @elseif(in_array(strtolower($header), ['created at', 'created_at', 'date']))
                        <!-- Date with Icon -->
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar text-muted me-2"></i>
                            <span>{{ isset($row['created_at']) ? \Carbon\Carbon::parse($row['created_at'])->format('M j, Y') : 'N/A' }}</span>
                        </div>
                        @elseif(strtolower($header) === 'status')
                        <!-- Status Badge -->
                        @php
                        $status = $row['status'] ?? 'inactive';
                        $badgeClass = $status === 'active' ? 'bg-success' : 'bg-secondary';
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                            {{ ucfirst($status) }}
                        </span>
                        @else
                        <!-- Default Cell -->
                        {{ $row[strtolower(str_replace(' ', '_', $header))] ?? 'N/A' }}
                        @endif
                    </td>
                    @endforeach

                    @if($actions)
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm action-btn"
                                data-action="view" data-id="{{ $row['id'] ?? $index }}"
                                title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm action-btn"
                                data-action="edit" data-id="{{ $row['id'] ?? $index }}"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm action-btn"
                                data-action="delete" data-id="{{ $row['id'] ?? $index }}"
                                title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}" class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No data available</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paginated && count($data) > 10)
    <!-- Pagination -->
    <div class="table-pagination mt-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <select class="form-select form-select-sm" id="entriesPerPage" style="width: auto; display: inline-block;">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <small class="text-muted ms-2">entries per page</small>
            </div>
            <div class="col-md-6">
                <nav aria-label="Table pagination">
                    <ul class="pagination pagination-sm justify-content-end mb-0" id="tablePagination">
                        <!-- Pagination will be generated by JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Styles -->
<style>
    /* Modern Table Styles */
    .table-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .modern-table {
        margin-bottom: 0;
        border: none;
    }

    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    .table-header th {
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 1rem 0.75rem;
        position: relative;
        user-select: none;
    }

    .table-header th.sortable {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .table-header th.sortable:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sort-icon {
        font-size: 0.75rem;
        opacity: 0.6;
        transition: all 0.2s ease;
    }

    .table-header th.sortable:hover .sort-icon {
        opacity: 1;
    }

    .table-header th.sort-asc .sort-icon:before {
        content: "\f0de";
        /* fa-sort-up */
    }

    .table-header th.sort-desc .sort-icon:before {
        content: "\f0dd";
        /* fa-sort-down */
    }

    .modern-table tbody tr {
        border: none;
        transition: all 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8f9ff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .modern-table td {
        border: none;
        border-bottom: 1px solid #e9ecef;
        padding: 0.875rem 0.75rem;
        vertical-align: middle;
    }

    /* Avatar Styles */
    .avatar-sm {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
    }

    .avatar-sm img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-placeholder {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Search Bar Styles */
    .table-search-bar .input-group-text {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .table-search-bar .form-control {
        border-color: #e9ecef;
    }

    .table-search-bar .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    /* Button Styles */
    .btn-group .btn {
        border-radius: 6px !important;
        margin-right: 2px;
        padding: 0.375rem 0.5rem;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    /* Empty State */
    .empty-state {
        padding: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .table-search-bar .row>div {
            margin-bottom: 0.5rem;
        }

        .table-search-bar .text-end {
            text-align: left !important;
        }

        .table-pagination .row>div {
            margin-bottom: 0.5rem;
        }

        .table-pagination .justify-content-end {
            justify-content: start !important;
        }
    }
</style>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('tableSearch');
        const tableBody = document.getElementById('tableBody');
        const showingCount = document.getElementById('showing-count');

        if (searchInput && tableBody) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = tableBody.querySelectorAll('tr.table-row');
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

                if (showingCount) {
                    showingCount.textContent = visibleCount;
                }
            });
        }

        // Sort functionality
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                const column = parseInt(this.dataset.column);
                const isAsc = this.classList.contains('sort-asc');

                // Remove sort classes from all headers
                document.querySelectorAll('.sortable').forEach(h => {
                    h.classList.remove('sort-asc', 'sort-desc');
                });

                // Add appropriate sort class
                this.classList.add(isAsc ? 'sort-desc' : 'sort-asc');

                // Sort the table
                sortTable(column, !isAsc);
            });
        });
    });

    // Sort table function
    function sortTable(column, ascending = true) {
        const tableBody = document.getElementById('tableBody');
        const rows = Array.from(tableBody.querySelectorAll('tr.table-row'));

        rows.sort((a, b) => {
            const aVal = a.children[column].textContent.trim();
            const bVal = b.children[column].textContent.trim();

            // Check if values are dates
            const aDate = new Date(aVal);
            const bDate = new Date(bVal);
            if (!isNaN(aDate) && !isNaN(bDate)) {
                return ascending ? aDate - bDate : bDate - aDate;
            }

            // Check if values are numbers
            const aNum = parseFloat(aVal);
            const bNum = parseFloat(bVal);
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return ascending ? aNum - bNum : bNum - aNum;
            }

            // String comparison
            return ascending ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
        });

        // Reorder rows in the table
        rows.forEach(row => tableBody.appendChild(row));
    }

    // Action buttons event delegation
    $(document).on('click', '.action-btn', function() {
        const action = $(this).data('action');
        const id = $(this).data('id');

        switch (action) {
            case 'view':
                if (typeof viewRecord === 'function') {
                    viewRecord(id);
                } else {
                    console.log('View record:', id);
                }
                break;
            case 'edit':
                if (typeof editRecord === 'function') {
                    editRecord(id);
                } else {
                    console.log('Edit record:', id);
                }
                break;
            case 'delete':
                if (typeof deleteRecord === 'function') {
                    deleteRecord(id);
                } else {
                    if (confirm('Are you sure you want to delete this record?')) {
                        console.log('Delete record:', id);
                    }
                }
                break;
        }
    });
</script>