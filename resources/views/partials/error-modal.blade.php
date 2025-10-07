<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title d-flex align-items-center gap-2 m-0" id="errorModalLabel">
                    <i class="fas fa-triangle-exclamation"></i>
                    Error
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="d-flex align-items-start">
                    <div class="me-2 text-danger">
                        <i class="fas fa-circle-exclamation fa-lg"></i>
                    </div>
                    <div>
                        <p class="mb-1 fw-semibold" id="errorModalMessage">Something went wrong.</p>
                        <small class="text-muted d-none" id="errorModalDetails" style="white-space: pre-line;"></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2 border-0 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-light text-danger border-danger btn-sm" data-bs-dismiss="modal">Dismiss</button>
                <button type="button" class="btn btn-danger btn-sm" id="errorModalPrimaryBtn">OK</button>
            </div>
        </div>
    </div>
</div>