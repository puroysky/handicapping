/**
 * Reusable Table Filter Component - Server-Side with Client Rendering
 *
 * Usage:
 * const filter = new TableFilter({
 *     tableBodyId: 'mainTableBody',
 *     filterButtonId: 'filterButton',
 *     showingCountId: 'showing-count',
 *     totalCountId: 'total-count',
 *     filterUrl: '/admin/scores/filter',
 *     csrfToken: 'your-csrf-token',
 *     renderRow: function(item) { return '<tr>...</tr>'; },
 *     fields: [
 *         { value: 'field_name', label: 'Field Label', type: 'text', selector: '.cell-class span' },
 *         { value: 'date_field', label: 'Date Field', type: 'date', selector: '.date-cell .cell-text-date' },
 *         { value: 'dropdown_field', label: 'Dropdown Field', type: 'select', selector: '.dropdown-cell span' }
 *     ]
 * });
 */

class TableFilter {
    constructor(config) {
        this.config = {
            tableBodyId: config.tableBodyId || "mainTableBody",
            filterButtonId: config.filterButtonId || "filterButton",
            showingCountId: config.showingCountId || "showing-count",
            totalCountId: config.totalCountId || "total-count",
            modalId: config.modalId || "filterModal",
            filterUrl: config.filterUrl || null,
            csrfToken:
                config.csrfToken ||
                document.querySelector('meta[name="csrf-token"]')?.content,
            renderRow: config.renderRow || null,
            fields: config.fields || [],
        };

        this.activeFilters = [];
        this.init();
    }

    init() {
        this.createModal();
        this.attachEventListeners();
    }

    createModal() {
        // Check if modal already exists
        if (document.getElementById(this.config.modalId)) {
            return;
        }

        const modalHTML = `
        <div class="modal fade" id="${this.config.modalId}" tabindex="-1" aria-labelledby="${this.config.modalId}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background: linear-gradient(135deg, #304c40 0%, #6b8e4e 100%); color: white;">
                        <h5 class="modal-title" id="${this.config.modalId}Label">
                            <i class="fas fa-filter me-2"></i>Advanced Filter
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Active Filters Display -->
                        <div id="activeFiltersContainer" class="mb-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Active Filters</h6>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="tableFilterInstance.clearAllFilters()">
                                    <i class="fas fa-times me-1"></i>Clear All
                                </button>
                            </div>
                            <div id="activeFiltersList" class="d-flex flex-wrap gap-2"></div>
                            <hr>
                        </div>

                        <!-- Add New Filter Section -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-plus-circle me-2"></i>Add New Filter
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="filterField" class="form-label fw-semibold">Filter Field</label>
                                        <select class="form-select" id="filterField" onchange="tableFilterInstance.updateFilterInput()">
                                            <option value="">-- Select Field --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="filterValue" class="form-label fw-semibold">Filter Value</label>
                                        <input type="text" class="form-control" id="filterValue" placeholder="Enter filter value" disabled>
                                        <input type="date" class="form-control" id="filterValueDate" style="display: none;">
                                        <input type="number" class="form-control" id="filterValueNumber" style="display: none;">
                                        <select class="form-select" id="filterValueSelect" style="display: none;">
                                            <option value="">-- Select --</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary w-100" onclick="tableFilterInstance.addFilter()">
                                            <i class="fas fa-plus me-1"></i>Add Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Instructions -->
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>How to use:</strong> Select a field from the dropdown, enter the value you want to filter by, then click "Add Filter". You can add multiple filters.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Close
                        </button>
                        <button type="button" class="btn btn-success" onclick="tableFilterInstance.applyFilters()">
                            <i class="fas fa-check me-1"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
        `;

        document.body.insertAdjacentHTML("beforeend", modalHTML);
        this.populateFieldsDropdown();
    }

    populateFieldsDropdown() {
        const select = document.getElementById("filterField");
        select.innerHTML = '<option value="">-- Select Field --</option>';

        this.config.fields.forEach((field) => {
            const option = document.createElement("option");
            option.value = field.value;
            option.textContent = field.label;
            option.dataset.type = field.type;
            option.dataset.selector = field.selector;
            select.appendChild(option);
        });
    }

    attachEventListeners() {
        const filterButton = document.getElementById(
            this.config.filterButtonId
        );
        if (filterButton) {
            filterButton.addEventListener("click", () => this.openModal());
        }
    }

    openModal() {
        const modal = new bootstrap.Modal(
            document.getElementById(this.config.modalId)
        );
        modal.show();
    }

    updateFilterInput() {
        const fieldSelect = document.getElementById("filterField");
        const selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
        const fieldType = selectedOption.dataset.type;

        const textInput = document.getElementById("filterValue");
        const dateInput = document.getElementById("filterValueDate");
        const numberInput = document.getElementById("filterValueNumber");
        const selectInput = document.getElementById("filterValueSelect");

        // Hide all inputs first
        textInput.style.display = "none";
        dateInput.style.display = "none";
        numberInput.style.display = "none";
        selectInput.style.display = "none";
        textInput.disabled = true;
        dateInput.disabled = true;
        numberInput.disabled = true;
        selectInput.disabled = true;

        if (!fieldSelect.value) return;

        // Show appropriate input based on field type
        switch (fieldType) {
            case "date":
                dateInput.style.display = "block";
                dateInput.disabled = false;
                break;
            case "number":
                numberInput.style.display = "block";
                numberInput.disabled = false;
                numberInput.placeholder = `Enter ${selectedOption.textContent}`;
                break;
            case "select":
                selectInput.style.display = "block";
                selectInput.disabled = false;
                this.populateDropdown(
                    fieldSelect.value,
                    selectedOption.dataset.selector
                );
                break;
            default:
                textInput.style.display = "block";
                textInput.disabled = false;
                textInput.placeholder = `Enter ${selectedOption.textContent}`;
        }
    }

    populateDropdown(fieldValue, selector) {
        const selectInput = document.getElementById("filterValueSelect");
        selectInput.innerHTML = '<option value="">-- Select --</option>';

        // Get unique values from the table
        const values = new Set();
        const rows = document.querySelectorAll(
            `#${this.config.tableBodyId} tr`
        );

        rows.forEach((row) => {
            const element = row.querySelector(selector);
            const value = element?.textContent.trim();

            if (value && value !== "N/A" && value !== "") {
                values.add(value);
            }
        });

        // Sort and add to dropdown
        Array.from(values)
            .sort()
            .forEach((value) => {
                const option = document.createElement("option");
                option.value = value;
                option.textContent = value;
                selectInput.appendChild(option);
            });
    }

    addFilter() {
        const fieldSelect = document.getElementById("filterField");
        const selectedOption = fieldSelect.options[fieldSelect.selectedIndex];
        const field = fieldSelect.value;
        const fieldType = selectedOption.dataset.type;
        const selector = selectedOption.dataset.selector;

        if (!field) {
            alert("Please select a field to filter");
            return;
        }

        let value = "";
        switch (fieldType) {
            case "date":
                value = document.getElementById("filterValueDate").value;
                break;
            case "number":
                value = document.getElementById("filterValueNumber").value;
                break;
            case "select":
                value = document.getElementById("filterValueSelect").value;
                break;
            default:
                value = document.getElementById("filterValue").value;
        }

        if (!value) {
            alert("Please enter a value to filter");
            return;
        }

        // Check if filter already exists
        const existingFilter = this.activeFilters.find(
            (f) => f.field === field && f.value === value
        );
        if (existingFilter) {
            alert("This filter is already active");
            return;
        }

        // Add filter to active filters
        this.activeFilters.push({
            field: field,
            value: value,
            label: selectedOption.textContent,
            type: fieldType,
            selector: selector,
        });

        // Update UI
        this.updateActiveFiltersDisplay();

        // Reset inputs
        fieldSelect.value = "";
        document.getElementById("filterValue").value = "";
        document.getElementById("filterValueDate").value = "";
        document.getElementById("filterValueNumber").value = "";
        document.getElementById("filterValueSelect").value = "";
        this.updateFilterInput();
    }

    updateActiveFiltersDisplay() {
        const container = document.getElementById("activeFiltersContainer");
        const list = document.getElementById("activeFiltersList");

        if (this.activeFilters.length === 0) {
            container.style.display = "none";
            return;
        }

        container.style.display = "block";
        list.innerHTML = this.activeFilters
            .map(
                (filter, index) => `
            <span class="badge bg-primary d-flex align-items-center gap-2" style="font-size: 0.9rem; padding: 0.5rem 0.75rem;">
                <span><strong>${filter.label}:</strong> ${filter.value}</span>
                <button type="button" class="btn-close btn-close-white" style="font-size: 0.7rem;" onclick="tableFilterInstance.removeFilter(${index})" aria-label="Remove filter"></button>
            </span>
        `
            )
            .join("");

        // Update filter count badge
        this.updateFilterCountBadge();
    }

    updateFilterCountBadge() {
        const badge = document.getElementById("activeFilterCount");
        if (badge) {
            if (this.activeFilters.length > 0) {
                badge.textContent = this.activeFilters.length;
                badge.style.display = "inline-block";
            } else {
                badge.style.display = "none";
            }
        }
    }

    removeFilter(index) {
        this.activeFilters.splice(index, 1);
        this.updateActiveFiltersDisplay();
    }

    clearAllFilters() {
        if (confirm("Are you sure you want to clear all filters?")) {
            this.activeFilters = [];
            this.updateActiveFiltersDisplay();
            this.applyFilters();
        }
    }

    async applyFilters() {
        // Check if server-side filtering is configured
        if (!this.config.filterUrl) {
            console.error(
                "filterUrl is not configured. Server-side filtering requires filterUrl."
            );
            alert("Filter configuration error. Please contact support.");
            return;
        }

        if (!this.config.renderRow) {
            console.error(
                "renderRow function is not configured. Client-side rendering requires renderRow function."
            );
            alert("Filter configuration error. Please contact support.");
            return;
        }

        const tableBody = document.getElementById(this.config.tableBodyId);
        const originalContent = tableBody.innerHTML;

        // Show loading state
        tableBody.innerHTML = `
            <tr class="loading-row">
                <td colspan="100" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Applying filters...</p>
                </td>
            </tr>
        `;

        try {
            // Prepare filter data to send to server
            const filterData = this.activeFilters.map((filter) => ({
                field: filter.field,
                value: filter.value,
                type: filter.type,
            }));

            // Send filter data to server
            const response = await fetch(this.config.filterUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": this.config.csrfToken,
                    Accept: "application/json",
                },
                body: JSON.stringify({ filters: filterData }),
            });

            if (!response.ok) {
                throw new Error(`Server error: ${response.status}`);
            }

            const data = await response.json();

            // Check if server returned data
            if (!data.data || !Array.isArray(data.data)) {
                throw new Error("Invalid response format: missing data array");
            }

            // Render rows using client-side function
            tableBody.innerHTML = "";
            if (data.data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="100" class="text-center py-4 text-muted">
                            <i class="fas fa-search mb-2" style="font-size: 2rem;"></i>
                            <p>No records found matching your filters.</p>
                        </td>
                    </tr>
                `;
            } else {
                data.data.forEach((item) => {
                    tableBody.innerHTML += this.config.renderRow(item);
                });
            }

            // Update counts
            const showingCount = document.getElementById(
                this.config.showingCountId
            );
            if (showingCount && data.count !== undefined) {
                showingCount.textContent = data.count;
            }

            // Close modal
            const modal = bootstrap.Modal.getInstance(
                document.getElementById(this.config.modalId)
            );
            if (modal) {
                modal.hide();
            }

            // Log result
            console.log(
                `Applied ${this.activeFilters.length} filter(s). Showing ${
                    data.count || 0
                } records.`
            );
        } catch (error) {
            console.error("Filter error:", error);

            // Restore original content on error
            tableBody.innerHTML = originalContent;

            // Show error message
            alert(
                "Failed to apply filters. Please try again or contact support."
            );
        }
    }

    resetTable() {
        // For server-side, reload without filters
        if (this.config.filterUrl) {
            window.location.reload();
        } else {
            // Fallback for client-side
            const rows = document.querySelectorAll(
                `#${this.config.tableBodyId} tr`
            );
            rows.forEach((row) => {
                row.style.display = "";
            });

            const showingCount = document.getElementById(
                this.config.showingCountId
            );
            const totalCount = document.getElementById(
                this.config.totalCountId
            );
            if (showingCount && totalCount) {
                showingCount.textContent = totalCount.textContent;
            }
        }
    }

    getActiveFilters() {
        return this.activeFilters;
    }

    setFilters(filters) {
        this.activeFilters = filters;
        this.updateActiveFiltersDisplay();
    }
}

// Global variable to store the instance
let tableFilterInstance = null;
