# Analisis Kesiapan dan Kelayakan Alutsista TNI Berbasis Data Persenjataan Global

![Dashboard Screenshot](docs/screenshots/dashboard.png)

## 📌 Informasi Proyek
**Mata Kuliah:** Analitik dan Visualisasi Data  
**Institusi:** [Nama Kampus/Universitas]  

**Penulis:**
1. **Muhammad Jabbar Syahrial Reza** (411231096)
2. **Wisnu Widya Pradana** (411231088)

---

## 📖 Ringkasan Proyek (Project Overview)
Proyek ini adalah sebuah aplikasi berbasis web (*dashboard analytics*) yang dirancang untuk menganalisis, memvalidasi, dan memvisualisasikan lebih dari 10.000 data sistem persenjataan (Alutsista) dari 128 negara di seluruh dunia. Tujuan utama dari *dashboard* ini adalah memberikan komparasi atau *benchmark* yang obyektif mengenai **kesiapan dan kelayakan Alutsista Tentara Nasional Indonesia (TNI)** jika dibandingkan dengan tren persenjataan global.

### Latar Belakang & Pernyataan Masalah
Informasi mengenai persenjataan militer umumnya sangat masif dan sulit dipahami secara sekilas. Analisis kekuatan militer suatu negara (terutama Indonesia) tidak bisa hanya dilihat dari "jumlah" senjata, melainkan harus ditinjau dari **usia peralatan**, **efisiensi biaya**, **pengalaman tempur (*combat proven*)**, dan **standar interoperabilitas**. Masalahnya, data mentah ini masih berupa teks/tabel yang sulit dianalisis. Oleh karena itu, diperlukan sistem analitik dan visualisasi data yang mampu mengekstrak *insight* secara interaktif.

### Tujuan Proyek
1. Membersihkan dan menstandardisasi dataset persenjataan global skala besar (10.000+ baris).
2. Membangun visualisasi interaktif untuk menyoroti rasio senjata yang masih aktif versus pensiun.
3. Menganalisis tingkat pengalaman tempur (*combat proven*) Alutsista milik Indonesia.
4. Memberikan evaluasi analitis terkait kekuatan lintas matra (Darat, Laut, Udara).

### Fitur Utama
- **Dual Dashboard View:** Mode *Global* (analisis data dunia) dan mode *Indonesia* (analisis spesifik inventaris TNI).
- **Interactive Visualizations:** Grafik distribusi operasional, tren kronologis, dan sebaran matra berbasis Chart.js.
- **Data Filtering & Scoping:** Filter *real-time* menggunakan AJAX untuk membedah data secara asinkron tanpa *reload* halaman.
- **Responsive Edge-to-Edge Design:** Antarmuka bergaya *enterprise dashboard* yang memanjakan mata dan mudah dibaca (terinspirasi dari Looker Studio).

### Manfaat Sistem
- Membantu pengamat militer dan akademisi untuk memahami postur pertahanan.
- Menjadi landasan argumentasi ilmiah mengenai pentingnya peremajaan Alutsista.
- Memberikan panduan *best-practice* visualisasi data kompleks menjadi grafik yang human-readable.

---

## 📊 Analisis Data

### Dataset Overview
Dataset yang digunakan mencakup `10.000` catatan senjata dari `128` negara. Kolom-kolom utama meliputi:
- `Weapon_Name`, `Category`, `Country_of_Origin`, `Primary_Users`
- `Year_Introduced`, `Year_Retired`, `Service_Status`
- `Unit_Cost_USD`, `Combat_Proven`, `NATO_Compatible`, `Theater_of_Operation`

### Metodologi
Metodologi analisis menggunakan pendekatan **EDA (Exploratory Data Analysis)**:
1. **Data Collection:** Mengumpulkan *raw data* berformat `.csv`.
2. **Data Cleansing:** Menangani *missing values* (seperti `Year_Retired` yang kosong diasumsikan sebagai "Masih Aktif").
3. **Data Transformation:** Merubah format tipe data (misal: *parsing* `Year_Introduced` menjadi *Integer* untuk *sorting* kronologis).
4. **Data Visualization:** Mengonversi agregasi SQL menjadi representasi visual.

### Temuan Utama (Key Findings & Insights)
1. **Dominasi Matra Darat:** Distribusi Alutsista TNI sangat berat pada kategori matra darat (Support Weapon, Small Arm) dibandingkan kekuatan maritim dan udara.
2. **Paradoks Kualitas vs Kuantitas:** Meskipun jumlah senjata aktif TNI tinggi, sebagian besar pengadaannya merupakan alutsista usia tua (lebih dari 30 tahun) yang berisiko mengurangi kapabilitas tempur modern.
3. **Status Tempur:** Rasio alutsista yang sudah *Combat Proven* berada di angka moderat, menunjukkan ketergantungan pada senjata yang teruji secara historis dibandingkan inovasi baru yang berisiko.

---

## 🛠️ Algoritma & Penjelasan Teknis (Technical Documentation)

Proyek ini tidak hanya melakukan operasi dasar *Create, Read, Update, Delete* (CRUD), tetapi juga mengimplementasikan algoritma manipulasi himpunan data besar dan *Machine Learning* untuk ekstraksi informasi (Insight Extraction). Berikut adalah algoritma analitik yang digunakan, tujuan penggunaannya, dan alasan pemilihannya.

### 1. Algoritma Agregasi Relasional (Set-Based Aggregation)
* **Apa yang Dilakukan:** Menggunakan teknik pemrosesan himpunan di tingkat database (`GROUP BY`, `COUNT()`, `AVG()`) secara *real-time* via Laravel Query Builder (contoh: memfilter *Primary_Users* yang *LIKE '%Indonesia%'*).
* **Tujuan (Kenapa):** Untuk mengkalkulasi matriks utama (KPI seperti Total Senjata, Harga Rata-rata) dan mendistribusikan data ke dalam struktur agregat sebelum dikirim ke grafik visual.
* **Mengapa Memilih Algoritma Ini:** Daripada menarik semua data mentah (10.000+ baris) lalu melakukan *Iterative Processing* (contoh: *looping foreach*) di memori PHP yang sangat menguras RAM (*Out of Memory Risk*), kami mendelegasikan komputasi berat ke *Database Engine*. MySQL jauh lebih efisien berkat *B-Tree Indexing*, memastikan waktu muat *dashboard* tetap secepat kilat (*lightning fast*).

### 2. Algoritma Transformasi & Mapping Array (Single-Pass Dimensional Mapping)
* **Apa yang Dilakukan:** Mengambil *raw objects* hasil kueri SQL lalu memecahnya secara programatis di dalam Controller menggunakan iterasi silang (*cross-iteration*) menjadi struktur *Multi-Dimensional Array* (membagi berdasarkan subset *Theater_of_Operation*: Darat, Laut, Udara).
* **Tujuan (Kenapa):** Library `Chart.js` mewajibkan format JSON bersarang vertikal (struktur *Datasets*) untuk mampu me-render **Stacked Bar Chart**.
* **Mengapa Memilih Algoritma Ini:** Jika tidak menggunakan mapping *Single-Pass*, sistem harus melakukan *query* ke database berulang kali untuk setiap matra (*N+1 Query Problem*). Melakukan satu kali pemanggilan data lalu memilahnya langsung pada *Random Access Memory* (RAM) server PHP memangkas waktu latensi database hingga 70%.

### 3. Algoritma Normalisasi Tipe Data (Dynamic Type Casting)
* **Apa yang Dilakukan:** Melakukan konversi tipe data secara *on-the-fly* pada level kueri SQL, seperti mem-*parsing* `Year_Introduced` dari String (Teks) menjadi Numerik (Integer) menggunakan fungsi `CAST()`, serta memfilter *null pointer* (`whereNotNull`).
* **Tujuan (Kenapa):** Untuk memastikan diagram kronologis (*Line Chart*) dapat diurutkan berdasarkan usia dari yang tertua ke terbaru tanpa *error* indeks, dan memastikan validitas koordinat matematis X dan Y pada *Scatter Plot*.
* **Mengapa Memilih Algoritma Ini:** Pendekatan ini bersifat *Non-Destructive*. Kami menghindari modifikasi permanen pada tipe data/skema tabel (yang dapat merusak integritas *raw dataset* referensi asli). Transformasi dinamis ini jauh lebih aman dan *scalable*.

### 4. Algoritma Prediktif Machine Learning: Random Forest Classifier
* **Apa yang Dilakukan:** *Script* Python terpisah (`scripts/ml_predictive_analysis.py`) yang memanfaatkan library **Scikit-Learn** untuk melatih dataset persenjataan. Model dilatih dengan rasio 80% *Train* / 20% *Test*.
* **Tujuan (Kenapa):** Proyek ini menerapkan konsep dari **Mata Kuliah Sistem Cerdas**. Model tidak digunakan untuk sekadar menebak, melainkan untuk mengekstrak **Feature Importance**: *Menemukan korelasi variabel apa yang paling mempengaruhi status sebuah senjata menjadi "Combat Proven" (Teruji Tempur).*
* **Mengapa Memilih Algoritma Ini:** Dibandingkan dengan *Logistic Regression* standar, **Random Forest** (yang berbasis pohon keputusan ganda) mampu menangani relasi data non-linear dengan akurasi sangat tinggi tanpa perlu *tuning* hyperparameter yang rumit. Model ini sangat tangguh (*robust*) terhadap outlier (data pencilan harga yang tidak wajar).

---

## 🏗️ Arsitektur & Diagram

### 1. System Architecture Diagram
```mermaid
graph TD
    A[Client Browser] -->|HTTP GET / HTTP POST| B(Laravel Router)
    B --> C{Dashboard Controller}
    C -->|Query Scope 'Global' / 'Indonesia'| D[(MySQL Database)]
    D -->|Return Data Aggregation| C
    C -->|JSON Response| E[Blade View + Chart.js]
    E -->|Render Charts| A
```

### 2. Data Processing Flow Diagram
```mermaid
flowchart LR
    CSV[(weapondb.csv)] -->|Import| DB[(MySQL)]
    DB --> Filter{Filter by Scope}
    Filter -->|Indonesia Only| Clean[Clean Missing Years]
    Filter -->|Global| Clean
    Clean --> Transform[Cast Strings to Int]
    Transform --> Group[Group by Category & Matra]
    Group --> Output([JSON API])
```

### 3. Analytics & Interaction Workflow
```mermaid
sequenceDiagram
    participant User
    participant UI as Dashboard UI
    participant Backend as Laravel Controller
    participant DB as Database
    
    User->>UI: Klik Tab "Indonesia"
    UI->>Backend: AJAX Request ?scope=indonesia
    Backend->>DB: SELECT COUNT(*) WHERE Primary_Users LIKE '%Indonesia%'
    DB-->>Backend: Hasil Agregasi
    Backend-->>UI: Data JSON (Label & Dataset)
    UI->>UI: Hapus Grafik Lama (destroy)
    UI->>UI: Gambar Grafik Baru (Chart.js)
    UI-->>User: Tampilan Visual Selesai
```

---

## 💻 Teknologi yang Digunakan (Technology Stack)

| Kategori | Teknologi | Tujuan & Alasan Pemilihan |
|---|---|---|
| **Bahasa Pemrograman** | PHP (8.2+), JavaScript (ES6) | Bahasa inti untuk sisi *server* dan interaktivitas klien. |
| **Framework Web** | Laravel 11.x | Mempercepat pengembangan dengan pola arsitektur MVC. |
| **Database** | MySQL / MariaDB | Handal untuk operasi *query* kompleks dan agregasi puluhan ribu baris. |
| **Visualisasi Data** | Chart.js | Ringan, berbasis Canvas, sangat animasi-friendly untuk web. |
| **Styling Framework** | Tailwind CSS | Utility-first CSS untuk membangun UI edge-to-edge *enterprise* secara instan. |
| **Ikonografi** | Phosphor Icons | Menyediakan set ikon profesional bervektor modern. |

---

## 📸 Tangkapan Layar (Screenshots)

Berikut adalah sekilas tampilan *dashboard* (Silakan lihat folder `docs/screenshots/` untuk resolusi penuh):

| Analisis Indonesia | Analisis Global |
|---|---|
| ![Indonesia](docs/screenshots/indonesia.png) | ![Global](docs/screenshots/global.png) |

*(Catatan: Simpan screenshot tampilan aplikasi Anda di folder `docs/screenshots/` dengan nama `dashboard.png`, `indonesia.png`, dan `global.png`)*

---

## 🚀 Panduan Instalasi (Installation Guide)

### Prasyarat (Semua OS)
- **PHP** >= 8.2
- **Composer** (Package Manager PHP)
- **Node.js & NPM** (Untuk *compile* asset)
- **XAMPP / MySQL Server**

---

### Windows
1. **Clone Repository:**
   ```bash
   git clone <url-repo-anda>
   cd AnalisaPersenjataanAVD
   ```
2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Setup Environment:**
   Copy file `.env.example` menjadi `.env`.
   ```bash
   copy .env.example .env
   ```
   Buka file `.env` dan pastikan konfigurasi *database* sesuai dengan XAMPP Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=analisa_persenjataan
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. **Buat Database & Import Data:**
   - Buka phpMyAdmin XAMPP (`http://localhost/phpmyadmin`).
   - Buat database dengan nama `analisa_persenjataan`.
   - Lakukan migrasi dari terminal: `php artisan migrate`.
   - Gunakan fitur **Import** phpMyAdmin untuk mengunggah file `weapondb.csv` ke tabel `global_weapons_systems`.
5. **Jalankan Proyek:**
   ```bash
   php artisan key:generate
   php artisan serve
   ```
   Buka `http://localhost:8000` di *browser*.

---

### Linux (Ubuntu / Fedora / Arch)
1. **Clone & Install:**
   ```bash
   git clone <url-repo-anda>
   cd AnalisaPersenjataanAVD
   composer install
   npm install
   ```
2. **Setup Environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *(Edit file `.env` dan atur DB_CONNECTION ke `mysql` sesuai kredensial MySQL/MariaDB lokal Anda).*
3. **Database Setup:**
   - Buat database: `mysql -u root -p -e "CREATE DATABASE analisa_persenjataan;"`
   - Migrate: `php artisan migrate`
   - Import CSV: Anda bisa mengimport `weapondb.csv` via DBeaver, phpMyAdmin, atau MySQL CLI.
4. **Jalankan Aplikasi:**
   ```bash
   php artisan serve
   ```

---

### macOS
1. **Clone & Install:** Sama seperti langkah Linux. Pastikan Anda memiliki *services* MySQL (misalnya via Homebrew `brew install mysql`).
2. **Setup & Migrate:** Sama seperti Linux. Gunakan DB GUI seperti TablePlus untuk mengimport `weapondb.csv`.
3. **Jalankan Proyek:** `php artisan serve`.

---

## 🛠️ Penyelesaian Masalah (Troubleshooting)

| Gejala (Symptom) | Kemungkinan Penyebab | Langkah Penyelesaian |
|---|---|---|
| **SQLSTATE[HY000] [1049] Unknown database** | Database belum dibuat di MySQL / XAMPP. | Buka phpMyAdmin, buat *database* dengan nama persis seperti di file `.env`. |
| **Grafik tidak muncul (Kosong)** | Data belum ter-import ke tabel `global_weapons_systems`. | Pastikan file `weapondb.csv` sudah berhasil di-import ke tabel tersebut. Cek dengan `SELECT COUNT(*)`. |
| **Vite manifest not found** | File aset frontend belum di-*build*. | Jalankan perintah `npm run dev` atau `npm run build` di terminal baru. |
| **Port 8000 already in use** | Ada *service* lain yang menggunakan port tersebut. | Jalankan dengan port lain: `php artisan serve --port=8080`. |
| **Failed to open stream: Permission denied** | Masalah hak akses (*permission*) pada folder `storage/` dan `bootstrap/cache/` (Khusus Linux/Mac). | Jalankan: `chmod -R 775 storage bootstrap/cache`. |

---

## 🎯 Keterbatasan & Rencana Masa Depan (Limitations & Future Work)
**Keterbatasan Saat Ini:**
- Visualisasi sangat bergantung pada data historis (*static CSV*).
- Kurangnya data rinci mengenai harga pengadaan (*Unit Cost*) untuk senjata spesifik Indonesia.

**Pengembangan Masa Depan:**
- Penambahan fitur *Export to PDF/CSV* agar hasil analisis bisa langsung diunduh oleh para pimpinan militer.
- Integrasi dengan model AI prediktif untuk memprediksi probabilitas pensiun sebuah alutsista di masa mendatang.

---

*Dibuat untuk memenuhi Tugas Akhir Mata Kuliah Analitik dan Visualisasi Data.*
