@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <!-- Compact Modern Header Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="header-title">Players Management</h6>
                    <p class="header-subtitle">
                        <i class="fas fa-users me-2"></i>
                        Manage system players and their golf profiles
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-modern" onclick="exportUsers()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                    <button class="btn btn-outline-secondary btn-modern" onclick="importUsers()">
                        <i class="fas fa-upload me-1"></i>Import
                    </button>
                    <a href="{{ route('admin.players.create') }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-plus me-2"></i>Add New Player
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
                                        <span></i>Name</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>WHS No</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Account No</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Email</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Birthdate</span>
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
                                        <span>Status</span>
                                        <i class="fas fa-sort sort-icon"></i>
                                    </div>
                                </th>
                                <th class="sortable" data-column="7">
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
                            @foreach ($players as $player)
                            <tr class="table-row">
                                <td class="name-cell">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-3">
                                            @if($player->profile->avatar !== null)
                                            <img src="{{ $player->profile->avatar }}" alt="Avatar" class="avatar-img">
                                            @else
                                            <div class="avatar-placeholder">
                                                {{ strtoupper(
                                                    (isset($player->profile->first_name) ? substr($player->profile->first_name, 0, 1) : '') . 
                                                    (isset($player->profile->last_name) ? substr($player->profile->last_name, 0, 1) : '')
                                                ) ?: 'U' }}
                                            </div>
                                            @endif
                                            <!-- Status Indicator -->
                                            <div class="status-indicator {{ $player->active ? 'status-online' : 'status-offline' }}"
                                                title="{{ $player->active ? 'Active Player' : 'Inactive Player' }}">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="user-name">{{ ($player->profile->last_name ?? '') . ', ' . ($player->profile->first_name ?? '') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whs-no-cell">
                                    <span class="whs-no-text fw-semibold">{{ $player->player->whs_no ?? 'N/A' }}</span>
                                </td>
                                <td class="account-cell">
                                    <span class="account-number">
                                        {{ $player->player->account_no ?? 'Not Set' }}
                                    </span>
                                </td>
                                <td class="email-cell">
                                    <span class="user-email">{{ $player->email }}</span>
                                </td>
                                <td class="birthdate-cell">
                                    <span class="birthdate-text">{{ $player->profile->birthdate ? \Carbon\Carbon::parse($player->profile->birthdate)->format('M d, Y') : 'N/A' }}</span>
                                </td>
                                <td class="sex-cell">
                                    <span class="badge {{ $player->profile->sex === 'M' ? 'bg-primary' : 'bg-danger' }}">
                                        {{ $player->profile->sex === 'M' ? 'Male' : ($player->profile->sex === 'F' ? 'Female' : 'N/A') }}
                                    </span>
                                </td>
                                <td class="status-cell">
                                    @if ($player->active)
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
                                    <span class="cell-text-date">{{ \Carbon\Carbon::parse($player->created_at)->format('M d, Y') }}</span>
                                    <small class="cell-text-time d-block">{{ \Carbon\Carbon::parse($player->created_at)->format('g:i A') }}</small>
                                </td>
                                <td class="action-cell text-center">
                                    <div class="action-wrapper">
                                        <button class="btn btn-outline-info btn-sm btn-handicap-info"
                                            type="button"
                                            onclick="openHandicapModal({{ $player->player->player_profile_id }})"
                                            title="Handicap Info">
                                            <i class="fas fa-golf-ball"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-context-menu"
                                            type="button"
                                            onclick="showUserContextMenu({{ $player->id }}, '{{ ($player->profile->first_name ?? '') . ' ' . ($player->profile->last_name ?? '') }}', event)"
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

<!-- Handicap Info Modal -->
<div class="modal fade" id="handicapInfoModal" tabindex="-1" aria-labelledby="handicapInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            {{-- <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="handicapInfoModalLabel">
                    <i class="fas fa-golf-ball me-2 text-primary"></i>Handicap Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> --}}
            <div class="modal-body pt-2" id="handicapInfoBody">
                <div class="text-center text-muted py-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Loading handicap information...</p>
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

    // Player-specific context menu
    function showUserContextMenu(userId, userName, event) {
        event.preventDefault();
        event.stopPropagation();

        modernContext({
            "data": {
                "title": "Player Actions",
                "subtitle": userName
            },
            "recordId": userId,
            "items": [{
                    "label": "View Details",
                    "description": "View complete player profile",
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
                    "label": "Manage Profile",
                    "description": "Update golf profile settings",
                    "icon": "user-cog",
                    "action": function(id) {
                        window.location.href = `/admin/players/${id}/profile`;
                    }
                },
                {
                    "label": "View Handicap",
                    "description": "Check current handicap status",
                    "icon": "golf-ball",
                    "action": function(id) {
                        window.location.href = BASE_URL + `/admin/players/${id}/handicap`;
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

    // Open Handicap Info Modal
    function openHandicapModal(playerId) {
        const modalElement = document.getElementById('handicapInfoModal');
        const modalBody = document.getElementById('handicapInfoBody');
        
        // Clear old content and show loading state
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border" role="status" style="color: #6b8e4e; width: 2.5rem; height: 2.5rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 mb-0" style="color: #6b8e4e; font-weight: 500;">Loading handicap information...</p>
            </div>
        `;
        
        // Show modal
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $('#handicapInfoModal').modal('show');
        } else {
            modalElement.style.display = 'block';
            modalElement.classList.add('show');
            document.body.classList.add('modal-open');
        }

        // Fetch handicap info
        fetch(BASE_URL + `/admin/players/${playerId}/handicap`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const localIndex = data.local_handicap_index;
                const details = data.details || {};
                const profile = data.profile || {};
                const config = data.config || {};
                const recentScores = details.recent_scores || [];
                const selectedScores = details.selected_scores || [];
                const scoreDifferentials = details.score_differentials || [];
                const consideredDifferentials = details.considered_differentials || [];
                const method = details.method || 'N/A';
                const count = details.count || 0;
                const adjustment = details.adjustment || 0;
                
                // Get score period from config or from recent scores
                let periodDisplay = '';
                let scorePeriodStart = '';
                let scorePeriodEnd = '';
                
                if (config.score_date && config.score_date.start && config.score_date.end) {
                    scorePeriodStart = config.score_date.start;
                    scorePeriodEnd = config.score_date.end;
                    periodDisplay = `
                        <span class="badge-period">${scorePeriodStart}</span>
                        <span style="margin: 0 8px; color: #adb5bd;">to</span>
                        <span class="badge-period">${scorePeriodEnd}</span>
                    `;
                } else if (recentScores.length > 0) {
                    const recentStart = recentScores[recentScores.length - 1].date_played;
                    const recentEnd = recentScores[0].date_played;
                    periodDisplay = `
                        <span class="badge-period">${recentStart}</span>
                        <span style="margin: 0 8px; color: #adb5bd;">to</span>
                        <span class="badge-period">${recentEnd}</span>
                    `;
                } else {
                    periodDisplay = '<span style="color: #6c757d; font-style: italic;">No scores available</span>';
                }
                
                // Build formula label
                let methodLabel = '';
                if (method === 'LOWEST') {
                    methodLabel = `Lowest ${count} score differentials`;
                } else if (method === 'HIGHEST') {
                    methodLabel = `Highest ${count} score differentials`;
                } else if (method === 'AVERAGE_OF_LOWEST') {
                    methodLabel = `Average of lowest ${count} score differentials`;
                } else {
                    methodLabel = `${method} (${count} scores)`;
                }

                // Build considered differentials table
                let consideredTableHtml = '';

                // Build all recent scores table (reference only)
                let recentTableHtml = '';
                if (recentScores.length > 0) {
                    // Create a set of score IDs that are in considered differentials for quick lookup
                    const consideredScoreIds = new Set();
                    consideredDifferentials.forEach(diff => {
                        const scoreIds = diff.score_ids || [];
                        scoreIds.forEach(id => consideredScoreIds.add(id));
                    });


                    // Map score IDs to their corresponding considered differential
                    let convertedTo8Holes = {};
                    scoreDifferentials.forEach(diff => {
                        const scoreIds = diff.score_ids || [];  
                        scoreIds.forEach(id => {
                            convertedTo8Holes[id] = diff;
                        });
                    });


                    console.log('Converted to 8 Holes Mapping:', convertedTo8Holes);
                    

                    recentTableHtml = `
                        <div class="mt-2">
                            <h6 class="mb-2" style="color: #304c40; font-weight: 600; font-size: 0.85rem;">All Recent Scores (${recentScores.length})</h6>
                            <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                                <table class="table table-sm mb-0" style="border: 1px solid #d4e5d9; border-radius: 4px; overflow: hidden; font-size: 0.8rem;">
                                    <thead style="background: linear-gradient(135deg, #f0f5f2 0%, #e8ede8 100%); position: sticky; top: 0;">
                                        <tr>
                                            <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">Date</th>
                                            <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">Diff</th>
                                            <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">AGS</th>
                                            <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">H</th>
                                            <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">SR/CR</th>
                                            <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">Course</th>
                                           <th style="color: #304c40; font-weight: 600; border-bottom: 1px solid #d4e5d9; padding: 6px 6px; font-size: 0.75rem;">Tee</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${recentScores.map((score, index) => {
                                            const isConsidered = consideredScoreIds.has(score.score_id);
                                            const scoreMark = isConsidered ? ' <span style="color: #6b8e4e; font-weight: bold;">*</span>' : '';
                                            const rowBackground = isConsidered ? '#e8f3e6' : (index % 2 === 0 ? '#ffffff' : '#f9faf8');
                                            let scoreDiff = score.score_differential;


                                            if(convertedTo8Holes[score.score_id]){
                                                if(convertedTo8Holes[score.score_id].holes_played === 'converted'){
                                                    scoreDiff =  scoreDiff + '->' + convertedTo8Holes[score.score_id].score_differential;
                                                }
                                            }
                                            
                                            
                                            return `
                                                <tr style="background: ${rowBackground};">
                                                    <td style="color: #304c40; padding: 5px 6px; border-bottom: 1px solid #e8ede8; font-size: 0.75rem;">${score.date_played}</td>
                                                    <td style="color: #6b8e4e; font-weight: 600; padding: 5px 6px; border-bottom: 1px solid #e8ede8; font-size: 0.75rem;">${scoreDiff}</td>
                                                    <td style="color: #212529; padding: 5px 6px; border-bottom: 1px solid #e8ede8; font-size: 0.75rem;">${score.adjusted_gross_score}${scoreMark}</td>
                                                    <td style="color: #212529; padding: 5px 6px; border-bottom: 1px solid #e8ede8; text-align: center; font-size: 0.75rem;">${score.holes_played}</td>
                                                    <td style="color: #212529; padding: 5px 6px; border-bottom: 1px solid #e8ede8; font-size: 0.75rem;">${parseInt(score.slope_rating)}/${score.course_rating}</td>
                                                    <td style="color: #212529; padding: 5px 6px; border-bottom: 1px solid #e8ede8; font-size: 0.75rem;">${score.course_name}</td>
                                                    <td style="color: #212529; padding: 5px 6px; border-bottom: 1px solid #e8ede8; font-size: 0.75rem;">${score.tee_name}</td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                            </div>
                            <small style="color: #6c757d; margin-top: 4px; display: block;"><span style="color: #6b8e4e; font-weight: bold;">*</span> = Considered in calculation</small>
                        </div>
                    `;
                }

                modalBody.innerHTML = `
                    <style>
                        .handicap-card {
                            background: linear-gradient(135deg, #f5f7f4 0%, #ffffff 100%);
                            border: 1px solid #d4e5d9;
                            border-radius: 6px;
                            padding: 10px;
                            margin-bottom: 6px;
                            transition: all 0.3s ease;
                        }
                        .handicap-card:hover {
                            box-shadow: 0 2px 6px rgba(48, 76, 64, 0.08);
                            border-color: #6b8e4e;
                        }
                        .handicap-label {
                            font-size: 0.65rem;
                            color: #304c40;
                            text-transform: uppercase;
                            letter-spacing: 0.4px;
                            margin-bottom: 4px;
                            display: block;
                            font-weight: 600;
                        }
                        .handicap-value {
                            font-size: 1.5rem;
                            font-weight: 700;
                            color: #6b8e4e;
                            line-height: 1;
                        }
                        .info-row {
                            display: flex;
                            align-items: center;
                            padding: 6px 0;
                            border-bottom: 1px solid #e8ede8;
                            font-size: 0.85rem;
                        }
                        .info-row:last-child {
                            border-bottom: none;
                        }
                        .info-label {
                            font-weight: 600;
                            color: #304c40;
                            min-width: 100px;
                            font-size: 0.8rem;
                        }
                        .info-value {
                            color: #212529;
                            font-size: 0.8rem;
                        }
                        .badge-period {
                            background: #e8f3e6;
                            color: #304c40;
                            padding: 3px 8px;
                            border-radius: 16px;
                            font-size: 0.75rem;
                            font-weight: 500;
                        }
                        .player-info-header {
                            background: linear-gradient(135deg, #304c40 0%, #3d5c4f 100%);
                            color: white;
                            padding: 10px;
                            margin: -16px -16px 8px -16px;
                            border-radius: 6px 6px 0 0;
                        }
                        .player-info-header .player-name {
                            font-size: 1rem;
                            font-weight: 700;
                            margin-bottom: 6px;
                        }
                        .player-info-detail {
                            font-size: 0.75rem;
                            opacity: 0.95;
                            margin-bottom: 0;
                            display: flex;
                            flex-wrap: wrap;
                            gap: 12px;
                        }
                        .player-info-detail span {
                            opacity: 0.9;
                            margin-right: 0;
                            white-space: nowrap;
                        }
                    </style>
                    <div class="handicap-info-container">
                        <!-- Player Info Header -->
                        <div class="player-info-header">
                            <div class="player-name">
                                <i class="fas fa-user-circle me-2"></i>${profile.name || 'Player'}
                            </div>
                            <div class="player-info-detail">
                                <span><i class="fas fa-golf-ball me-1"></i>WHS: <strong>${profile.whs_no || 'N/A'}</strong></span>
                                <span><i class="fas fa-id-card me-1"></i>Acct: <strong>${profile.account_no || 'N/A'}</strong></span>
                            </div>
                        </div>

                        <!-- Primary Info Card -->
                        <div class="handicap-card">
                            <span class="handicap-label">Local Handicap Index</span>
                            <div class="handicap-value">${localIndex !== null ? parseFloat(localIndex).toFixed(2) : 'Pending'}</div>
                        </div>

                        <!-- Score Period (Always Visible) -->
                        <div class="handicap-card">
                            <div class="info-row">
                                <span class="info-label">Score Period</span>
                                <div class="info-value">
                                    ${periodDisplay}
                                </div>
                            </div>
                        </div>

                        <!-- Calculation Details -->
                        <div class="handicap-card">
                            <div class="info-row">
                                <span class="info-label">Method</span>
                                <div class="info-value">
                                    <code style="background: #f8f9fa; padding: 4px 6px; border-radius: 3px; font-size: 0.75rem;">${methodLabel}</code>
                                </div>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Adjustment</span>
                                <div class="info-value">${parseFloat(adjustment) >= 0 ? '+' : ''}${parseFloat(adjustment)}</div>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Scores Used</span>
                                <div class="info-value">${count} of ${recentScores.length}</div>
                            </div>
                        </div>

                        <!-- Considered Differentials Table -->
                        ${consideredTableHtml}

                        <!-- All Recent Scores Table -->
                        ${recentTableHtml}
                    </div>
                `;
            } else if (!data.success) {
                modalBody.innerHTML = `
                    <div class="alert alert-danger border-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Error</strong> - ${data.message || 'Failed to load handicap information.'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching handicap info:', error);
            modalBody.innerHTML = `
                <div class="alert alert-danger border-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Connection Error</strong> - Failed to load handicap information. Please try again.
                </div>
            `;
        });
    }

    function viewRecord(id) {
        window.location.href = `/admin/players/${id}`;
    }

    function editRecord(id) {
        window.location.href = `/admin/players/${id}/edit`;
    }

    function deleteRecord(id) {
        if (confirm('Are you sure you want to delete this player?')) {
            // Create a form and submit it for DELETE request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/players/${id}`;

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
        console.log('Export players functionality');
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
                // window.location.reload();
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