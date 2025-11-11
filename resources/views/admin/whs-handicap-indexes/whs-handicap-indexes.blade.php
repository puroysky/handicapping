@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <!-- Compact Modern Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="header-title">WHS Handicap Imports</h6>
                    <p class="header-subtitle">
                        <i class="fas fa-download me-2"></i>
                        Manage WHS handicap index imports
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" onclick="exportWHSImports()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <a href="{{ route('admin.tournaments.index') }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-arrow-left me-2"></i>Back to Tournaments
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
                                <input type="text" class="search-input" id="tableSearch" placeholder="Search WHS imports..." autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="table-info">
                                <small class="text-muted">
                                    Showing <span id="showing-count">{{ count($whsHandicapIndexes ?? []) }}</span> of <span id="total-count">{{ count($whsHandicapIndexes ?? []) }}</span> imports
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
                                        <span>WHS No</span>
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
                                        <span>Name</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>WHS Handicap Index</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Adjusted WHS Index</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="5">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Sex</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="6">
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
                            @foreach ($whsHandicapIndexes ?? [] as $whsHandicapIndex)
                            <tr class="table-row">
                                <td class="whs-no-cell">
                                    <span class="fw-semibold">{{ $whsHandicapIndex->whs_no ?? '-' }}</span>
                                </td>
                                <td class="account-no-cell">
                                    <span class="text-muted">{{ $whsHandicapIndex->account_no ?? '-' }}</span>
                                </td>
                                <td class="name-cell">
                                    @if($whsHandicapIndex->account_no === null)
                                    <span class="text-danger font-weight-bold">{{ $whsHandicapIndex->name ?? '' }} </span>
                                    @else
                                    <span>{{ $whsHandicapIndex->first_name ?? '' }} {{ $whsHandicapIndex->last_name ?? '' }}</span>
                                    @endif
                                </td>
                                <td class="whs-handicap-cell">
                                    <span class="text-center">{{ $whsHandicapIndex->whs_handicap_index ?? 'N/A' }}</span>
                                </td>
                                <td class="adjusted-whs-cell">
                                    <span class="text-center">{{ $whsHandicapIndex->adjusted_whs_handicap_index ?? 'N/A' }}</span>
                                </td>
                                <td class="sex-cell">
                                    <span class="text-center">{{ $whsHandicapIndex->sex ?? 'N/A' }}</span>
                                </td>
                                <td class="date-cell">
                                    <span class="cell-text-date">{{ \Carbon\Carbon::parse($whsHandicapIndex->created_at)->format('M d, Y') }}</span>
                                    <small class="cell-text-time d-block">{{ \Carbon\Carbon::parse($whsHandicapIndex->created_at)->format('g:i A') }}</small>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-wrapper">
                                        <button class="btn btn-outline-secondary btn-context-menu"
                                            type="button"
                                            onclick="showWhsImportContextMenu({{ $whsHandicapIndex->whs_handicap_import_id }}, '{{ $whsHandicapIndex->account_no ?? 'Record' }}', event)"
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

<!-- Import Players Modal -->
<div class="modal fade" id="importPlayersModal" tabindex="-1" aria-labelledby="importPlayersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importPlayersModalLabel">
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
                            <div class="col-md-6">
                                <ul class="mb-0 small">
                                    <li><strong>whs_no</strong> - WHS Number (numeric)</li>
                                    <li><strong>account_no</strong> - Account Number (string)</li>
                                    <li><strong>first_name</strong> - First Name</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0 small">
                                    <li><strong>last_name</strong> - Last Name</li>
                                    <li><strong>birthdate</strong> - Birth Date (YYYY-MM-DD)</li>
                                    <li><strong>sex</strong> - Gender (M/F or MALE/FEMALE)</li>
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

    // WHS Import-specific context menu
    function showWhsImportContextMenu(importId, fileName, event) {
        event.preventDefault();
        event.stopPropagation();

        modernContext({
            "data": {
                "title": "WHS Import Actions",
                "subtitle": fileName
            },
            "recordId": importId,
            "items": [{
                    "label": "View Details",
                    "description": "View import details",
                    "icon": "eye",
                    "action": function(id) {
                        viewWhsImportRecord(id);
                    }
                },
                {
                    "label": "Download File",
                    "description": "Download the import file",
                    "icon": "download",
                    "action": function(id) {
                        downloadWhsImportFile(id);
                    }
                },
                {
                    "label": "---"
                },
                {
                    "label": "Delete Import",
                    "description": "Remove this import record",
                    "icon": "trash",
                    "action": function(id) {
                        deleteWhsImportRecord(id);
                    }
                }
            ]
        });
    }

    function viewWhsImportRecord(id) {
        // Redirect to view page
        window.location.href = `/admin/whs-handicap-indexes/${id}`;
    }

    function downloadWhsImportFile(id) {
        window.location.href = `/admin/whs-handicap-indexes/${id}/download`;
    }

    function deleteWhsImportRecord(id) {
        if (confirm('Are you sure you want to delete this WHS import record?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/whs-handicap-indexes/${id}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();
        }
    }

    function exportWHSImports() {
        console.log('Export WHS imports functionality');
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

    // Import Players Functionality
    function importUsers() {
        // Try Bootstrap 5 first, then fallback to jQuery/Bootstrap 4
        const modalElement = document.getElementById('importPlayersModal');

        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            // Bootstrap 5
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            // jQuery/Bootstrap 4
            $('#importPlayersModal').modal('show');
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

        // Update UI to show progress
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Importing...';
        progressSection.style.display = 'block';
        resultsSection.style.display = 'none';

        // Simulate progress (since we don't have real-time progress from backend)
        simulateProgress();

        // Make the import request
        fetch(BASE_URL + '/admin/players/import', {
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
            ['whs_no', 'account_no', 'first_name', 'last_name', 'birthdate', 'sex'],
            ['12345', 'ACC001', 'John', 'Doe', '1990-01-15', 'M'],
            ['67890', 'ACC002', 'Jane', 'Smith', '1985-05-20', 'F'],
            ['11111', 'ACC003', 'Bob', 'Johnson', '1992-12-10', 'MALE']
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
        a.download = 'players_import_sample.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Export Users Function (placeholder)
    function exportUsers() {
        alert('Export functionality coming soon!');
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
                closeModal('importPlayersModal');
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