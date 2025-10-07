// Simple, reusable error modal controller
// Exposes window.showErrorModal({ message, details, primaryText, onPrimary })

(function () {
    function byId(id) {
        return document.getElementById(id);
    }

    function setText(el, text) {
        if (!el) return;
        el.textContent = text || '';
    }

    function toggle(el, show) {
        if (!el) return;
        el.classList.toggle('d-none', !show);
    }

    function init() {
        const modalEl = byId('errorModal');
        if (!modalEl) return;

        const modal = new bootstrap.Modal(modalEl, { backdrop: 'static' });
        const msgEl = byId('errorModalMessage');
        const detailsEl = byId('errorModalDetails');
        const primaryBtn = byId('errorModalPrimaryBtn');

        window.showErrorModal = function ({ message, details, primaryText, onPrimary } = {}) {
            setText(msgEl, message || 'Something went wrong.');
            setText(detailsEl, details || '');
            toggle(detailsEl, !!details);

            primaryBtn.textContent = primaryText || 'OK';
            primaryBtn.onclick = function () {
                if (typeof onPrimary === 'function') {
                    try { onPrimary(); } catch (e) { console.error(e); }
                }
                modal.hide();
            };

            modal.show();
        };

        // Optional: global unhandled error hook (comment out if not desired)
        window.addEventListener('error', function (e) {
            // Avoid spamming modal for trivial errors
            // showErrorModal({ message: 'A script error occurred', details: e.message });
        });

        window.addEventListener('unhandledrejection', function (e) {
            // showErrorModal({ message: 'An unexpected error occurred', details: String(e.reason) });
        });

        // Client-side validation: show modal on invalid submit
        document.addEventListener('submit', function (e) {
            const form = e.target;
            if (!(form instanceof HTMLFormElement)) return;

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                // Find first invalid field
                const firstInvalid = form.querySelector(':invalid');
                const label = firstInvalid && firstInvalid.id
                    ? (form.querySelector(`label[for="${firstInvalid.id}"]`)?.textContent || firstInvalid.name || 'This field')
                    : 'This field';

                const msg = firstInvalid?.validationMessage || 'Please correct the highlighted errors.';

                window.showErrorModal({
                    message: `${label}: ${msg}`,
                    details: 'Please review your input and try again.',
                    primaryText: 'Got it',
                    onPrimary: () => firstInvalid && firstInvalid.focus()
                });

                form.classList.add('was-validated');
            }
        }, true);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
