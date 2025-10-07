document.addEventListener('DOMContentLoaded', () => {
    try {
        // Selectors and constants
        const RAW_SCORE_SELECTOR = '.score-input';
        const COMPUTED_SCORE_SELECTOR = '.score-input-display';
        const TOTAL_INPUT_SELECTOR = 'input[name="score[total]"]';
        const TOTAL_COMPUTED_SELECTOR = '.score-input-display-total';
        const X_STROKES = 2; // strokes to add to par when 'x' is entered

        // Cache nodes
        const rawInputs = Array.from(document.querySelectorAll(RAW_SCORE_SELECTOR));
        const computedInputs = Array.from(document.querySelectorAll(COMPUTED_SCORE_SELECTOR));
        const totalInput = document.querySelector(TOTAL_INPUT_SELECTOR);
        const totalComputed = document.querySelector(TOTAL_COMPUTED_SELECTOR);

        // Helpers
        const toInt = (v) => {
            const n = parseInt(v, 10);
            return Number.isFinite(n) ? n : null;
        };

        const getParForHole = (hole) => {
            const parEl = document.querySelector(`[data-hole="${hole}"]`);
            const val = parEl?.dataset?.parValue;
            const n = parseInt(val, 10);
            return Number.isFinite(n) ? n : null;
        };

        function computeScore(rawValue, hole) {
            if (rawValue == null) return null;
            const str = String(rawValue).trim();
            if (str === '') return null;
            if (str.toLowerCase() === 'x') {
                const par = getParForHole(hole) ?? 0;
                return par + X_STROKES;
            }
            return toInt(str);
        }

        function renderComputedScore(hole, rawValue) {
            try {
                const el = document.querySelector(`[data-score-input-display="${hole}"]`);
                if (!el) return; // if no computed display element, skip
                const computed = computeScore(rawValue, hole);
                el.value = computed != null ? computed : '';
            } catch (err) {
                console.error('renderComputedScore error', err);
                if (window.showErrorModal) {
                    window.showErrorModal({ message: 'Unable to update hole score', details: String(err) });
                }
            }
        }

        function renderGrossTotal() {
            try {
                let total = 0;
                let hasAny = false;

                // Prefer summing computedInputs if they exist; fallback to raw inputs
                const sourceEls = computedInputs.length > 0 ? computedInputs : rawInputs;

                sourceEls.forEach((el, idx) => {
                    let n = toInt(el.value);
                    if (n == null && sourceEls === rawInputs) {
                        // if summing raw and value is 'x', compute on the fly
                        const maybeX = String(el.value || '').trim().toLowerCase();
                        if (maybeX === 'x') {
                            const hole = idx + 1;
                            const par = getParForHole(hole) ?? 0;
                            n = par + X_STROKES;
                        }
                    }
                    if (n != null) {
                        total += n;
                        hasAny = true;
                    }
                });

                const display = hasAny ? total : '';
                if (totalInput) totalInput.value = display;
                if (totalComputed) totalComputed.value = display;
            } catch (err) {
                console.error('renderGrossTotal error', err);
            }
        }

        function onRawInput(e, index) {
            try {
                const input = e.currentTarget;
                const val = input.value;
                const hole = index + 1; // holes are 1-indexed

                // Auto-advance logic
                if (val.length === 1 && val === '1') {
                    // wait for second digit
                } else if (val.length === 2 || (val.length === 1 && val !== '1')) {
                    const next = rawInputs[index + 1];
                    if (next) next.focus();
                }

                renderComputedScore(hole, val);
                renderGrossTotal();
            } catch (err) {
                console.error('onRawInput error', err);
                if (window.showErrorModal) {
                    window.showErrorModal({ message: 'Score input error', details: String(err) });
                }
            }
        }

        // Bind inputs
        rawInputs.forEach((el, idx) => el.addEventListener('input', (e) => onRawInput(e, idx)));
    } catch (err) {
        console.error('handicapping.js init error', err);
        if (window.showErrorModal) {
            window.showErrorModal({ message: 'Initialization error', details: String(err) });
        }
    }
});
