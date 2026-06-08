@extends('layouts.admin-cabang')

@section('title', 'Scan Reservasi')

@section('content')
    <div class="page-header mb-3">
        <h2 class="page-title">
            Scan Reservasi
        </h2>
    </div>

    <div class="row gx-2 gy-3">
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 p-sm-3">
                    <div class="ratio ratio-16x9 ratio-md-4x3 rounded overflow-hidden bg-dark position-relative">
                        <video id="scannerVideo" class="w-100 h-100 object-cover" autoplay muted playsinline></video>
                        <div id="scannerOverlay"
                            class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white text-center p-2">
                            <div>
                                <i class="bi bi-qr-code-scan fs-2"></i>
                                <div class="mt-1 small fw-semibold">
                                    Kamera belum aktif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-1 mt-2">
                        <button type="button" id="startScanner" class="btn btn-primary btn-sm flex-fill flex-md-grow-0">
                            <i class="bi bi-camera-video me-1"></i>
                            Buka
                        </button>
                        <button type="button" id="stopScanner" class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0" disabled>
                            <i class="bi bi-camera-video-off me-1"></i>
                            Tutup
                        </button>
                        <button type="button" id="resetScanner" class="btn btn-outline-primary btn-sm flex-fill flex-md-grow-0">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </button>
                    </div>

                    <div id="scannerStatus" class="alert alert-info mt-2 mb-0 py-2 small">
                        Siap memindai QR reservasi.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm mb-2">
                <div class="card-body p-2 p-sm-3">
                    <h3 class="card-title mb-2">
                        Input Kode
                    </h3>

                    <form id="manualScanForm" class="d-flex gap-1">
                        <input type="text" id="manualCode" class="form-control form-control-sm" placeholder="RSV-XXXXXX"
                            autocomplete="off">
                        <button type="submit" class="btn btn-primary btn-sm">
                            Proses
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-2 p-sm-3">
                    <h3 class="card-title mb-2">
                        Hasil Scan
                    </h3>

                    <div id="scanResult" class="text-secondary small overflow-hidden">
                        Belum ada QR yang diproses.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- jsQR library: fallback untuk browser yang belum support BarcodeDetector --}}
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

    <script>
        const video = document.getElementById('scannerVideo');
        const overlay = document.getElementById('scannerOverlay');
        const startButton = document.getElementById('startScanner');
        const stopButton = document.getElementById('stopScanner');
        const resetButton = document.getElementById('resetScanner');
        const statusBox = document.getElementById('scannerStatus');
        const resultBox = document.getElementById('scanResult');
        const manualForm = document.getElementById('manualScanForm');
        const manualCode = document.getElementById('manualCode');

        // Hidden canvas untuk capture frame video (dipakai jsQR fallback)
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d', { willReadFrequently: true });

        let stream = null;
        let nativeDetector = null;   // BarcodeDetector (jika tersedia)
        let useNativeDetector = false;
        let scanning = false;
        let processing = false;
        let animFrameId = null;
        let processedCodes = new Set();

        function setStatus(message, type = 'info') {
            statusBox.className = `alert alert-${type} mt-3 mb-0`;
            statusBox.textContent = message;
        }

        function normalizeCode(rawValue) {
            const value = String(rawValue || '').trim();
            const match = value.match(/RSV-[A-Z0-9]+/i);

            return (match ? match[0] : value).toUpperCase();
        }

        function escapeHtml(value) {
            return String(value || '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function renderResult(response) {
            const data = response.data || {};
            const alertType = response.success ? 'success' : 'warning';

            resultBox.innerHTML = `
                <div class="alert alert-${alertType} mb-3 small">
                    ${escapeHtml(response.message)}
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-0" style="width: 40%">Kode</th>
                                <td class="fw-semibold">: ${escapeHtml(data.kode_reservasi || '-')}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Customer</th>
                                <td>: ${escapeHtml(data.nama_customer || '-')}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Meja</th>
                                <td>: ${escapeHtml(data.nomor_meja || '-')}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Jadwal</th>
                                <td>: ${escapeHtml(data.tanggal_booking || '-')} ${escapeHtml(data.jam_mulai || '')}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Status</th>
                                <td>: ${escapeHtml(data.status || '-')}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function processCode(rawCode) {
            const code = normalizeCode(rawCode);

            if (!code || processing) {
                return;
            }

            if (processedCodes.has(code)) {
                setStatus(`${code} sudah diproses. Tekan Scan Ulang untuk memproses kode yang sama lagi.`, 'secondary');
                return;
            }

            processing = true;
            setStatus(`Memproses ${code}...`, 'info');

            try {
                const response = await fetch('{{ route('admin-cabang.reservasi.scan.process') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        kode_reservasi: code,
                    }),
                });

                const payload = await response.json();
                processedCodes.add(code);
                renderResult(payload);
                setStatus(payload.message, payload.success ? 'success' : 'warning');
            } catch (error) {
                setStatus('Gagal memproses kode reservasi.', 'danger');
            } finally {
                processing = false;
            }
        }

        // ── Scan frame: coba native BarcodeDetector dulu, fallback ke jsQR ──
        async function scanFrame() {
            if (!scanning) {
                return;
            }

            try {
                let foundCode = null;

                if (useNativeDetector && nativeDetector) {
                    // ─ Native BarcodeDetector ─
                    const codes = await nativeDetector.detect(video);
                    if (codes.length > 0) {
                        foundCode = codes[0].rawValue;
                    }
                } else if (typeof jsQR === 'function') {
                    // ─ jsQR fallback ─
                    if (video.readyState === video.HAVE_ENOUGH_DATA) {
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                        const qr = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: 'dontInvert',
                        });

                        if (qr && qr.data) {
                            foundCode = qr.data;
                        }
                    }
                }

                if (foundCode) {
                    const code = normalizeCode(foundCode);
                    if (code) {
                        await processCode(code);
                    }
                }
            } catch (error) {
                // Jangan tampilkan warning terus-menerus
                console.warn('Scan frame error:', error);
            }

            animFrameId = requestAnimationFrame(scanFrame);
        }

        // ── Cek apakah secure context (HTTPS / localhost) ──
        function isSecureContext() {
            if (window.isSecureContext) return true;
            const host = location.hostname;
            return host === 'localhost' || host === '127.0.0.1' || host === '[::1]';
        }

        async function startScanner() {
            // Cek secure context dulu
            if (!isSecureContext()) {
                setStatus(
                    'Kamera membutuhkan akses HTTPS atau localhost. ' +
                    'Buka halaman via http://localhost atau tambahkan HTTPS. ' +
                    'Atau gunakan input kode manual di samping.',
                    'warning'
                );
                // Tetap lanjut mencoba, karena beberapa browser mengizinkan
            }

            // Cek ketersediaan getUserMedia
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                setStatus(
                    'Browser tidak mendukung akses kamera. Gunakan Chrome/Edge/Firefox terbaru, ' +
                    'atau gunakan input kode manual.',
                    'danger'
                );
                return;
            }

            // Tentukan metode decode: native BarcodeDetector atau jsQR
            if ('BarcodeDetector' in window) {
                try {
                    nativeDetector = new BarcodeDetector({ formats: ['qr_code'] });
                    useNativeDetector = true;
                } catch (e) {
                    useNativeDetector = false;
                }
            }

            if (!useNativeDetector && typeof jsQR !== 'function') {
                setStatus(
                    'Library scanner QR gagal dimuat. Periksa koneksi internet dan refresh halaman.',
                    'danger'
                );
                return;
            }

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: 1280 },
                        height: { ideal: 720 },
                    },
                    audio: false,
                });

                video.srcObject = stream;
                await video.play();

                scanning = true;
                overlay.classList.add('d-none');
                startButton.disabled = true;
                stopButton.disabled = false;

                const method = useNativeDetector ? 'BarcodeDetector' : 'jsQR';
                setStatus(`Kamera aktif (${method}). Arahkan ke QR reservasi.`, 'info');

                animFrameId = requestAnimationFrame(scanFrame);
            } catch (error) {
                let msg = 'Kamera tidak bisa dibuka. ';

                if (error.name === 'NotAllowedError') {
                    msg += 'Izin kamera ditolak. Klik ikon 🔒 di address bar → izinkan kamera → refresh halaman.';
                } else if (error.name === 'NotFoundError') {
                    msg += 'Tidak ada kamera yang terdeteksi pada perangkat ini.';
                } else if (error.name === 'NotReadableError') {
                    msg += 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi lain dan coba lagi.';
                } else if (error.name === 'OverconstrainedError') {
                    msg += 'Kamera tidak mendukung resolusi yang diminta. Coba lagi.';
                } else {
                    msg += 'Pastikan izin kamera aktif dan halaman diakses via HTTPS/localhost.';
                }

                setStatus(msg, 'danger');
            }
        }

        function stopScanner() {
            scanning = false;

            if (animFrameId) {
                cancelAnimationFrame(animFrameId);
                animFrameId = null;
            }

            if (stream) {
                stream.getTracks().forEach((track) => track.stop());
                stream = null;
            }

            video.srcObject = null;
            overlay.classList.remove('d-none');
            startButton.disabled = false;
            stopButton.disabled = true;
            setStatus('Kamera ditutup.', 'secondary');
        }

        startButton.addEventListener('click', startScanner);
        stopButton.addEventListener('click', stopScanner);
        resetButton.addEventListener('click', () => {
            processedCodes.clear();
            setStatus('Scanner siap memproses kode lagi.', 'info');
        });

        manualForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            await processCode(manualCode.value);
            manualCode.value = '';
        });
    </script>
@endpush
