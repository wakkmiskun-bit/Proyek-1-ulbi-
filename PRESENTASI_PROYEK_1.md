# PANDUAN & DOKUMEN PRESENTASI SIDANG PROYEK 1
## Aplikasi TaskMate: Sistem Manajemen Tugas dan Produktivitas Berbasis Web

* **Nama Kelompok**: Kelompok 26
* **Anggota (Nama - NPM)**:
  1. Muhammad Ilham Habiballah - 714250003
  2. Gianjar Nugraha - 714250007
* **Dosen Pembimbing**: Cahyo Prianto, S.Pd., M.T., CDSP, SFPC (NIK: 117.84.222)
* **Koordinator Proyek 1**: M. Yusril Helmi Setyawan, S.Kom., M.Kom (NIK: 113.74.163)
* **Program Studi**: D4 Teknik Informatika - Universitas Logistik dan Bisnis Internasional (ULBI)
* **Tahun Akademik**: 2025/2026

---

## BERKAS PRESENTASI YANG TERSEDIA

1. **Berkas PowerPoint (.pptx)**: `TaskMate_Presentasi_Proyek1.pptx`  
   *Dapat langsung dibuka di Microsoft PowerPoint / Google Slides.*
2. **Web Presentasi Interaktif (.html)**: `presentasi.html`  
   *Dapat dibuka langsung via Browser (Chrome/Edge) dengan menekan tombol **F** untuk Fullscreen.*

---

## ESTRUKTUR SLIDE & CATATAN PRESENTER (SPEAKER NOTES)

---

### SLIDE 1: JUDUL & INFORMASI TIM (COVER SLIDE)
* **Waktu**: 1 Menit
* **Judul Slide**: TaskMate: Sistem Manajemen Tugas dan Produktivitas Berbasis Web
* **Konten**:
  - Judul Aplikasi & Laporan Akhir Sidang Proyek 1
  - Mahasiswa Presenter: Muhammad Ilham Habiballah & Gianjar Nugraha
  - Dosen Pembimbing: Cahyo Prianto, S.Pd., M.T., CDSP, SFPC
  - D4 Teknik Informatika ULBI 2026
* **Catatan Presenter (Speaker Script)**:
  > *"Assalamu’alaikum Wr. Wb. Selamat pagi/siang kepada Bapak/Ibu Dewan Penguji dan Dosen Pembimbing. Perkenalkan kami dari Kelompok 26, saya Muhammad Ilham Habiballah dan rekan saya Gianjar Nugraha. Pada kesempatan kali ini, kami akan mempresentasikan hasil pengembangan aplikasi Proyek 1 kami yang berjudul 'TaskMate: Sistem Manajemen Tugas dan Produktivitas Berbasis Web'."*

---

### SLIDE 2: BAB I - PENDAHULUAN & LATAR BELAKANG
* **Waktu**: 2 Menit
* **Judul Slide**: Latar Belakang & Identifikasi Masalah Akademik
* **Konten**:
  - **Permasalahan**: Beban kurikulum padat ULBI, pencatatan manual tidak terintegrasi, fenomena prokrastinasi & forgetfulness deadline, minimnya monitoring akademik oleh admin.
  - **Solusi TaskMate**: Visual Management (Kanban Board), WhatsApp Gateway Reminder proaktif (H-5 & H-2), Centralized Admin Monitoring Dashboard.
* **Catatan Presenter (Speaker Script)**:
  > *"Latar belakang ide ini berawal dari banyaknya beban tugas dan praktikum yang dihadapi mahasiswa ULBI. Pencatatan manual seringkali membuat mahasiswa lupa tenggat waktu. Di sisi lain, pengelola akademik kesulitan memantau keaktifan mahasiswa. Oleh karena itu, TaskMate hadir mengintegrasikan Kanban Board visual dan notifikasi WhatsApp Gateway otomatis."*

---

### SLIDE 3: BAB I - VISI, MISI & TUJUAN PENGEMBANGAN
* **Waktu**: 1.5 Menit
* **Judul Slide**: Visi, Misi & Tujuan Khusus Development
* **Konten**:
  - **Visi**: Platform manajemen tugas berbasis web No.1 di ULBI yang mendorong kedisiplinan dan produktivitas.
  - **Misi**: UI Kanban bersih dengan Tailwind CSS, pengingat WA proaktif, monitoring admin real-time.
  - **Tujuan Khusus**: Multi-role Authentication, CRUD Task, Laravel Task Scheduler + Fonnte WA API, Admin Analytics Dashboard.
* **Catatan Presenter (Speaker Script)**:
  > *"Visi kami adalah menjadikan TaskMate sebagai asisten digital utama mahasiswa. Tujuan khusus kami mencakup 5 target utama: otorisasi multi-role aman, papan kerja Kanban interaktif, scheduler pengingat WhatsApp, dan dasbor monitoring admin."*

---

### SLIDE 4: BAB II - DESKRIPSI SISTEM & STAKEHOLDER
* **Waktu**: 1.5 Menit
* **Judul Slide**: Stakeholder & Hak Akses Pengguna
* **Konten**:
  - **Administrator**: Full control, CRUD Mahasiswa, Live Kanban Monitoring, Audit Log Aktivitas.
  - **Mahasiswa**: Workspace terisolasi, CRUD Task, Drag & Drop Kanban, Notifikasi WA, Pengaturan Profil.
  - **Tamu (Guest)**: Landing Page publik & Statistik Global real-time.
* **Catatan Presenter (Speaker Script)**:
  > *"Sistem kami membagi pengguna menjadi 3 aktor utama: Admin yang memantau performa & log audit, Mahasiswa sebagai pengguna utama yang mengelola tugasnya secara mandiri, dan Tamu yang dapat melihat statistik global aplikasi."*

---

### SLIDE 5: BAB II - KEBUTUHAN FUNGSIONAL & NON-FUNGSIONAL
* **Waktu**: 1.5 Menit
* **Judul Slide**: Kebutuhan Fungsional (FR) & Non-Fungsional (NFR)
* **Konten**:
  - **FR**: Multi-Guard Auth (`web` & `admin`), Inline Task Form, Auto-Done Checklist 100%, Laravel Scheduler Cron, Auto-Logging `activities`.
  - **NFR**: Bcrypt Password Hashing, CSRF Protection, Response time < 200ms, Upload Limit 2MB (JPG/PNG), Glassmorphism UI & Responsive Tailwind CSS.
* **Catatan Presenter (Speaker Script)**:
  > *"Kebutuhan fungsional dijamin melalui pemisahan Auth Guard Laravel dan otomatisasi scheduler. Kebutuhan non-fungsional memastikan keamanan enkripsi Bcrypt, kecepatan muat di bawah 2 detik, serta antarmuka glassmorphism yang modern."*

---

### SLIDE 6: BAB III - ARSITEKTUR SISTEM
* **Waktu**: 2 Menit
* **Judul Slide**: Diagram Arsitektur Terdistribusi Client-Server
* **Konten**:
  - **Frontend Client**: HTML5, Tailwind CSS, Vanilla JS, SortableJS (AJAX Fetch API).
  - **Backend Server**: Laravel Application Engine (Routing, Controllers, Eloquent ORM).
  - **Database & Services**: MySQL DB, Laravel Task Scheduler Cron, Fonnte WhatsApp API Gateway.
* **Catatan Presenter (Speaker Script)**:
  > *"Secara arsitektur, TaskMate mengadopsi model Client-Server terdistribusi. Frontend berkomunikasi asinkron via AJAX Fetch API. Di backend, Laravel mengolah logika bisnis dan mengeksekusi Cron Job terjadwal yang terhubung ke API Gateway Fonnte untuk meneruskan pesan notifikasi ke WhatsApp mahasiswa."*

---

### SLIDE 7: BAB III - WORKFLOW SISTEM & KANBAN LIFECYCLE
* **Waktu**: 2 Menit
* **Judul Slide**: Siklus Hidup Tugas & Workflow Pembaharuan Status
* **Konten**:
  - 1. Autentikasi -> 2. Input Tugas Inline -> 3. Kanban Lifecycle (To Do -> Doing -> Review -> Done) & Auto-Done -> 4. Automated WA Reminder (H-5 & H-2).
* **Catatan Presenter (Speaker Script)**:
  > *"Alur kerja diawali registrasi terenkripsi. Mahasiswa menambah tugas di kolom To Do, menyeret kartu menggunakan SortableJS ke kolom Doing/Done. Ketika checklist sub-tugas mencapai 100%, sistem otomatis mengubah status ke Done. Latar belakang server secara berkala memindai tugas yang mendekati deadline (H-5/H-2) untuk dikirimkan pengingat WhatsApp."*

---

### SLIDE 8: BAB III - ERD DATABASE STRUCTURAL
* **Waktu**: 1.5 Menit
* **Judul Slide**: Struktur Relasi Basis Data MySQL (ERD)
* **Konten**:
  - Tabel: `mahasiswas`, `tasks`, `activities`, `task_reminders`, `admins`.
  - Relasi 1-to-Many dengan Foreign Key & Cascade Delete.
* **Catatan Presenter (Speaker Script)**:
  > *"Relasi basis data dirancang efisien dalam MySQL. Tabel mahasiswas terhubung One-to-Many dengan tasks, activities, dan task_reminders. Setiap kartu tugas dapat memicu maksimal 2 catatan pengingat (H-5 & H-2) untuk mencegah spam."*

---

### SLIDE 9: BAB IV - KONSEP DESAIN ANTARMUKA
* **Waktu**: 1.5 Menit
* **Judul Slide**: Aesthetics & UI/UX Principles
* **Konten**:
  - Skema Warna Vibrant Pink (`#e91e63` ke `#f06292`).
  - Tipografi Plus Jakarta Sans & DM Sans.
  - Efek Glassmorphism (`backdrop-filter: blur(10px)`).
  - Desain Responsif (Desktop Grid & Mobile Stacked).
* **Catatan Presenter (Speaker Script)**:
  > *"Dari sisi antarmuka, kami menerapkan skema warna gradasi pink modern yang dinamis. Sentuhan efek glassmorphism pada kartu dan sidebar memberikan kedalaman visual yang mewah, serta fully responsive di perangkat seluler."*

---

### SLIDE 10: BAB V - TECH STACK & TOOLS IMPLEMENTASI
* **Waktu**: 1.5 Menit
* **Judul Slide**: Implementasi Teknologi Server & Client
* **Konten**:
  - Backend: PHP 8.x, Laravel 10/11, Eloquent ORM.
  - Frontend: Tailwind CSS, Vanilla JS, SortableJS, FontAwesome.
  - Tools: MySQL, XAMPP, Git & GitHub, VS Code.
* **Catatan Presenter (Speaker Script)**:
  > *"Teknologi utama yang digunakan adalah Laravel 10/11 di sisi server dan Tailwind CSS di sisi klien. Pengujian lokal dilakukan menggunakan Apache dan MySQL pada paket XAMPP."*

---

### SLIDE 11: BAB V - STRUKTUR DIREKTORI & CODEBASE
* **Waktu**: 1.5 Menit
* **Judul Slide**: Modul Controller, Model & Support Services
* **Konten**:
  - Controllers: `TaskController`, `AdminDashboardController`, `ProfileController`.
  - Services: `WhatsAppService.php` (cURL Fonnte API) & `ActivityLogger.php` (Auto Audit Trail).
  - Storage Symlink: `profile-photos`.
* **Catatan Presenter (Speaker Script)**:
  > *"Struktur kode kami memisahkan logika bisnis secara modular. Kami menyertakan layer Service khusus yaitu WhatsAppService untuk mengelola request API Fonnte dan ActivityLogger untuk pencatatan otomatis transaksi aktivitas."*

---

### SLIDE 12: BAB VI - KESIMPULAN HASIL PROYEK
* **Waktu**: 1 Menit
* **Judul Slide**: Kesimpulan Hasil Implementasi (100%)
* **Konten**:
  - 100% Luaran Proyek 1 berhasil diselesaikan.
  - Kanban Board mempermudah pelacakan tugas visual.
  - Automated WhatsApp Reminder proaktif menekan angka keterlambatan.
  - Dasbor Admin memberikan transparansi monitoring dan audit log.
* **Catatan Presenter (Speaker Script)**:
  > *"Kesimpulannya, aplikasi TaskMate v2.0 telah selesai 100%. Kombinasi visual Kanban Board dan notifikasi WhatsApp Gateway terbukti menjadi solusi efektif bagi mahasiswa ULBI dalam mengelola kewajiban akademiknya."*

---

### SLIDE 13: BAB VI - SARAN PENGEMBANGAN MASA DEPAN
* **Waktu**: 1 Menit
* **Judul Slide**: Rencana Pengembangan Aplikasi
* **Konten**:
  - Interactive 2-Way WhatsApp Bot (Update status via balasan WA).
  - Productivity Analytics Chart (Grafik performa belajar mingguan).
  - Mobile Progressive Web App (PWA) / Android Native app.
* **Catatan Presenter (Speaker Script)**:
  > *"Untuk saran pengembangan ke depan, kami merekomendasikan integrasi bot WA 2 arah, grafik analitik produktivitas mingguan, dan konversi aplikasi ke format PWA Mobile."*

---

### SLIDE 14: SLIDE PENUTUP & Q&A
* **Waktu**: Diskusi Penguji
* **Judul Slide**: Terima Kasih & Sesi Tanya Jawab
* **Catatan Presenter (Speaker Script)**:
  > *"Sekian presentasi dari kelompok 26. Terima kasih atas perhatian Bapak/Ibu Penguji dan Dosen Pembimbing. Waktu dan tempat kami persilakan untuk sesi tanya jawab."*

---

## PETUNJUK DEMO APLIKASI SAAT SIDANG

1. **Jalankan Server**:
   ```bash
   php artisan serve
   npm run dev
   ```
2. **Buka Browser**: Akses `http://127.0.0.1:8000`.
3. **Simulasi Alur Demo**:
   - Tunjukkan **Landing Page Publik** (Statistik real-time).
   - Login sebagai **Mahasiswa**: Buat tugas baru di Kanban, drag-and-drop kartu, centang sub-task checklist hingga 100% (Auto-Done).
   - Tunjukkan **Profil Mahasiswa** (Unggah foto profil 2MB).
   - Login sebagai **Admin**: Tunjukkan dasbor statistik, monitoring live Kanban board mahasiswa, dan daftar audit log aktivitas.
