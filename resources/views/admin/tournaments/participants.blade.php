@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <!-- Compact Modern Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="header-title">{{ $tournament->tournament_name }} Participant Management</h6>
                    <p class="header-subtitle">
                        <i class="fas fa-users me-2"></i>
                        Manage tournament participants and handicaps    
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" onclick="exportPlayers()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button class="btn btn-outline-secondary btn-modern" onclick="importParticipants()">
                        <i class="fas fa-upload me-1"></i>Import
                    </button>
                    <button class="btn btn-primary btn-modern" onclick="openAddParticipantsModal()">
                        <i class="fas fa-plus me-2"></i>Add Players
                    </button>
                    <!-- Calculations Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-modern dropdown-toggle" type="button" id="calculationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-calculator me-2"></i>Calculate
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="calculationsDropdown">
                            <li><a class="dropdown-item" href="#" onclick="calculateLocalHandicap(event)"><i class="fas fa-chart-line me-2"></i>Calculate Local Handicap</a></li>
                            <li><a class="dropdown-item" href="#" onclick="calculateTournamentHandicap(event)"><i class="fas fa-trophy me-2"></i>Calculate Tournament Handicap</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="calculateCourseHandicap(event)"><i class="fas fa-golf me-2"></i>Calculate Course Handicap</a></li>
                            <li><a class="dropdown-item" href="#" onclick="recalculateAll(event)"><i class="fas fa-sync me-2"></i>Recalculate All</a></li>
                        </ul>
                    </div>
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
                                <input type="text" class="search-input" id="tableSearch" placeholder="Search players..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="table-info">
                                <small class="text-muted">
                                    Showing <span id="showing-count">{{ count($players) }}</span> of <span id="total-count">{{ count($players) }}</span> players
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
                                        <span>Player Name</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Account No</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>WHS No</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Local HI</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Tournament HI</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="5">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>WHS HI</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="6">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Course Handicap</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <!-- Removed Tee and Course columns per request -->
                                <th class="text-center">
                                    <i class="fas fa-cogs me-2"></i>Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody id="mainTableBody">
                            @foreach ($players as $player)
                            <tr class="table-row">
                                <td class="player-name-cell">
                                    <span class="fw-bold" style="color: #2F4A3C;">{{ $player->user->profile->first_name ?? '' }} {{ $player->user->profile->last_name ?? 'N/A' }}</span>
                                </td>
                                <td class="account-no-cell">
                                    <span class="fw-semibold">{{ $player->user->player->account_no ?? 'N/A' }}</span>
                                </td>
                                <td class="whs-no-cell">
                                    <span class="fw-semibold">{{ $player->user->player->whs_no ?? 'N/A' }}</span>
                                </td>
                                <td class="local-handicap-index-cell">
                                    <span class="fw-semibold">{{ $player->final_local_handicap_index ?? 'N/A' }}</span>
                                </td>
                                <td class="tournament-handicap-index-cell">
                                    <span class="text-muted" style="font-size: 0.9rem;">{{ $player->tournament_handicap_index ?? 'N/A' }}</span>
                                </td>
                                <td class="whs-handicap-cell">
                                    <span class="text-muted" style="font-size: 0.9rem;">{{ $player->final_whs_handicap_index ?? '-' }}</span>
                                </td>
                                <td class="course-handicap-cell">
                                    @if($player->playerCourseHandicaps && count($player->playerCourseHandicaps) > 0)
                                        <div class="course-handicap-list">
                                            @foreach ($player->playerCourseHandicaps as $handicap)
                                                <div class="handicap-item mb-1">
                                                    <span class="badge bg-secondary me-1" style="font-size: 0.75rem;">{{ $handicap->course->course_code ?? 'N/A' }}</span>
                                                    <span class="badge bg-info me-1" style="font-size: 0.75rem;">{{ $handicap->tee->tee_code ?? 'N/A' }}</span>
                                                    <span class="fw-bold text-primary" style="font-size: 0.9rem;">{{ $handicap->course_handicap ?? 'N/A' }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">No handicaps assigned</span>
                                    @endif
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-wrapper">
                                        <button class="btn btn-outline-secondary btn-context-menu"
                                            type="button"
                                            onclick="showPlayerContextMenu({{ $player->player_profile_id }}, '{{ $player->user->profile->first_name ?? '' }} {{ $player->user->profile->last_name ?? '' }}', event)"
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

<!-- Add Participants Modal with Select2 -->
<div class="modal fade" id="addParticipantsModal" tabindex="-1" aria-labelledby="addParticipantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addParticipantsModalLabel">
                    <i class="fas fa-users-plus me-2"></i>Add Players to Tournament
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addParticipantsForm">
                    @csrf
                    <input type="hidden" id="tournament_id_hidden" value="{{ $tournament->tournament_id }}">
                    
                    <!-- Player Selection with Select2 -->
                    <div class="mb-4">
                        <label for="playe_select" class="form-label fw-bold">
                            <i class="fas fa-user-check me-1"></i>Select Players
                        </label>
                        <select id="playe_select" 
                            name="participants[]" 
                            class="form-select select2-tags" 
                            multiple="multiple"
                            style="width: 100%">
                            <option></option>

                        </select>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Select one or more players to add to this tournament
                        </div>
                    </div>

                    <!-- Display selected count -->
                    <div id="participantCount" class="alert alert-info mb-3" style="display: none;">
                        <small>
                            <i class="fas fa-check-circle me-1"></i>
                            <strong id="countText">0</strong> player(s) selected
                        </small>
                    </div>

                    <!-- Progress Bar (hidden initially) -->
                    <div id="addProgress" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Adding players...</small>
                            <small class="text-muted" id="addProgressText">0%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar"
                                style="width: 0%"
                                id="addProgressBar"></div>
                        </div>
                    </div>

                    <!-- Results Section (hidden initially) -->
                    <div id="addResults" style="display: none;"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="startAddParticipants()" id="addBtn">
                    <i class="fas fa-user-plus me-1"></i>Add Selected Players
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import Participants Modal -->
<div class="modal fade" id="importParticipantsModal" tabindex="-1" aria-labelledby="importParticipantsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importParticipantsModalLabel">
                    <i class="fas fa-upload me-2"></i>Import Players
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="importPlayersForm" enctype="multipart/form-data">
                    @csrf
                    <!-- File Upload Section -->
                    <div class="mb-4">
                        <label for="import_file" class="form-label fw-bold">
                            <i class="fas fa-file-excel me-1"></i>Select Import File
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
                            <div class="col-md-12">
                                <ul class="mb-0 small">
                                    <li><strong>whs_no</strong> - WHS Number (string or numeric)</li>
                                    <li><strong>handicap_index</strong> - Handicap Index (decimal)</li>
                                    <li><strong>north_tee</strong> - North Tee (string or code)</li>
                                    <li><strong>south_tee</strong> - South Tee (string or code)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Format -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="downloadSampleFile()">
                            <i class="fas fa-download me-1"></i>Download Sample Format
                        </button>
                    </div>

                    <!-- Progress Bar (hidden initially) -->
                    <div id="importProgress" class="mb-3" style="display: none;">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-muted">Importing players...</small>
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
                <button type="button" class="btn btn-primary" onclick="startImport()" id="importBtn">
                    <i class="fas fa-upload me-1"></i>Import Players
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Custom Action Functions --}}
<script>
    // Notification Helper Function
    function showNotification(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.setAttribute('role', 'alert');
        alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
        
        const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
        
        alertDiv.innerHTML = `
            <i class="fas fa-${icon} me-2"></i>
            <strong>${type === 'success' ? 'Success!' : type === 'error' ? 'Error!' : 'Info'}</strong>
            <div>${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

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

    // Player-specific context menu
    function showPlayerContextMenu(playerId, playerName, event) {
        event.preventDefault();
        event.stopPropagation();

        modernContext({
            "data": {
                "title": "Player Actions",
                "subtitle": playerName
            },
            "recordId": playerId,
            "items": [{
                    "label": "View Details",
                    "description": "View complete player information",
                    "icon": "eye",
                    "action": function(id) {
                        viewRecord(id);
                    }
                },
                {
                    "label": "Edit Player",
                    "description": "Modify player information",
                    "icon": "edit",
                    "action": function(id) {
                        editRecord(id);
                    }
                },
                {
                    "label": "---"
                },
                {
                    "label": "Delete Player",
                    "description": "Permanently remove player",
                    "icon": "trash",
                    "action": function(id) {
                        deleteRecord(id);
                    }
                }
            ]
        });
    }

    function viewRecord(id) {
        window.location.href = `/admin/participants/${id}`;
    }

    function editRecord(id) {
        window.location.href = `/admin/participants/${id}/edit`;
    }

    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this player?')) {
            // Create a form and submit it for DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/participants/${id}`;

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

    function exportPlayers() {
        console.log('Export players functionality');
        // Implement export logic here
    }

    function importPlayers() {
        console.log('Import players functionality');
        // Implement import logic here
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

    // Import Players Functionality
    function importParticipants() {
        // Try Bootstrap 5 first, then fallback to jQuery/Bootstrap 4
        const modalElement = document.getElementById('importParticipantsModal');

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            // jQuery/Bootstrap 4
            $('#importParticipantsModal').modal('show');
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

    // Add Participants Modal (with Select2)
    function openAddParticipantsModal() {
        const modalElement = document.getElementById('addParticipantsModal');

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#addParticipantsModal').modal('show');
        } else {
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
            const backdrop = document.createElement('div');
            backdrop.className = 'modal-backdrop fade show';
            backdrop.id = 'modal-backdrop';
            document.body.appendChild(backdrop);
        }

        // Initialize Select2 if not already done
        if (!$('#playe_select').hasClass('select2-hidden-accessible')) {
            initializeSelect2();
        }

        resetAddParticipantsModal();
    }

    // Initialize Select2
    function initializeSelect2() {
        // Sample data for demonstration
        const sampleData = [
            { player_profile_id: 1, first_name: 'John', last_name: 'Doe', account_no: '0001-00' },
            { player_profile_id: 2, first_name: 'Jane', last_name: 'Smith', account_no: '0001-01' },
            { player_profile_id: 3, first_name: 'Robert', last_name: 'Johnson', account_no: '0001-02' },
            { player_profile_id: 4, first_name: 'Maria', last_name: 'Garcia', account_no: '0001-03' },
            { player_profile_id: 5, first_name: 'Michael', last_name: 'Brown', account_no: '0001-04' },
            { player_profile_id: 6, first_name: 'Sarah', last_name: 'Davis', account_no: '0001-05' },
            { player_profile_id: 7, first_name: 'James', last_name: 'Wilson', account_no: '0001-06' },
            { player_profile_id: 8, first_name: 'Emily', last_name: 'Martinez', account_no: '0001-07' },
            { player_profile_id: 9, first_name: 'David', last_name: 'Anderson', account_no: '0001-08' },
            { player_profile_id: 10, first_name: 'Lisa', last_name: 'Taylor', account_no: '0001-09' }
        ];

        const select = $('#playe_select');
        select.empty();
        select.append('<option></option>');

        // First try to fetch from backend, fallback to sample data
        fetch(BASE_URL + '/admin/players/available', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const players = (data.success && data.players && data.players.length > 0) ? data.players : sampleData;
            
            players.forEach(player => {
                select.append(`
                    <option value="${player.player_profile_id}">
                        ${player.first_name} ${player.last_name} (${player.account_no || 'N/A'})
                    </option>
                `);
            });

            initializeSelect2Widget();
        })
        .catch(error => {
            console.error('Error fetching players:', error);
            // Fallback to sample data
            sampleData.forEach(player => {
                select.append(`
                    <option value="${player.player_profile_id}">
                        ${player.first_name} ${player.last_name} (${player.account_no || 'N/A'})
                    </option>
                `);
            });
            initializeSelect2Widget();
        });
    }

    // Initialize Select2 widget
    function initializeSelect2Widget() {
        const select = $('#playe_select');
        
        // Initialize Select2 with Bootstrap theme
        select.select2({
            theme: 'bootstrap-5',
            placeholder: 'Search and select players...',
            allowClear: true,
            width: '100%',
            containerCssClass: 'select2-container--bootstrap-5',
            dropdownCssClass: 'select2-dropdown--bootstrap-5',
            language: {
                noResults: function() {
                    return 'No players found';
                }
            }
        });

        // Update count when selection changes
        select.on('change', function() {
            const count = $(this).val() ? $(this).val().length : 0;
            const countDiv = document.getElementById('participantCount');
            const countText = document.getElementById('countText');
            
            if (count > 0) {
                countText.textContent = count;
                countDiv.style.display = 'block';
            } else {
                countDiv.style.display = 'none';
            }
        });
    }

    function resetAddParticipantsModal() {
        document.getElementById('addParticipantsForm').reset();
        document.getElementById('addProgress').style.display = 'none';
        document.getElementById('addResults').style.display = 'none';
        document.getElementById('participantCount').style.display = 'none';
        document.getElementById('addBtn').disabled = false;
        document.getElementById('addBtn').innerHTML = '<i class="fas fa-user-plus me-1"></i>Add Selected Players';
        
        // Reset Select2
        $('#playe_select').val(null).trigger('change');
    }

    function startAddParticipants() {
        const selectedIds = $('#playe_select').val();
        const addBtn = document.getElementById('addBtn');
        const progressSection = document.getElementById('addProgress');
        const resultsSection = document.getElementById('addResults');
        const tournamentId = document.getElementById('tournament_id_hidden').value;

        // Validate selection
        if (!selectedIds || selectedIds.length === 0) {
            alert('Please select at least one player.');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        selectedIds.forEach(id => {
            formData.append('participant_ids[]', id);
        });
        formData.append('tournament_id', tournamentId);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        // Update UI
        addBtn.disabled = true;
        addBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';
        progressSection.style.display = 'block';
        resultsSection.style.display = 'none';

        // Simulate progress
        simulateAddProgress();

        // Make request
        fetch(BASE_URL + '/admin/participants/add-bulk', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            handleAddResponse(data);
        })
        .catch(error => {
            console.error('Add error:', error);
            handleAddError(error);
        });
    }

    function simulateAddProgress() {
        const progressBar = document.getElementById('addProgressBar');
        const progressText = document.getElementById('addProgressText');
        let progress = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90;

            progressBar.style.width = progress + '%';
            progressText.textContent = Math.round(progress) + '%';
        }, 200);

        window.addProgressInterval = interval;
    }

    function handleAddResponse(data) {
        // Clear progress simulation
        if (window.addProgressInterval) {
            clearInterval(window.addProgressInterval);
        }

        // Complete progress bar
        const progressBar = document.getElementById('addProgressBar');
        const progressText = document.getElementById('addProgressText');
        progressBar.style.width = '100%';
        progressText.textContent = '100%';

        // Show results
        const resultsSection = document.getElementById('addResults');
        const addBtn = document.getElementById('addBtn');

        if (data.success) {
            resultsSection.innerHTML = `
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-check-circle me-1"></i>Players Added Successfully!
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    <small>Successfully added ${data.added} player(s) to the tournament.</small>
                    ${data.errors && data.errors.length > 0 ? 
                        `<div class="mt-2">
                            <strong>Warnings/Already Added:</strong>
                            <ul class="mb-0 small mt-1">
                                ${data.errors.slice(0, 5).map(error => `<li>${error}</li>`).join('')}
                                ${data.errors.length > 5 ? `<li><em>... and ${data.errors.length - 5} more</em></li>` : ''}
                            </ul>
                        </div>` : ''
                    }
                </div>
            `;

            // Update button
            addBtn.innerHTML = '<i class="fas fa-check me-1"></i>Players Added';
            addBtn.classList.remove('btn-primary');
            addBtn.classList.add('btn-success');

            // Refresh page
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            resultsSection.innerHTML = `
                <div class="alert alert-danger">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-triangle me-1"></i>Error Adding Players
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    ${data.errors && data.errors.length > 0 ? 
                        `<div class="mt-2">
                            <strong>Details:</strong>
                            <ul class="mb-0 small mt-1">
                                ${data.errors.slice(0, 10).map(error => `<li>${error}</li>`).join('')}
                                ${data.errors.length > 10 ? `<li><em>... and ${data.errors.length - 10} more</em></li>` : ''}
                            </ul>
                        </div>` : ''
                    }
                </div>
            `;

            addBtn.disabled = false;
            addBtn.innerHTML = '<i class="fas fa-user-plus me-1"></i>Try Again';
        }

        resultsSection.style.display = 'block';
    }

    function handleAddError(error) {
        if (window.addProgressInterval) {
            clearInterval(window.addProgressInterval);
        }

        const resultsSection = document.getElementById('addResults');
        const addBtn = document.getElementById('addBtn');

        resultsSection.innerHTML = `
            <div class="alert alert-danger">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-1"></i>Error
                </h6>
                <p class="mb-0">An unexpected error occurred. Please try again.</p>
            </div>
        `;

        resultsSection.style.display = 'block';
        addBtn.disabled = false;
        addBtn.innerHTML = '<i class="fas fa-user-plus me-1"></i>Try Again';
    }

    function resetImportModal() {
        document.getElementById('importPlayersForm').reset();
        document.getElementById('importProgress').style.display = 'none';
        document.getElementById('importResults').style.display = 'none';
        document.getElementById('importBtn').disabled = false;
        document.getElementById('importBtn').innerHTML = '<i class="fas fa-upload me-1"></i>Import Players';
    }

    function startImport() {
        const form = document.getElementById('importPlayersForm');
        const fileInput = document.getElementById('import_file');
        const importBtn = document.getElementById('importBtn');
        const progressSection = document.getElementById('importProgress');
        const resultsSection = document.getElementById('importResults');

        // Validate file selection
        if (!fileInput.files[0]) {
            alert('Please select a file to import.');
            return;
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('import_file', fileInput.files[0]);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        //tournament_id
        formData.append('tournament_id', {{ $tournament->tournament_id }});

        // Update UI to show progress
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Importing...';
        progressSection.style.display = 'block';
        resultsSection.style.display = 'none';

        // Simulate progress (since we don't have real-time progress from backend)
        simulateProgress();

        // Make the import request
        fetch(BASE_URL + '/admin/participants/import', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                handleImportResponse(data);
            })
            .catch(error => {
                console.error('Import error:', error);
                handleImportError(error);
            });
    }

    function simulateProgress() {
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

    function handleImportResponse(data) {
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
                        <i class="fas fa-check-circle me-1"></i>Import Successful!
                    </h6>
                    <p class="mb-1">${data.message}</p>
                    <small>Successfully imported ${data.imported} players.</small>
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

            // Refresh page after delay to show new players
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
            importBtn.innerHTML = '<i class="fas fa-upload me-1"></i>Try Again';
        }

        resultsSection.style.display = 'block';
    }

    function handleImportError(error) {
        // Clear progress simulation
        if (window.importProgressInterval) {
            clearInterval(window.importProgressInterval);
        }

        const resultsSection = document.getElementById('importResults');
        const importBtn = document.getElementById('importBtn');

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
        importBtn.innerHTML = '<i class="fas fa-upload me-1"></i>Try Again';
    }

    function downloadSampleFile() {
        // Create sample CSV data
        const sampleData = [
            ['account_no', 'whs_handicap_index', 'north_tee', 'south_tee'],
            ['0001-00', '8.2', 'BLUE', 'GOLD'],
            ['0001-02', '15.0', 'WHITE', 'BLUE']
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
        a.download = 'tournament_players_import_sample.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Export Users Function (placeholder)
    function exportPlayers() {
        alert('Export functionality coming soon!');
    }

    // Calculate Local Handicap
    function calculateLocalHandicap(event) {
        event.preventDefault();
        const tournamentId = {{ $tournamentId }};
        
        // Show loading indicator
        const btn = event.target.closest('.dropdown-item');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Calculating...';
        btn.disabled = true;
        
        // Make background API call
        fetch(BASE_URL + `/admin/participant/calculate-handicap`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                tournament_id: tournamentId,
                type: 'local'
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Local Handicap calculation completed:', data);
            
            // Show success message
            if (data.success) {
                showNotification('Local Handicap calculated successfully for ' + data.count + ' participants', 'success');
                // Refresh the page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showNotification(data.message || 'An error occurred during calculation', 'error');
            }
        })
        .catch(error => {
            console.error('Error calculating local handicap:', error);
            showNotification('Error calculating local handicap. Please try again.', 'error');
        })
        .finally(() => {
            // Restore button state
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    // Calculate Tournament Handicap
    function calculateTournamentHandicap(event) {
        event.preventDefault();
        console.log('Calculating Tournament Handicap for all participants...');
        alert('Tournament Handicap calculation initiated. Please wait...');
        // TODO: Implement API call to calculate tournament handicaps
    }

    // Calculate Course Handicap
    function calculateCourseHandicap(event) {
        event.preventDefault();
        console.log('Calculating Course Handicap for all participants...');
        alert('Course Handicap calculation initiated. Please wait...');
        // TODO: Implement API call to calculate course handicaps
    }

    // Recalculate All Handicaps
    function recalculateAll(event) {
        event.preventDefault();
        if (confirm('This will recalculate all handicaps for this tournament. Continue?')) {
            console.log('Recalculating all handicaps...');
            alert('All handicaps recalculation initiated. Please wait...');
            // TODO: Implement API call to recalculate all handicaps
        }
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
        // Pre-populate Select2 field with sample data on page load
        // This will be called when modal opens via openAddParticipantsModal()
        
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
                closeModal('importParticipantsModal');
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
    
    /* Course Handicap Column Improvements */
    .course-handicap-list {
        max-height: 120px;
        overflow-y: auto;
        padding: 2px;
    }
    
    .handicap-item {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 4px;
        padding: 2px 0;
        border-bottom: 1px solid #f8f9fa;
    }
    
    .handicap-item:last-child {
        border-bottom: none;
    }
    
    .course-handicap-cell .badge {
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .course-handicap-cell .badge.bg-secondary {
        background-color: #6c757d !important;
    }
    
    .course-handicap-cell .badge.bg-info {
        background-color: #0dcaf0 !important;
    }
    
    .course-handicap-cell .fw-bold {
        min-width: 30px;
        text-align: center;
        font-size: 1rem !important;
        font-weight: 600 !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .course-handicap-list {
            max-height: 80px;
        }
        
        .handicap-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .course-handicap-cell .badge {
            font-size: 0.65rem !important;
        }
        
        .course-handicap-cell .fw-bold {
            font-size: 0.85rem !important;
        }
    }

    /* Select2 Customization */
    .select2-container--bootstrap-5 .select2-selection {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        min-height: 38px;
        padding: 0.375rem 0.75rem;
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .select2-container--bootstrap-5 .select2-selection__rendered {
        padding: 0;
        line-height: 1.5;
    }

    .select2-container--bootstrap-5 .select2-selection__choice {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        padding: 0.25rem 0.5rem;
        margin: 0.1rem 0.125rem;
        border-radius: 0.25rem;
    }

    .select2-container--bootstrap-5 .select2-selection__choice__remove {
        color: white;
        margin-right: 0.25rem;
    }

    .select2-container--bootstrap-5 .select2-selection__arrow {
        height: 36px;
    }

    .select2-container--bootstrap-5 .select2-dropdown {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }

    .select2-results__option--highlighted {
        background-color: #0d6efd !important;
    }

    .select2-results__option {
        padding: 0.5rem 1rem;
    }
</style>
@endsection