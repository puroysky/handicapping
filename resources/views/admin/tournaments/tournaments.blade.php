@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <!-- Compact Modern Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="header-title">Tournaments Management</h6>
                    <p class="header-subtitle">
                        <i class="fas fa-users me-2"></i>
                        Manage system tournaments and their golf profiles
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" onclick="exportUsers()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <a href="{{ route('admin.tournaments.create') }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-plus me-2"></i>Add New Tournament
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
                                <input type="text" class="search-input" id="tableSearch" placeholder="Search tournaments..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="table-info">
                                <small class="text-muted">
                                    Showing <span id="showing-count">{{ count($tournaments) }}</span> of <span id="total-count">{{ count($tournaments) }}</span> tournaments
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead class="table-modern-header">
                            <tr>
                                <th class="sortable" data-column="0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span></i>Name</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Description</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Course</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Scorecards</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Start Date</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>End Date</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Status</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="5">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Created At</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-cogs me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="mainTableBody">
                            @foreach ($tournaments as $tournament)
                            <tr class="table-row">
                                <td class="name-cell">
                                    <span class="user-name">{{ $tournament->tournament_name ?? 'N/A' }}</span>
                                </td>
                                <td class="">
                                    <span class="">
                                        {{ $tournament->tournament_desc ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @foreach ($tournament->tournamentCourses as $course)
                                    <span class="badge bg-secondary me-1 mb-1">{{ $course->course->course_name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($tournament->tournamentCourses as $course)
                                    <span class="badge bg-info me-1 mb-1">{{ $course->scorecard->scorecard_code }}</span>
                                    @endforeach
                                </td>
                                <td class="">
                                    <span class="">{{ $tournament->tournament_start }}</span>
                                </td>
                                <td class="">
                                    <span class="">{{ $tournament->tournament_end }}</span>
                                </td>
                                <td class="status-cell">
                                    @if ($tournament->active)
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
                                    <span class="cell-text-date">{{ \Carbon\Carbon::parse($tournament->created_at)->format('M d, Y') }}</span>
                                    <small class="cell-text-time d-block">{{ \Carbon\Carbon::parse($tournament->created_at)->format('g:i A') }}</small>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-wrapper">
                                        <button class="btn btn-outline-secondary btn-context-menu"
                                            type="button"
                                            onclick="showUserContextMenu({{ $tournament->tournament_id }}, '{{ $tournament->tournament_name }}', event)"
                                            title="Actions"
                                            data-label="Actions">
                                            <i class="fas fa-ellipsis-v me-1"></i>
                                            <span class="action-text">Actions</span>
                                        </button>
                                        <span class="action-label">Actions</span>
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

<!-- Import Tournaments Modal -->
<div class="modal fade" id="importTournamentsModal" tabindex="-1" aria-labelledby="importTournamentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importTournamentsModalLabel">
                    <i class="fas fa-upload me-2"></i>Migrate from Excel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importTournamentsForm" enctype="multipart/form-data">
                    @csrf
                    <!-- File Upload Section -->
                    <div class="mb-4">
                        <label for="import_file" class="form-label fw-bold">
                            <i class="fas fa-file-excel me-1"></i>Select Excel File
                        </label>
                        <input type="file"
                            class="form-control"
                            id="import_file"
                            name="import_file"
                            accept=".xlsx,.xls,.csv"
                            required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Supported formats: Excel (.xlsx, .xls) or CSV files. Maximum size: 2MB
                        </div>
                    </div>

                    <!-- File Requirements -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-list me-1"></i>Required Column Headers
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mb-0 small">
                                    <li><strong>account_no</strong> - Account Number (string)</li>
                                    <li><strong>name</strong> - Player Name (string)</li>
                                    <li><strong>adjusted_gross_score</strong> - Adjusted Score (numeric)</li>
                                    <li><strong>slope_rating</strong> - Slope Rating (numeric)</li>
                                    <li><strong>course_rating</strong> - Course Rating (decimal)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0 small">
                                    <li><strong>holes_completed</strong> - Holes Played (F9, B9, or 18)</li>
                                    <li><strong>date_played</strong> - Date Played (YYYY-MM-DD)</li>
                                    <li><strong>tee_id</strong> - Tee ID (numeric)</li>
                                    <li><strong>course_id</strong> - Course ID (numeric)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Format -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="downloadTournamentSampleFile()">
                            <i class="fas fa-download me-1"></i>Download Sample Format
                        </button>
                    </div>

                    <!-- Progress Bar (hidden initially) -->
                    <div id="importProgress" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Migrating scores...</small>
                            <small class="text-muted" id="importProgressText">0%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                style="width: 0%"
                                id="importProgressBar"></div>
                        </div>
                    </div>

                    <!-- Results Section (hidden initially) -->
                    <div id="importResults" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="startTournamentImport()" id="importBtn">
                    <i class="fas fa-upload me-1"></i>Migrate Scores
                </button>
            </div>
        </div>
    </div>
</div>

@include('admin.tournaments.tournament-preview-modal')

<!-- Import WHS Handicap Indexes Modal -->
<div class="modal fade" id="importWhsHandicapIndexesModal" tabindex="-1" aria-labelledby="importWhsHandicapIndexesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importWhsHandicapIndexesModalLabel">
                    <i class="fas fa-download me-2"></i>Import WHS Handicap Indexes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importWhsHandicapIndexesForm" enctype="multipart/form-data">
                    @csrf
                    <!-- File Upload Section -->
                    <div class="mb-4">
                        <label for="whs_import_file" class="form-label fw-bold">
                            <i class="fas fa-file-excel me-1"></i>Select Excel File
                        </label>
                        <input type="file"
                            class="form-control"
                            id="whs_import_file"
                            name="whs_import_file"
                            accept=".xlsx,.xls,.csv"
                            required>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Supported formats: Excel (.xlsx, .xls) or CSV files. Maximum size: 2MB
                        </div>
                    </div>

                    <!-- File Requirements -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="fas fa-list me-1"></i>Required & Optional Column Headers
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <strong class="d-block mb-2 text-success">Required Fields:</strong>
                                <ul class="mb-3 small">
                                    <li><strong>whs_no</strong> - WHS Number (numeric)</li>
                                    <li><strong>whs_handicap_index</strong> - WHS Handicap Index (numeric)</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block mb-2 text-warning">Optional Fields:</strong>
                                <ul class="mb-0 small">
                                    <li><strong>name</strong> - Player Name (string)</li>
                                    <li><strong>sex</strong> - Sex (M or F)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Format -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="downloadWhsHandicapSampleFile()">
                            <i class="fas fa-download me-1"></i>Download Sample Format
                        </button>
                    </div>

                    <!-- Progress Bar (hidden initially) -->
                    <div id="whsImportProgress" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Importing WHS handicap indexes...</small>
                            <small class="text-muted" id="whsImportProgressText">0%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                style="width: 0%"
                                id="whsImportProgressBar"></div>
                        </div>
                    </div>

                    <!-- Results Section (hidden initially) -->
                    <div id="whsImportResults" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="startWhsHandicapImport()" id="whsImportBtn">
                    <i class="fas fa-download me-1"></i>Import Handicaps
                </button>
            </div>
        </div>
    </div>
</div>

@include('admin.tournaments.tournament-preview-modal')
{{-- Custom Action Functions --}}
<script>
    // Modern Context Menu Implementation
    function modernContext(config) {
        // Remove any existing context menu
        const existingMenu = document.querySelector('.modern-context-menu');
        if (existingMenu) {
            existingMenu.remove();
        }

        const {
            data,
            recordId,
            items
        } = config;

        // Create context menu container
        const contextMenu = document.createElement('div');
        contextMenu.className = 'modern-context-menu';
        contextMenu.innerHTML = `
            <div class="modern-context-header">
                <div class="context-title">${data.title || 'Actions'}</div>
                ${data.subtitle ? `<div class="context-subtitle">${data.subtitle}</div>` : ''}
            </div>
            <div class="modern-context-items">
                ${items.map(item => `
                    <div class="modern-context-item" data-action="${items.indexOf(item)}">
                        <div class="context-item-icon">
                            <i class="fas fa-${item.icon}"></i>
                        </div>
                        <div class="context-item-content">
                            <div class="context-item-label">${item.label}</div>
                            ${item.description ? `<div class="context-item-description">${item.description}</div>` : ''}
                        </div>
                    </div>
                `).join('')}
            </div>
        `;

        // Add click handlers
        items.forEach((item, index) => {
            contextMenu.querySelector(`[data-action="${index}"]`).addEventListener('click', () => {
                item.action(recordId, data);
                contextMenu.remove();
            });
        });

        // Position and show menu
        document.body.appendChild(contextMenu);

        // Get trigger element and viewport dimensions
        const triggerElement = event.target.closest('.btn-context-menu');
        const rect = triggerElement.getBoundingClientRect();
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const scrollX = window.pageXOffset;
        const scrollY = window.pageYOffset;

        // Initial positioning - prefer right side of button
        let left = rect.right + 8;
        let top = rect.top;

        // Get menu dimensions after adding to DOM
        const menuRect = contextMenu.getBoundingClientRect();
        const menuWidth = menuRect.width;
        const menuHeight = menuRect.height;

        // Horizontal positioning logic
        if (left + menuWidth > viewportWidth - 20) {
            // Not enough space on right, try left side
            left = rect.left - menuWidth - 8;

            // If still not enough space on left, center it
            if (left < 20) {
                left = Math.max(20, (viewportWidth - menuWidth) / 2);
            }
        }

        // Vertical positioning logic
        if (top + menuHeight > viewportHeight - 20) {
            // Not enough space below, position above
            top = rect.bottom - menuHeight;

            // If still not enough space, position at bottom of viewport
            if (top < 20) {
                top = Math.max(20, viewportHeight - menuHeight - 20);
            }
        }

        // Apply final positioning with scroll offsets
        contextMenu.style.left = `${left + scrollX}px`;
        contextMenu.style.top = `${top + scrollY}px`;

        // Add positioning class for animation direction
        if (left < rect.left) {
            contextMenu.classList.add('menu-left');
        } else {
            contextMenu.classList.add('menu-right');
        }

        // Close menu when clicking outside
        setTimeout(() => {
            document.addEventListener('click', function closeMenu(e) {
                if (!contextMenu.contains(e.target)) {
                    contextMenu.remove();
                    document.removeEventListener('click', closeMenu);
                }
            });
        }, 10);
    }

    // Tournament-specific context menu
    function showUserContextMenu(userId, userName, event) {
        event.preventDefault();
        event.stopPropagation();

        modernContext({
            "data": {
                "title": "Tournament Actions",
                "subtitle": userName
            },
            "recordId": userId,
            "items": [{
                    "label": "View Details",
                    "description": "View complete tournament profile",
                    "icon": "eye",
                    "action": function(id) {
                        viewRecord(id);
                    }
                },
                {
                    "label": "Edit Tournament",
                    "description": "Modify tournament information",
                    "icon": "edit",
                    "action": function(id) {
                        editRecord(id);
                    }
                },
                {
                    "label": "View Participants",
                    "description": "View and manage tournament participants",
                    "icon": "users",
                    "action": function(id) {
                        window.open(`${BASE_URL}/admin/participants/${id}`, '_blank');
                    }
                },
                {
                    "label": "View WHS Handicap Index",
                    "description": "View WHS handicap indexes for this tournament",
                    "icon": "eye",
                    "action": function(id) {
                        viewWhsHandicapIndexForTournament(id);
                    }
                },
                {
                    "label": "Import WHS Handicap Indexes",
                    "description": "Import WHS handicap index for this tournament",
                    "icon": "download",
                    "action": function(id) {
                        importWhsHandicapIndexes(id);
                    }
                },
                {
                    "label": "Migrate Scores from Excel",
                    "description": "Migrate scores from Excel file",
                    "icon": "file-excel",
                    "action": function(id) {
                        importTournaments(id);
                    }
                },
                {
                    "label": "---"
                },
                {
                    "label": "Delete Tournament",
                    "description": "Permanently remove tournament",
                    "icon": "trash",
                    "action": function(id) {
                        deleteRecord(id);
                    }
                }
            ]
        });
    }

    function viewRecord(id) {



        // Fetch tournament details from backend and show modal
        fetch(`${BASE_URL}/admin/participants/${id}?preview=1`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Failed to fetch tournament details');
                return response.json();
            })
            .then(data => {
                showTournamentPreview(data);
            })
            .catch(err => {
                alert('Could not load tournament details.');
            });


        // window.location.href = `/admin/tournaments/${id}`;
    }

    function editRecord(id) {
        window.location.href = `/admin/tournaments/${id}/edit`;
    }

    function viewWhsHandicapIndexForTournament(id) {
        // Navigate to WHS handicap indexes view, optionally filtered by tournament
        window.location.href = BASE_URL + `/admin/whs-handicap-indexes?tournament_id=${id}`;
    }

    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this tournament?')) {
            // Create a form and submit it for DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/tournaments/${id}`;

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
        console.log('Export tournaments functionality');
        // Implement export logic here
    }

    // Search functionality
    document.getElementById('tableSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#mainTableBody tr');
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

    // Enhanced context menu button functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add keyboard navigation for context menu buttons
        document.querySelectorAll('.btn-context-menu').forEach(button => {
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.click();
                }
            });

            // Add pulse effect on focus
            button.addEventListener('focus', function() {
                this.style.animation = 'pulse 0.5s ease-in-out';
            });

            button.addEventListener('blur', function() {
                this.style.animation = '';
            });
        });
    });

    // Add pulse animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);

    // Sort functionality
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const column = parseInt(this.dataset.column);
            const tbody = document.getElementById('mainTableBody');
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

    // Import WHS Handicap Indexes Functionality
    function importWhsHandicapIndexes(tournamentId) {
        const modalElement = document.getElementById('importWhsHandicapIndexesModal');
        
        // Store tournament ID in modal for later use
        modalElement.dataset.tournamentId = tournamentId;

        // Find tournament name from the table
        const tournamentRow = document.querySelector(`[onclick*="showUserContextMenu(${tournamentId}"]`);
        const tournamentName = tournamentRow ? tournamentRow.closest('tr').querySelector('.name-cell .user-name').textContent.trim() : 'Selected Tournament';

        // Update modal title to show which tournament
        document.getElementById('importWhsHandicapIndexesModalLabel').innerHTML = `
            <i class="fas fa-download me-2"></i>Import WHS Handicap Indexes
            <br><small class="text-muted" style="font-size: 0.85rem;">Tournament: ${tournamentName}</small>
        `;

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            // jQuery/Bootstrap 4
            $('#importWhsHandicapIndexesModal').modal('show');
        } else {
            // Fallback - manual modal display
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');

            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'whs-modal-backdrop';
            document.body.appendChild(backdrop);
        }

        // Reset form when modal opens
        resetWhsImportModal();
    }

    // Import Tournaments Functionality
    function importTournaments(tournamentId) {
        // Try Bootstrap 5 first, then fallback to jQuery/Bootstrap 4
        const modalElement = document.getElementById('importTournamentsModal');

        // Store tournament ID in modal for later use
        modalElement.dataset.tournamentId = tournamentId;

        // Find tournament name from the table
        const tournamentRow = document.querySelector(`[onclick*="showUserContextMenu(${tournamentId}"]`);
        const tournamentName = tournamentRow ? tournamentRow.closest('tr').querySelector('.name-cell .user-name').textContent.trim() : 'Selected Tournament';

        // Update modal title to show which tournament
        document.getElementById('importTournamentsModalLabel').innerHTML = `
            <i class="fas fa-upload me-2"></i>Migrate Scores from Excel
            <br><small class="text-muted" style="font-size: 0.85rem;">Tournament: ${tournamentName}</small>
        `;

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            // jQuery/Bootstrap 4
            $('#importTournamentsModal').modal('show');
        } else {
            // Fallback - manual modal display
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');

            // Add backdrop
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop';
            document.body.appendChild(backdrop);
        }

        // Reset form when modal opens
        resetImportModal();
    }

    function resetImportModal() {
        document.getElementById('importTournamentsForm').reset();
        document.getElementById('importProgress').style.display = 'none';
        document.getElementById('importResults').style.display = 'none';
        document.getElementById('importBtn').disabled = false;
        document.getElementById('importBtn').innerHTML = '<i class="fas fa-upload me-1"></i>Migrate Scores';
    }

    function startTournamentImport() {
        const form = document.getElementById('importTournamentsForm');
        const fileInput = document.getElementById('import_file');
        const importBtn = document.getElementById('importBtn');
        const progressSection = document.getElementById('importProgress');
        const resultsSection = document.getElementById('importResults');
        const modalElement = document.getElementById('importTournamentsModal');
        const tournamentId = modalElement.dataset.tournamentId;

        // Validate file selection
        if (!fileInput.files[0]) {
            alert('Please select a file to import.');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('import_file', fileInput.files[0]);
        formData.append('tournament_id', tournamentId);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Update UI to show progress
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Migrating...';
        progressSection.style.display = 'block';
        resultsSection.style.display = 'none';

        // Simulate progress (since we don't have real-time progress from backend)
        simulateTournamentProgress();

        // Make the import request
        fetch(BASE_URL + '/admin/scores/migrate', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                handleTournamentImportResponse(data);
            })
            .catch(error => {
                console.error('Import error:', error);
                handleTournamentImportError(error);
            });
    }

    function simulateTournamentProgress() {
        const progressBar = document.getElementById('importProgressBar');
        const progressText = document.getElementById('importProgressText');
        let progress = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90; // Cap at 90% until real response

            progressBar.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';
        }, 200);

        // Store interval ID to clear it later
        window.importProgressInterval = interval;
    }

    function handleTournamentImportResponse(data) {
        // Clear progress simulation
        if (window.importProgressInterval) {
            clearInterval(window.importProgressInterval);
        }

        // Complete progress bar
        const progressBar = document.getElementById('importProgressBar');
        const progressText = document.getElementById('importProgressText');
        progressBar.style.width = '100%';
        progressText.textContent = '100%';

        // Show results
        const resultsSection = document.getElementById('importResults');
        const importBtn = document.getElementById('importBtn');

        if (data.success) {
            resultsSection.innerHTML = `
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-check-circle me-1"></i>Migration Successful!
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    <small>Successfully migrated ${data.imported} scores.</small>
                    ${data.errors && data.errors.length > 0 ? 
                        `<div class="mt-2">
                            <strong>Warnings/Skipped rows:</strong>
                            <ul class="mb-0 small mt-1">
                                ${data.errors.slice(0, 5).map(error => `<li>${error}</li>`).join('')}
                                ${data.errors.length > 5 ? `<li><em>... and ${data.errors.length - 5} more</em></li>` : ''}
                            </ul>
                        </div>` : ''
                    }
                </div>
            `;

            // Update button
            importBtn.innerHTML = '<i class="fas fa-check me-1"></i>Migration Complete';
            importBtn.classList.remove('btn-primary');
            importBtn.classList.add('btn-success');

            // Refresh page after delay to show new tournaments
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } else {
            resultsSection.innerHTML = `
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-1"></i>Migration Failed
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    ${data.errors && data.errors.length > 0 ? 
                        `<div class="mt-2">
                            <strong>Errors found:</strong>
                            <ul class="mb-0 small mt-1">
                                ${data.errors.slice(0, 10).map(error => `<li>${error}</li>`).join('')}
                                ${data.errors.length > 10 ? `<li><em>... and ${data.errors.length - 10} more</em></li>` : ''}
                            </ul>
                        </div>` : ''
                    }
                </div>
            `;

            // Reset button
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="fas fa-upload me-1"></i>Try Again';
        }

        resultsSection.style.display = 'block';
    }

    function handleTournamentImportError(error) {
        // Clear progress simulation
        if (window.importProgressInterval) {
            clearInterval(window.importProgressInterval);
        }

        const resultsSection = document.getElementById('importResults');
        const importBtn = document.getElementById('importBtn');

        resultsSection.innerHTML = `
            <div class="alert alert-danger">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-1"></i>Migration Error
                </h6>
                <p class="mb-0">An unexpected error occurred during migration. Please try again.</p>
            </div>
        `;

        resultsSection.style.display = 'block';
        importBtn.disabled = false;
        importBtn.innerHTML = '<i class="fas fa-upload me-1"></i>Try Again';
    }

    function downloadTournamentSampleFile() {
        // Create sample CSV data
        const sampleData = [
            ['account_no', 'name', 'adjusted_gross_score', 'slope_rating', 'course_rating', 'holes_completed', 'date_played', 'tee_id', 'course_id'],
            ['ACC001', 'John Doe', '85', '130', '72.5', '18', '2025-01-15', '1', '1'],
            ['ACC002', 'Jane Smith', '78', '130', '72.5', '18', '2025-01-15', '1', '1'],
            ['ACC003', 'Bob Johnson', '42', '130', '72.5', 'F9', '2025-01-16', '1', '1']
        ];

        // Convert to CSV
        const csvContent = sampleData.map(row => row.join(',')).join('\n');

        // Create and trigger download
        const blob = new Blob([csvContent], {
            type: 'text/csv'
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'scores_import_sample.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // WHS Handicap Indexes Import Functions
    function resetWhsImportModal() {
        document.getElementById('importWhsHandicapIndexesForm').reset();
        document.getElementById('whsImportProgress').style.display = 'none';
        document.getElementById('whsImportResults').style.display = 'none';
        document.getElementById('whsImportBtn').disabled = false;
        document.getElementById('whsImportBtn').innerHTML = '<i class="fas fa-download me-1"></i>Import Handicaps';
    }

    function startWhsHandicapImport() {
        const form = document.getElementById('importWhsHandicapIndexesForm');
        const fileInput = document.getElementById('whs_import_file');
        const importBtn = document.getElementById('whsImportBtn');
        const progressSection = document.getElementById('whsImportProgress');
        const resultsSection = document.getElementById('whsImportResults');
        const modalElement = document.getElementById('importWhsHandicapIndexesModal');
        const tournamentId = modalElement.dataset.tournamentId;

        // Validate file selection
        if (!fileInput.files[0]) {
            alert('Please select a file to import.');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('whs_import_file', fileInput.files[0]);
        formData.append('tournament_id', tournamentId);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Update UI to show progress
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Importing...';
        progressSection.style.display = 'block';
        resultsSection.style.display = 'none';

        // Simulate progress (since we don't have real-time progress from backend)
        simulateWhsProgress();

        // Make the import request
        fetch(BASE_URL + '/admin/whs-handicap-imports/import', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                handleWhsHandicapImportResponse(data);
            })
            .catch(error => {
                console.error('WHS Import error:', error);
                handleWhsHandicapImportError(error);
            });
    }

    function simulateWhsProgress() {
        const progressBar = document.getElementById('whsImportProgressBar');
        const progressText = document.getElementById('whsImportProgressText');
        let progress = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90; // Cap at 90% until real response

            progressBar.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';
        }, 200);

        // Store interval ID to clear it later
        window.whsImportProgressInterval = interval;
    }

    function handleWhsHandicapImportResponse(data) {
        // Clear progress simulation
        if (window.whsImportProgressInterval) {
            clearInterval(window.whsImportProgressInterval);
        }

        // Complete progress bar
        const progressBar = document.getElementById('whsImportProgressBar');
        const progressText = document.getElementById('whsImportProgressText');
        progressBar.style.width = '100%';
        progressText.textContent = '100%';

        // Show results
        const resultsSection = document.getElementById('whsImportResults');
        const importBtn = document.getElementById('whsImportBtn');

        if (data.success) {
            resultsSection.innerHTML = `
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-check-circle me-1"></i>Import Successful!
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    <small>Successfully imported ${data.imported} WHS handicap indexes.</small>
                    ${data.errors && data.errors.length > 0 ? 
                        `<div class="mt-2">
                            <strong>Warnings/Skipped rows:</strong>
                            <ul class="mb-0 small mt-1">
                                ${data.errors.slice(0, 5).map(error => `<li>${error}</li>`).join('')}
                                ${data.errors.length > 5 ? `<li><em>... and ${data.errors.length - 5} more</em></li>` : ''}
                            </ul>
                        </div>` : ''
                    }
                </div>
            `;

            // Update button
            importBtn.innerHTML = '<i class="fas fa-check me-1"></i>Import Complete';
            importBtn.classList.remove('btn-primary');
            importBtn.classList.add('btn-success');

            // Refresh page after delay to show new data
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } else {
            resultsSection.innerHTML = `
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-1"></i>Import Failed
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    ${data.errors && data.errors.length > 0 ? 
                        `<div class="mt-2">
                            <strong>Errors found:</strong>
                            <ul class="mb-0 small mt-1">
                                ${data.errors.slice(0, 10).map(error => `<li>${error}</li>`).join('')}
                                ${data.errors.length > 10 ? `<li><em>... and ${data.errors.length - 10} more</em></li>` : ''}
                            </ul>
                        </div>` : ''
                    }
                </div>
            `;

            // Reset button
            importBtn.disabled = false;
            importBtn.innerHTML = '<i class="fas fa-download me-1"></i>Try Again';
        }

        resultsSection.style.display = 'block';
    }

    function handleWhsHandicapImportError(error) {
        // Clear progress simulation
        if (window.whsImportProgressInterval) {
            clearInterval(window.whsImportProgressInterval);
        }

        const resultsSection = document.getElementById('whsImportResults');
        const importBtn = document.getElementById('whsImportBtn');

        resultsSection.innerHTML = `
            <div class="alert alert-danger">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-1"></i>Import Error
                </h6>
                <p class="mb-0">An unexpected error occurred during import. Please try again.</p>
            </div>
        `;

        resultsSection.style.display = 'block';
        importBtn.disabled = false;
        importBtn.innerHTML = '<i class="fas fa-download me-1"></i>Try Again';
    }

    function downloadWhsHandicapSampleFile() {
        // Create sample CSV data for WHS handicap indexes
        const sampleData = [
            ['whs_no', 'whs_handicap_index', 'name', 'sex'],
            ['12345', '12.5', 'John Doe', 'M'],
            ['34567', '8.3', 'Jane Smith', 'F'],
            ['67890', '15.7', 'Bob Johnson', 'M']
        ];

        // Convert to CSV
        const csvContent = sampleData.map(row => row.join(',')).join('\n');

        // Create and trigger download
        const blob = new Blob([csvContent], {
            type: 'text/csv'
        });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'whs_handicap_indexes_sample.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Modal close functionality for fallback
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const backdrop = document.getElementById('modal-backdrop');

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            // jQuery/Bootstrap 4
            $(`#${modalId}`).modal('hide');
        } else {
            // Fallback - manual modal hide
            modal.style.display = 'none';
            modal.classList.remove('show');
            document.body.classList.remove('modal-open');
            if (backdrop) {
                backdrop.remove();
            }
        }
    }

    // Add event listeners for modal close buttons
    document.addEventListener('DOMContentLoaded', function() {
        // Close button event listeners
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
            button.addEventListener('click', function() {
                const modal = this.closest('.modal');
                if (modal) {
                    closeModal(modal.id);
                }
            });
        });

        // Close modal when clicking backdrop
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-backdrop')) {
                const openModal = document.querySelector('.modal.show');
                if (openModal) {
                    closeModal(openModal.id);
                }
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.querySelector('.modal.show');
                if (modal) {
                    closeModal(modal.id);
                }
            }
        });
    });
</script>

<style>
    /* Modern Header Card */
</style>
@endsection