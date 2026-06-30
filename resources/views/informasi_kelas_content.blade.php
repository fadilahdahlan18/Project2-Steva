<style>
    .faq-wrapper {
        display: flex;
        flex-direction: column;
        gap: 30px;
        font-family: 'Inter', sans-serif;
    }

    /* Section Headers */
    .faq-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #800020;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 2px solid #f9e8ec;
        padding-bottom: 8px;
    }

    .faq-section-title i {
        color: #c9a84c;
    }

    /* Grid for Class Types */
    .class-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .class-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .class-info-card {
        background: #ffffff;
        border: 1.5px solid #e9ecef;
        border-radius: 12px;
        padding: 24px;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .class-info-card:hover {
        transform: translateY(-4px);
        border-color: #800020;
        box-shadow: 0 8px 24px rgba(128, 0, 32, 0.08);
    }

    .class-card-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
    }

    .class-icon-wrapper {
        width: 48px;
        height: 48px;
        background: #f9e8ec;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #800020;
        flex-shrink: 0;
    }

    .class-info-card:hover .class-icon-wrapper {
        background: #800020;
        color: #ffffff;
        transition: all 0.25s ease;
    }

    .class-title {
        font-size: 16px;
        font-weight: 700;
        color: #212529;
    }

    .class-schedule-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff8e8;
        color: #856404;
        border: 1px solid #ffe082;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 4px;
        text-transform: uppercase;
    }

    .class-description {
        font-size: 13.5px;
        color: #495057;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .class-features-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 13px;
        color: #495057;
    }

    .class-features-list li {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .class-features-list li i {
        color: #28a745;
        font-size: 11px;
    }

    /* Levels/Categories Cards */
    .level-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    @media (max-width: 768px) {
        .level-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .level-card {
        background: #ffffff;
        border: 1.5px solid #e9ecef;
        border-radius: 12px;
        padding: 20px;
        transition: all 0.25s ease;
        border-top: 4px solid var(--border-color, #adb5bd);
    }

    .level-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    }

    .level-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 12px;
        background: var(--bg-color, #e9ecef);
        color: var(--text-color, #495057);
    }

    .level-card h4 {
        font-size: 15px;
        font-weight: 700;
        color: #212529;
        margin-bottom: 8px;
    }

    .level-desc {
        font-size: 12.5px;
        color: #6c757d;
        line-height: 1.5;
    }

    /* Accordion FAQ */
    .faq-accordion {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .faq-item {
        border: 1.5px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
        transition: all 0.22s ease;
    }

    .faq-header {
        width: 100%;
        padding: 16px 20px;
        background: #ffffff;
        border: none;
        text-align: left;
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        transition: background 0.22s ease, color 0.22s ease;
        font-family: 'Inter', sans-serif;
    }

    .faq-header:hover {
        background: #f9e8ec;
        color: #800020;
    }

    .faq-header i {
        font-size: 12px;
        transition: transform 0.25s ease;
        color: #adb5bd;
    }

    .faq-header.active {
        color: #800020;
        font-weight: 700;
    }

    .faq-header.active i {
        transform: rotate(180deg);
        color: #800020;
    }

    .faq-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.25s cubic-bezier(0, 1, 0, 1);
        background: #fff;
    }

    .faq-content-inner {
        padding: 16px 20px;
        font-size: 13.5px;
        color: #495057;
        line-height: 1.6;
        border-top: 1px solid #f1f3f5;
        background: #fdfafb;
    }
</style>

<div class="faq-wrapper">
    <!-- Section 1: Jenis Kelas -->
    <div>
        <h3 class="faq-section-title">
            <i class="fa-solid fa-graduation-cap"></i> Jenis Pilihan Kelas Latihan
        </h3>
        <div class="class-info-grid">
            <!-- Tari Rampak -->
            <div class="class-info-card">
                <div>
                    <div class="class-card-header">
                        <div class="class-icon-wrapper">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <div>
                            <span class="class-title">Kelas Tari Rampak (Kelompok)</span>
                            <div>
                                <span class="class-schedule-badge">
                                    <i class="fa-regular fa-calendar-days"></i> Setiap Jumat
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="class-description">
                        Latihan tari tradisional berkelompok yang menitikberatkan pada keselarasan tempo, sinkronisasi gerakan ritmis, dan keharmonisan formasi panggung. Kelas ini sangat penting untuk membangun kedisiplinan gerak bersama.
                    </p>
                </div>
                <div>
                    <ul class="class-features-list">
                        <li><i class="fa-solid fa-check"></i> <span>Fokus pada sinkronisasi & presisi gerak kelompok</span></li>
                        <li><i class="fa-solid fa-check"></i> <span>Latihan ketukan dinamis & formasi panggung</span></li>
                        <li><i class="fa-solid fa-check"></i> <span>Jadwal: <strong>Hari Jumat (14:00 - 17:00 WIB)</strong></span></li>
                    </ul>
                </div>
            </div>

            <!-- Tari Reguler -->
            <div class="class-info-card">
                <div>
                    <div class="class-card-header">
                        <div class="class-icon-wrapper">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div>
                            <span class="class-title">Kelas Tari Reguler (Individu)</span>
                            <div>
                                <span class="class-schedule-badge" style="background:#e8f6f9; color:#0c5460; border-color:#bee5eb;">
                                    <i class="fa-regular fa-calendar-days"></i> Sabtu & Minggu
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="class-description">
                        Kelas berfokus pada pengembangan dasar teknik tari secara individual, kelenturan tubuh, olah rasa (penjiwaan karakter), dan detail keluwesan gerak tangan, mata, serta ekspresi wajah dari murid secara privat/mandiri.
                    </p>
                </div>
                <div>
                    <ul class="class-features-list">
                        <li><i class="fa-solid fa-check"></i> <span>Fokus pada teknik detail, kelenturan, & keluwesan tari</span></li>
                        <li><i class="fa-solid fa-check"></i> <span>Olah rasa, ekspresi wajah, dan penjiwaan tari individu</span></li>
                        <li><i class="fa-solid fa-check"></i> <span>Jadwal: <strong>Hari Sabtu & Minggu (14:00 - 15:00 WIB)</strong></span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Kategori Tingkatan -->
    <div>
        <h3 class="faq-section-title">
            <i class="fa-solid fa-layer-group"></i> Tingkatan Kategori Belajar
        </h3>
        <div class="level-info-grid">
            <!-- Pemula -->
            <div class="level-card" style="--border-color: #28a745;">
                <span class="level-badge" style="--bg-color: #d4edda; --text-color: #155724;">Pemula</span>
                <h4>Tingkat Dasar</h4>
                <p class="level-desc">
                    Pengenalan postur tubuh dasar menari Sunda/Subang, sinkronisasi ketukan lambat, dan pengenalan pola gerak dasar tangan dan kaki. Diperuntukkan bagi pemula tanpa riwayat tari sebelumnya.
                </p>
            </div>

            <!-- Madya -->
            <div class="level-card" style="--border-color: #ffc107;">
                <span class="level-badge" style="--bg-color: #fff3cd; --text-color: #856404;">Madya</span>
                <h4>Tingkat Menengah</h4>
                <p class="level-desc">
                    Latihan gerak tari dinamis cepat, penguasaan variasi koreografi tari berkelompok, dan pembiasaan ekspresi keluwesan gerak. Diperuntukkan bagi murid yang telah lulus tingkat dasar.
                </p>
            </div>

            <!-- Ahli -->
            <div class="level-card" style="--border-color: #800020;">
                <span class="level-badge" style="--bg-color: #f9e8ec; --text-color: #800020;">Ahli</span>
                <h4>Tingkat Lanjut</h4>
                <p class="level-desc">
                    Penguasaan tari kolosal yang rumit, teknik improvisasi panggung profesional, serta penjiwaan karakter tari mendalam untuk persiapan pertunjukan skala besar atau kompetisi tari daerah.
                </p>
            </div>
        </div>
    </div>

    <!-- Section 3: FAQ -->
    <div>
        <h3 class="faq-section-title">
            <i class="fa-solid fa-circle-question"></i> FAQ (Pertanyaan Umum)
        </h3>
        <div class="faq-accordion">
            <!-- FAQ 1 -->
            <div class="faq-item">
                <button class="faq-header" onclick="toggleFaq(this)">
                    Apakah murid diperbolehkan mendaftar di kelas Rampak dan Reguler sekaligus?
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        <strong>Ya, tentu saja!</strong> Murid direkomendasikan untuk mengambil kedua jenis kelas tersebut secara bersamaan. Mengambil kelas Rampak (hari Jumat) melatih kemampuan kerja sama kelompok dan formasi, sedangkan mengambil kelas Reguler (Sabtu & Minggu) akan memantapkan detail teknik kelenturan individu murid tersebut.
                    </div>
                </div>
            </div>

            <!-- FAQ 2 -->
            <div class="faq-item">
                <button class="faq-header" onclick="toggleFaq(this)">
                    Bagaimana cara menentukan tingkatan kelas (Pemula, Madya, atau Ahli)?
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        Saat mendaftar pertama kali, murid dapat memilih tingkat **Pemula** sebagai standar awal belajar. Namun, apabila murid telah memiliki dasar keterampilan menari sebelumnya, Pelatih akan melakukan peninjauan/evaluasi langsung di sanggar latihan untuk mengonfirmasi kelayakan murid tersebut naik ke kelas tingkat **Madya** atau **Ahli**.
                    </div>
                </div>
            </div>

            <!-- FAQ 3 -->
            <div class="faq-item">
                <button class="faq-header" onclick="toggleFaq(this)">
                    Bagaimana sistem absensi kelas latihan bekerja?
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        Setiap sesi latihan berlangsung, Pelatih yang bertugas mengajar kelas tersebut akan mencatat daftar kehadiran absensi murid secara langsung lewat aplikasi STEVA. Murid dapat memantau riwayat absensi secara real-time langsung melalui dashboard menu **Riwayat Absensi**.
                    </div>
                </div>
            </div>

            <!-- FAQ 4 -->
            <div class="faq-item">
                <button class="faq-header" onclick="toggleFaq(this)">
                    Bagaimana tata cara pembayaran iuran di sanggar STEVA?
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        Pembayaran dapat diselesaikan secara mudah melalui dua cara:
                        <ul style="margin: 6px 0 0 20px; display:flex; flex-direction:column; gap:4px;">
                            <li><strong>Transfer / QRIS:</strong> Murid melakukan transfer ke rekening bank sanggar atau scan QRIS resmi sanggar yang tersedia di sistem, kemudian mengisi form pembayaran dengan melampirkan bukti transfer. Transaksi akan langsung berstatus lunas setelah disetujui Admin.</li>
                            <li><strong>Tunai (Cash):</strong> Murid menyetor dana langsung kepada Admin secara langsung. Admin akan mencatatkan pembayaran murid tersebut sebagai transaksi lunas langsung pada panel Admin.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- FAQ 5 -->
            <div class="faq-item">
                <button class="faq-header" onclick="toggleFaq(this)">
                    Apakah pelatih diperbolehkan mengajar di semua kategori kelas?
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-content">
                    <div class="faq-content-inner">
                        Pelatih ditugaskan oleh Admin berdasarkan kualifikasi kepelatihan masing-masing untuk mengajar kelas tertentu. Jadwal mengajar pelatih akan disesuaikan dengan tingkat kelas (Pemula/Madya/Ahli) dan jenis kelas (Rampak/Reguler) yang diampunya.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFaq(header) {
        // Close other items
        const accordion = header.closest('.faq-accordion');
        const allHeaders = accordion.querySelectorAll('.faq-header');
        allHeaders.forEach(h => {
            if (h !== header) {
                h.classList.remove('active');
                const content = h.nextElementSibling;
                content.style.maxHeight = null;
            }
        });

        // Toggle current item
        header.classList.toggle('active');
        const content = header.nextElementSibling;
        if (header.classList.contains('active')) {
            content.style.maxHeight = content.scrollHeight + "px";
        } else {
            content.style.maxHeight = null;
        }
    }
</script>
