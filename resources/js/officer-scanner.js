import { Html5QrcodeScanner } from "html5-qrcode";

document.addEventListener("DOMContentLoaded", function () {
    const scannerContainer = document.getElementById("reader");
    const qrInput = document.getElementById("qr_identifier");
    const participantNoInput = document.getElementById("participant_no");
    const autoSubmitCheckbox = document.getElementById("auto_submit_scan");
    const scanForm = document.getElementById("officer-scan-form");
    const statusBox = document.getElementById("scanner-status");
    const flashBox = document.getElementById("scan-flash");

    if (!scannerContainer || !qrInput || !scanForm) {
        return;
    }

    let lastScannedText = null;
    let lastScanAt = 0;

    const beep = (frequency = 880, duration = 120) => {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = ctx.createOscillator();
            const gain = ctx.createGain();

            oscillator.connect(gain);
            gain.connect(ctx.destination);

            oscillator.type = "sine";
            oscillator.frequency.value = frequency;
            gain.gain.value = 0.08;

            oscillator.start();

            setTimeout(() => {
                oscillator.stop();
                ctx.close();
            }, duration);
        } catch (e) {
            console.warn("Audio feedback unavailable");
        }
    };

    const flash = (type) => {
        if (!flashBox) return;

        flashBox.className = "";
        flashBox.classList.add("scan-flash", `scan-flash-${type}`);
        flashBox.style.display = "block";

        setTimeout(() => {
            flashBox.style.display = "none";
        }, 500);
    };

    const setStatus = (message, type = "info") => {
        if (!statusBox) return;

        statusBox.textContent = message;
        statusBox.classList.remove("scan-status-info", "scan-status-success", "scan-status-warning", "scan-status-error");
        statusBox.classList.add(`scan-status-${type}`);
    };

    const shouldIgnoreDuplicate = (text) => {
        const now = Date.now();

        if (lastScannedText === text && now - lastScanAt < 3000) {
            return true;
        }

        lastScannedText = text;
        lastScanAt = now;
        return false;
    };

    const clearManualFallback = () => {
        if (participantNoInput) {
            participantNoInput.value = "";
        }
    };

    const refocusScannerLoop = () => {
        setTimeout(() => {
            qrInput.focus();
            qrInput.select?.();
        }, 250);
    };

    const onScanSuccess = (decodedText) => {
        const value = String(decodedText || "").trim();

        if (!value) return;

        if (shouldIgnoreDuplicate(value)) {
            setStatus("Duplicate scan ignored.", "warning");
            flash("warning");
            beep(520, 100);
            return;
        }

        qrInput.value = value;
        clearManualFallback();
        setStatus("QR captured successfully. Ready to submit.", "success");
        flash("success");
        beep(900, 120);

        if (autoSubmitCheckbox && autoSubmitCheckbox.checked) {
            scanForm.submit();
        } else {
            refocusScannerLoop();
        }
    };

    const onScanFailure = () => {
        // stay quiet during background scan attempts
    };

    const scanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: { width: 260, height: 260 },
            rememberLastUsedCamera: true,
            supportedScanTypes: [0],
        },
        false
    );

    try {
        scanner.render(onScanSuccess, onScanFailure);
        setStatus("Scanner ready. Allow camera access to begin.", "info");
    } catch (error) {
        console.error(error);
        setStatus("Scanner could not start. Check browser camera permission.", "error");
        flash("error");
    }

    scanForm.addEventListener("submit", function () {
        setStatus("Submitting scan...", "info");
    });
});
