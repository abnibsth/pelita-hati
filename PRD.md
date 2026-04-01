# Product Requirements Document (PRD)
## Sistem Informasi Manajemen Posyandu Jakarta (SiPosyandu Jakarta)

**Versi:** 1.0  
**Tanggal:** 1 April 2026  
**Status:** Draft  

---

## 1. Ringkasan Eksekutif

### 1.1 Latar Belakang
Sistem Informasi Manajemen Posyandu Jakarta (SiPosyandu Jakarta) adalah platform berbasis web yang dirancang untuk mendigitalisasi pengelolaan data posyandu di wilayah DKI Jakarta. Sistem ini bertujuan untuk memudahkan kader posyandu dalam mencatat, memantau, dan melaporkan kegiatan posyandu secara real-time.

### 1.2 Tujuan
- Mendigitalkan pencatatan data balita dan kegiatan posyandu
- Mempermudah monitoring pertumbuhan balita secara terpusat
- Menyediakan laporan otomatis untuk puskesmas dan Dinas Kesehatan
- Mendeteksi dini kasus stunting dan gizi buruk
- Meningkatkan cakupan layanan posyandu di Jakarta

### 1.3 Scope Wilayah
- **Wilayah:** DKI Jakarta
- **Cakupan:** Semua kelurahan dengan multiple posyandu per kelurahan

---

## 2. Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Framework Backend | Laravel 13 |
| Database | MySQL 8.0+ |
| CSS Framework | Tailwind CSS 3.x |
| Authentication | Laravel Breeze/Fortify |
| Chart/Analytics | Chart.js atau ApexCharts |
| Export Data | Laravel Excel (Maatwebsite) |
| PDF Generation | DomPDF atau Snappy |

---

## 3. Struktur Organisasi & User Roles

### 3.1 Hierarki Wilayah
```
DKI Jakarta (Admin Kota)
├── Kecamatan A (Admin Kecamatan)
│   ├── Puskesmas A (Nakes Puskesmas)
│   ├── Kelurahan A1 (Admin Kelurahan)
│   │   ├── Posyandu Melati 1 (Kader)
│   │   ├── Posyandu Melati 2 (Kader)
│   │   └── Posyandu Melati 3 (Kader)
│   └── Kelurahan A2 (Admin Kelurahan)
│       ├── Posyandu Anggrek 1 (Kader)
│       └── Posyandu Anggrek 2 (Kader)
└── Kecamatan B
    └── ...
```

**Catatan:** 
- 1 Kader hanya ditugaskan di 1 Posyandu (Single-Posyandu)/
- 1 Kelurahan dapat memiliki banyak Posyandu
- Nakes Puskesmas dapat mengakses data dari semua kelurahan di wilayah puskesmasnya

### 3.2 User Roles & Permissions

| Role | Akses Data | Permissions |
|------|------------|-------------|
| **Admin Kota (Dinkes)** | Seluruh DKI Jakarta | - Dashboard Jakarta (all data)<br>- Export laporan seluruh kota<br>- Manage data kecamatan/kelurahan<br>- Manage semua user system<br>- View analytics & trend kota |
| **Admin Kecamatan** | Semua kelurahan di kecamatannya | - View laporan semua kelurahan<br>- Export laporan kecamatan<br>- Manage data kelurahan<br>- Manage user (kader, admin kelurahan)<br>- Dashboard analytics kecamatan |
| **Admin Kelurahan** | Semua posyandu di kelurahannya | - Manage data posyandu<br>- Manage kader<br>- View semua laporan<br>- Export laporan kelurahan |
| **Nakes Puskesmas** | Balita di wilayah puskesmas | - View data gizi buruk untuk tindak lanjut<br>- Input data imunisasi dari puskesmas<br>- View semua data balita di wilayah<br>- Generate laporan puskesmas<br>- Manage data rujukan balita |
| **Kader Posyandu** | Posyandu yang ditugaskan saja (1 posyandu) | - Input data balita<br>- Update pertumbuhan<br>- Catat kehadiran<br>- Catat pemberian obat/vitamin<br>- Generate laporan posyandu |
| **Orangtua/Balik** | Data anak sendiri saja | - View profil anak<br>- View riwayat pertumbuhan<br>- View jadwal posyandu<br>- View riwayat imunisasi |

---

## 4. Fitur Utama

### 4.1 Manajemen Data Wilayah

#### 4.1.1 Data Kelurahan
- Nama kelurahan
- Kecamatan
- Alamat lengkap
- Nomor telepon
- Admin kelurahan

#### 4.1.2 Data Posyandu
- Nama posyandu
- Kelurahan (relasi)
- Alamat lengkap
- Jadwal tetap (minggu ke-berapa, hari apa, jam berapa)
- Kader koordinator
- Daftar kader aktif

### 4.2 Manajemen User

#### 4.2.1 Registrasi & Autentikasi
- Login dengan email/NIK dan password
- Forgot password via email
- Registrasi user:
  - Admin Kota: oleh Super Admin
  - Admin Kecamatan: oleh Admin Kota
  - Admin Kelurahan: oleh Admin Kecamatan
  - Nakes Puskesmas: oleh Admin Kecamatan
  - Kader: oleh Admin Kelurahan
  - Orangtua: oleh Kader/Admin Kelurahan
- Role-based access control (RBAC)

#### 4.2.2 Profil User
- Data pribadi (nama, NIK, no HP, email)
- Foto profil
- Posyandu yang ditugaskan (untuk kader)
- Daftar anak (untuk orangtua)

### 4.3 Data Balita

#### 4.3.1 Data Dasar Balita
| Field | Tipe | Keterangan |
|-------|------|------------|
| NIK Anak | String(16) | Unique identifier |
| Nama Lengkap | String | |
| Tanggal Lahir | Date | |
| Jenis Kelamin | Enum | Laki-laki/Perempuan |
| Nama Ibu | String | |
| NIK Ibu | String(16) | |
| Nama Ayah | String | |
| No HP Orangtua | String | |
| Alamat | Text | |
| RT/RW | String | |
| Posyandu | Relasi | Posyandu asal |
| Tanggal Daftar | DateTime | |
| Status | Enum | Aktif/Pindah/Meninggal |

#### 4.3.2 Riwayat Pertumbuhan (Setiap Kunjungan)
| Field | Tipe | Keterangan |
|-------|------|------------|
| Tanggal Kunjungan | Date | |
| Berat Badan | Decimal(5,2) | dalam kg |
| Tinggi Badan | Decimal(5,2) | dalam cm |
| Lingkar Kepala | Decimal(4,2) | dalam cm (opsional) |
| Lingkar Lengan Atas | Decimal(4,2) | dalam cm (opsional) |
| Umur Saat Ukur | String | dihitung otomatis |
| Status Gizi | Enum | Normal/Kurang/Lebih/Stunting |
| Z-Score BB/U | Decimal | |
| Z-Score TB/U | Decimal | |
| Z-Score BB/TB | Decimal | |
| Catatan | Text | keluhan/catatan kader |
| Kader yang Input | Relasi | user_id |

#### 4.3.3 Riwayat Imunisasi
| Field | Tipe | Keterangan |
|-------|------|------------|
| Jenis Imunisasi | Enum | HB-0, BCG, Polio 1-4, DPT-HB 1-3, Campak, dll |
| Tanggal Diberikan | Date | |
| Batch Number | String | |
| Lokasi | String | Posyandu/Puskesmas/RS |
| Keterangan | Text | efek samping/catatan |

#### 4.3.4 Riwayat Pemberian Vitamin/Suplemen
| Field | Tipe | Keterangan |
|-------|------|------------|
| Jenis | Enum | Vitamin A, Tablet Tambah Darah, Obat Cacing, dll |
| Tanggal | Date | |
| Dosis | String | |
| Keterangan | Text | |

### 4.4 Jadwal Posyandu

#### 4.4.1 Jadwal Tetap Bulanan
Setiap posyandu memiliki jadwal tetap yang berulang setiap bulan:
- **Frekuensi:** Minggu ke-1, ke-2, ke-3, atau ke-4
- **Hari:** Senin/Minggu/Sabtu, dll
- **Jam:** 08:00 - 12:00
- **Lokasi:** Alamat posyandu

#### 4.4.2 Kalender Posyandu
- View kalender bulanan dengan jadwal semua posyandu
- Filter berdasarkan kelurahan/kecamatan
- Integrasi dengan Google Calendar (opsional)

### 4.5 Kegiatan Harian Kader

#### 4.5.1 Input Data Balita Baru
- Form registrasi balita baru
- Validasi NIK unik
- Upload foto (opsional)
- Generate KMS digital

#### 4.5.2 Update Pertumbuhan
- Pencarian balita berdasarkan NIK/nama
- Input hasil penimbangan
- Auto-calculate status gizi berdasarkan standar WHO
- Auto-generate grafik pertumbuhan
- Alert jika ada penyimpangan

#### 4.5.3 Absensi Kehadiran
- List balita terdaftar di posyandu
- Checkbox kehadiran per sesi posyandu
- Tanggal otomatis sesuai jadwal
- Statistik kehadiran bulanan

#### 4.5.4 Catat Pemberian Obat/Vitamin
- Pilih balita
- Pilih jenis vitamin/obat
- Input tanggal dan dosis
- Riwayat pemberian

#### 4.5.5 Deteksi Dini Stunting
- Auto-flag balita dengan TB/U < -2 SD
- Rekomendasi tindak lanjut
- Notifikasi ke admin kelurahan/puskesmas

### 4.6 Laporan & Analytics

#### 4.6.1 Dashboard Analytics
| Widget | Deskripsi |
|--------|-----------|
| Total Balita | Per posyandu/kelurahan |
| Balita Hadir Bulan Ini | Jumlah dan persentase |
| Status Gizi | Pie chart (Normal/Kurang/Stunting) |
| Cakupan Imunisasi | Progress bar per jenis imunisasi |
| Grafik Pertumbuhan | Line chart per balita |

#### 4.6.2 Laporan SKDN
Format standar Kemenkes:
- **S** (Sasaran): Jumlah balita 0-59 bulan
- **K** (Kunjungan): Jumlah kunjungan balita
- **D** (Diberi): Jumlah balita dapat pelayanan
- **N** (Nutrisi): Jumlah balita dengan gizi buruk

#### 4.6.3 Laporan Stunting
- Prevalensi stunting per posyandu/kelurahan/kecamatan
- Trend bulanan/tahunan
- List balita berisiko stunting

#### 4.6.4 Cakupan Imunisasi
- Persentase imunisasi lengkap per wilayah
- List balita belum imunisasi sesuai usia
- Reminder imunisasi tertunda

#### 4.6.5 Export Data
- Export ke Excel (.xlsx)
- Export ke PDF
- Template sesuai format Dinkes Jakarta

### 4.7 Fitur untuk Orangtua

#### 4.7.1 Dashboard Orangtua
- Profil anak
- Grafik pertumbuhan (KMS digital)
- Jadwal posyandu berikutnya
- Riwayat imunisasi
- Status gizi terkini

#### 4.7.2 Notifikasi (Opsional - Perlu Konfirmasi)
| Tipe | Trigger | Channel |
|------|---------|---------|
| Reminder Jadwal | H-1 jadwal posyandu | WhatsApp/SMS |
| Alert Tidak Hadir | 2 bulan tidak hadir | WhatsApp/SMS |
| Reminder Imunisasi | Mendekati jadwal imunisasi | WhatsApp/SMS |
| Alert Gizi Buruk | Terdeteksi gizi buruk | WhatsApp/SMS |

---

## 5. Logika Bisnis

### 5.1 Perhitungan Status Gizi
Menggunakan standar WHO 2005:
- **Z-Score BB/U** (Berat Badan menurut Umur)
- **Z-Score TB/U** (Tinggi Badan menurut Umur)
- **Z-Score BB/TB** (Berat Badan menurut Tinggi Badan)

Kategori:
| Z-Score | Kategori |
|---------|----------|
| > +2 SD | Gizi lebih |
| -2 SD sampai +2 SD | Gizi normal |
| < -2 SD sampai -3 SD | Gizi kurang |
| < -3 SD | Gizi buruk |

### 5.2 Deteksi Stunting
- **Stunted:** TB/U < -2 SD
- **Severely Stunted:** TB/U < -3 SD

### 5.3 KMS Digital
- Generate grafik pertumbuhan otomatis
- Plot point berat badan per bulan
- Garis referensi WHO (p50, p70, p85, dll)
- Warna berbeda per status gizi

### 5.4 Alur Kerja Kader (Workflow)

```
[Hari Posyandu]
     ↓
1. Login ke sistem
     ↓
2. Buka menu "Kegiatan Hari Ini"
     ↓
3. Absensi kehadiran balita
     ↓
4. Input data penimbangan (untuk yang hadir)
     ↓
5. Sistem auto-calculate status gizi
     ↓
6. Catat pemberian vitamin/obat
     ↓
7. Generate laporan harian
     ↓
8. Submit ke Admin Kelurahan
```

### 5.5 Alur Registrasi Balita Baru

```
Orangtua datang ke Posyandu
        ↓
Kader buka menu "Tambah Balita"
        ↓
Input data lengkap + NIK
        ↓
Validasi NIK (cek duplikasi)
        ↓
Upload dokumen (KK/Akta) - opsional
        ↓
Sistem generate KMS digital
        ↓
Balita terdaftar aktif
```

---

## 6. Database Schema (Ringkasan)

### 6.1 Tabel Utama

```
users
├── id
├── name
├── email
├── password
├── role (admin_kota, admin_kecamatan, admin_kelurahan, nakes_puskesmas, kader, orangtua)
├── nik
├── phone
├── kelurahan_id (untuk admin_kelurahan, kader)
├── kecamatan_id (untuk admin_kecamatan)
├── posyandu_id (untuk kader)
├── puskesmas_id (untuk nakes_puskesmas)
└── ...

kecamatans
├── id
├── name
├── kota_id
├── address
└── ...

kelurahans
├── id
├── name
├── kecamatan_id
├── address
└── ...

puskesmas
├── id
├── name
├── kecamatan_id
├── address
├── phone
└── ...

posyandus
├── id
├── name
├── kelurahan_id
├── address
├── jadwal_minggu_ke
├── jadwal_hari
├── jadwal_jam
└── ...

balitas
├── id
├── nik
├── name
├── birth_date
├── gender
├── mother_name
├── mother_nik
├── father_name
├── parent_phone
├── address
├── rt_rw
├── posyandu_id
├── user_id (orangtua)
├── status
└── ...

pertumbuhan_records
├── id
├── balita_id
├── tanggal
├── berat_badan
├── tinggi_badan
├── lingkar_kepala
├── lingkar_lengan
├── status_gizi
├── z_score_bbu
├── z_score_tbu
├── z_score_bbtb
├── kader_id
└── catatan

imunisasi_records
├── id
├── balita_id
├── jenis_imunisasi
├── tanggal
├── batch_number
├── lokasi
├── input_by (kader/nakes)
└── keterangan

vitamin_records
├── id
├── balita_id
├── jenis
├── tanggal
├── dosis
└── keterangan

kehadirans
├── id
├── balita_id
├── posyandu_id
├── tanggal
├── hadir (boolean)
└── keterangan

rujukan
├── id
├── balita_id
├── puskesmas_id
├── tanggal_rujuk
├── jenis_keluhan
├── status_gizi
├── tindak_lanjut
├── nakes_id
└── status (dirujuk/diteruskan/selesai)
```

---

## 7. Wireframe & UI Flow

### 7.1 Halaman Login
- Logo SiPosyandu Jakarta
- Form email & password
- Link forgot password
- Footer copyright

### 7.2 Dashboard Kader
- Statistik posyandu (total balita, hadir bulan ini)
- Jadwal posyandu berikutnya
- Quick action (Tambah Balita, Input Penimbangan)
- Notifikasi (jika ada)
- Grafik kehadiran bulanan

### 7.3 Dashboard Admin Kelurahan
- Statistik kelurahan (total posyandu, total balita)
- Peta sebaran posyandu
- Grafik status gizi kelurahan
- Alert balita gizi buruk
- Quick report export

### 7.4 Dashboard Admin Kecamatan
- Statistik kecamatan (total kelurahan, total posyandu, total balita)
- Grafik status gizi kecamatan
- Ranking kelurahan berdasarkan cakupan posyandu
- Alert gizi buruk per kelurahan
- Export laporan kecamatan

### 7.5 Dashboard Admin Kota (Dinkes)
- Statistik DKI Jakarta (total kecamatan, kelurahan, posyandu, balita)
- Peta heatmap sebaran stunting per kecamatan
- Trend bulanan seluruh Jakarta
- Ranking kecamatan/kelurahan
- Export laporan kota

### 7.6 Dashboard Nakes Puskesmas
- List balita gizi buruk untuk tindak lanjut
- Cakupan imunisasi wilayah puskesmas
- Data rujukan balita
- Grafik pertumbuhan balita bermasalah
- Generate laporan puskesmas

### 7.7 Dashboard Orangtua
- Profil anak
- Grafik KMS digital
- Jadwal posyandu berikutnya
- Riwayat imunisasi
- Status gizi terkini

---

## 8. Keamanan & Privacy

### 8.1 Proteksi Data
- Password hashing (bcrypt)
- CSRF protection
- SQL injection prevention
- XSS protection
- Rate limiting untuk API

### 8.2 Data Privacy
- Data balita hanya bisa diakses oleh:
  - Kader posyandu terkait (hanya balita di posyandunya)
  - Admin kelurahan (balita di kelurahannya)
  - Admin kecamatan (balita di kecamatannya)
  - Admin kota (semua balita di Jakarta)
  - Nakes puskesmas (balita di wilayah puskesmasnya)
  - Orangtua balita tersebut (hanya anaknya sendiri)
- NIK dan data sensitif dienkripsi di database
- Audit log untuk semua perubahan data

### 8.3 Backup & Recovery
- Daily automated backup
- Point-in-time recovery
- Disaster recovery plan

---

## 9. Integrasi (Future Phase)

### 9.1 Potensi Integrasi
- **SatuSehat Kemenkes:** Sinkronisasi data imunisasi
- **Dukcapil Jakarta:** Validasi NIK online
- **WhatsApp Business API:** Notifikasi otomatis
- **Puskesmas:** Rujukan balita gizi buruk
- **Dinkes Jakarta:** Reporting otomatis

---

## 10. Timeline Pengembangan (Estimasi)

| Phase | Durasi | Deliverables |
|-------|--------|--------------|
| Phase 1: Setup & Auth | 1 minggu | Project setup, DB schema, Authentication |
| Phase 2: Data Master | 1 minggu | CRUD Kelurahan, Posyandu, User Management |
| Phase 3: Data Balita | 2 minggu | CRUD Balita, Pertumbuhan, Imunisasi |
| Phase 4: Kegiatan Posyandu | 2 minggu | Absensi, Input data, KMS digital |
| Phase 5: Laporan | 2 minggu | Dashboard, Analytics, Export |
| Phase 6: Testing & Deploy | 1 minggu | UAT, Bug fixing, Deployment |

**Total Estimasi:** 9 minggu

---

## 11. Kriteria Sukses

### 11.1 Functional Requirements
- [ ] Semua user role dapat login sesuai permission
- [ ] Kader dapat input data balita dan pertumbuhan
- [ ] Sistem auto-calculate status gizi dengan benar
- [ ] Dashboard menampilkan analytics real-time sesuai role
- [ ] Export laporan ke Excel/PDF berfungsi untuk semua level
- [ ] Orangtua dapat melihat data anak sendiri
- [ ] Nakes dapat input imunisasi dan manage rujukan
- [ ] Admin kecamatan/kota dapat view laporan wilayahnya

### 11.2 Non-Functional Requirements
- [ ] Response time < 3 detik untuk semua halaman
- [ ] Dapat handle 1000+ concurrent users
- [ ] Uptime 99% selama jam operasional
- [ ] Mobile responsive (dapat diakses via HP)
- [ ] Browser compatibility (Chrome, Firefox, Safari, Edge)

---

## 12. Catatan & Asumsi

1. **Notifikasi:** Fitur notifikasi WhatsApp/SMS memerlukan budget tambahan untuk API subscription. Alternatif: notifikasi in-app saja.

2. **Konektivitas:** Sistem mengasumsikan kader memiliki koneksi internet stabil. Pertimbangkan mode offline untuk area blankspot.

3. **Training:** Diperlukan training untuk kader dalam penggunaan sistem.

4. **Data Migration:** Jika ada data existing dari sistem lama, perlu proses migrasi terpisah.

5. **Standar Gizi:** Menggunakan standar WHO 2005. Jika ada update standar dari Kemenkes, perlu penyesuaian.

---

## 13. Approval

| Role | Nama | Tanggal | Tanda Tangan |
|------|------|---------|--------------|
| Product Owner | | | |
| Tech Lead | | | |
| Stakeholder (Dinkes) | | | |

---

## Lampiran

### A. Referensi
- [Standar Antropometri WHO 2005](https://www.who.int/tools/child-growth-standards)
- [Petunjuk Teknis Posyandu Kemenkes](https://www.kemkes.go.id/)
- [Format Laporan SKDN](https://dinkes.jakarta.go.id/)

### B. Glosarium
- **KMS:** Kartu Menuju Sehat
- **SKDN:** Sistem Pencatatan Posyandu
- **Z-Score:** Standar deviasi dari median WHO
- **Stunting:** Kondisi gagal tumbuh akibat kurang gizi kronis
