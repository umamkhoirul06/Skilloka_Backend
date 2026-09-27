# PRD SKILLOKA — FINAL / SOURCE OF TRUTH
## Product Requirements Document — Version 3.0

**Status:** FINAL / LOCKED  
**Purpose:** Single source of truth for human developers and AI coding agents, including Antigravity.  
**Product:** Skilloka — Platform Pencarian, Pemesanan, dan Pengelolaan Pelatihan Kerja LPK  
**Initial Coverage:** Kabupaten Indramayu, dengan cakupan beberapa kecamatan sebagai pilot project.  
**Language:** Bahasa Indonesia  
**Timezone:** Asia/Jakarta (WIB)

---

# 0. ATURAN PALING PENTING UNTUK AI CODING AGENT

Dokumen ini adalah 
**SOURCE OF TRUTH UTAMA untuk implementasi aplikasi Skilloka**.

AI coding agent WAJIB membaca dokumen ini sebelum melakukan perubahan kode.

## 0.1 Platform Boundary — WAJIB DIKUNCI

Skilloka memiliki **satu backend/domain utama Laravel**, tetapi memiliki **dua aplikasi/surface utama yang berbeda konteks**:

### A. Flutter Mobile — USER SAJA

Flutter/Android adalah aplikasi untuk **User/peserta/pencari pelatihan**.

User menggunakan aplikasi untuk:
- registrasi dan login OTP;
- onboarding profil pendidikan;
- mencari LPK;
- mencari kursus;
- melihat rekomendasi;
- melihat lokasi;
- melihat detail LPK/kursus;
- booking;
- pembayaran;
- layanan subscription;
- mengikuti pelatihan;
- melihat jadwal/materi yang tersedia;
- melihat absensi pribadi;
- menerima sertifikat;
- memberi review;
- menggunakan chatbot;
- mengelola profil dan avatar;
- melihat notifikasi dan riwayat.

**DILARANG** membuat dashboard Admin LPK atau Super Admin di Flutter User.

### B. Laravel Web — ADMIN SAJA + PUBLIC WEBSITE

Laravel Web digunakan untuk:
1. **Admin LPK**
2. **Super Admin**
3. **Public Website/Landing Page**

Admin LPK mengelola operasional LPK.

Super Admin mengelola dan memverifikasi platform/LPK.

Public Website hanya untuk informasi publik, branding, dan akuisisi/registrasi LPK.

**DILARANG** mengubah Laravel Web menjadi aplikasi User yang menduplikasi seluruh Flutter User.

### C. Laravel API/Domain — SHARED CORE

Laravel menjadi pusat:
- business logic;
- authentication/authorization;
- REST API;
- database;
- booking;
- payment;
- subscription;
- course;
- LPK;
- attendance;
- certificate;
- review;
- chatbot integration;
- notification;
- audit/security logging.

Flutter mengakses backend melalui REST API.

Laravel Web boleh memakai domain/service yang sama, tetapi **business logic tidak boleh diduplikasi**.

---

# 1. SUMBER KEBENARAN DAN PRIORITAS DOKUMEN

Urutan prioritas keputusan:

1. PRD FINAL ini
2. Proposal Skilloka terbaru
3. SRS/SDD/Use Case/ERD/Flowchart/Sequence Diagram terbaru
4. Kode yang sudah ada, jika tidak bertentangan dengan dokumen di atas

Jika kode lama bertentangan dengan PRD:
- jangan mengikuti kode lama secara otomatis;
- identifikasi konflik;
- pertahankan data/fitur yang masih valid;
- refactor secara aman;
- jangan menghapus fitur valid hanya karena implementasi lama berbeda.

Jika ada requirement yang belum ditentukan:
- pilih implementasi sederhana, maintainable, aman, dan konsisten;
- jangan menambah fitur bisnis baru tanpa kebutuhan;
- jangan mengubah role boundary.

---

# 2. RINGKASAN PRODUK

Skilloka adalah platform digital yang membantu masyarakat menemukan Lembaga Pelatihan Kerja (LPK), mencari program pelatihan, melihat informasi kursus, melakukan booking dan pembayaran secara online, mengikuti proses pelatihan, memperoleh sertifikat, serta mendapatkan bantuan chatbot.

Platform juga menyediakan panel web untuk Admin LPK dan Super Admin.

Tujuan utama:
- memusatkan informasi LPK;
- membantu User menemukan pelatihan yang relevan;
- memudahkan booking dan pembayaran;
- meningkatkan kepercayaan melalui verifikasi LPK;
- membantu LPK mengelola operasional pelatihan;
- menyediakan pengalaman digital yang aman dan terstruktur.

---

# 3. SCOPE DAN BATASAN

## 3.1 Wilayah
Tahap awal:
- Kabupaten Indramayu;
- beberapa kecamatan sebagai pilot project.

Arsitektur harus tetap memungkinkan perluasan wilayah di masa depan.

## 3.2 Chatbot
Chatbot:
- menggunakan layanan AI pihak ketiga melalui API;
- membantu pertanyaan seputar LPK, kursus, booking, pembayaran, subscription, dan penggunaan Skilloka;
- tidak menjadi konsultan umum di luar konteks Skilloka/pelatihan kerja;
- tidak boleh mengarang data kursus, harga, jadwal, atau status booking;
- data transaksional harus berasal dari backend Skilloka.

## 3.3 Absensi
Absensi hanya mencatat kehadiran peserta pada sesi kursus.
Tidak mencakup biometrik, face recognition, atau geofencing wajib.

## 3.4 Subscription
Subscription adalah **layanan opsional yang terhubung dengan proses booking/payment**, bukan alur bisnis terpisah yang berdiri sendiri.

Secara data subscription tetap memiliki entity sendiri karena membutuhkan paket, periode aktif, status, hak akses, dan histori.

```text
Booking
   ↓
Pilih layanan
   ├── Booking biasa
   └── Booking + Subscription
              ↓
          Pembayaran
              ↓
      Booking terkonfirmasi
              ↓
       Subscription aktif
```

---

# 4. AKTOR DAN ROLE

## User
Pengguna/peserta yang mencari dan mengikuti pelatihan.
Akses: Flutter Mobile.

## Admin LPK
Pengelola sebuah LPK.
Akses: Laravel Web.
Hanya dapat mengelola data LPK yang menjadi tanggung jawabnya.

## Super Admin
Pengelola platform dan proses verifikasi LPK.
Akses: Laravel Web.

---

# 5. ARSITEKTUR FINAL

```text
                         SKILLOKA
                            │
                  ┌─────────┴─────────┐
                  │ Laravel Backend   │
                  │ API + Domain      │
                  │ + Database        │
                  └─────────┬─────────┘
                            │
             ┌──────────────┴──────────────┐
             │                             │
      REST API / Domain              Laravel Web
             │                             │
      ┌──────┴──────┐              ┌───────┼────────┐
      │             │              │       │        │
 Flutter Mobile   User API     Admin LPK  Super   Public
      │                         Web       Admin   Website
      │                           │          │       │
    USER                      ADMIN LPK   SUPER   PUBLIC
```

## Stack
- Backend/Web: PHP, Laravel, REST API.
- Mobile: Flutter/Dart, Android target utama.
- Database: MySQL.
- Web server: Nginx atau Apache.
- HTTPS/TLS.
- Docker.
- GitHub + GitHub Actions.
- OTP provider.
- Payment gateway.
- AI provider untuk chatbot.
- Email service bila diperlukan.

**Catatan:** beberapa dokumen lama menyebut PostgreSQL/PgAdmin. Untuk baseline implementasi PRD ini gunakan **MySQL** agar konsisten dengan rancangan implementasi terbaru.

---

# 6. FITUR FLUTTER MOBILE — USER

## Authentication
- Registrasi nomor telepon
- Request OTP
- Verify OTP
- Login
- Logout
- Session/token management

## Education Onboarding
Setelah registrasi/login pertama dan profile belum lengkap, User diarahkan ke onboarding.

Data:
- tingkat pendidikan: SMA / SMK / Perguruan Tinggi;
- jurusan/program studi;
- tahun lulus opsional;
- bidang/minat karier;
- lokasi yang diinginkan.

Onboarding **tidak mengunci** User dari kursus lain. Fungsinya untuk personalisasi rekomendasi.

## Home
- sapaan;
- rekomendasi;
- kategori;
- LPK terverifikasi;
- kursus populer;
- promo;
- shortcut pencarian;
- status booking aktif;
- notifikasi penting.

## Search
- cari LPK;
- cari kursus;
- filter kategori;
- filter lokasi;
- filter harga;
- filter jadwal bila tersedia;
- sorting.

## Detail LPK
- nama;
- logo/foto;
- status verifikasi;
- deskripsi;
- alamat;
- lokasi/map;
- kontak;
- fasilitas;
- galeri;
- daftar kursus;
- rating/review.

## Detail Kursus
- nama;
- LPK;
- kategori;
- deskripsi;
- harga;
- durasi;
- kuota;
- jadwal;
- lokasi;
- fasilitas;
- status slot;
- informasi subscription bila relevan.

## Booking

```text
Pilih Kursus
   ↓
Pilih Jadwal
   ↓
Cek Slot
   ↓
Isi/Konfirmasi Data
   ↓
Pilih Layanan
   ├── Booking biasa
   └── Booking + Subscription
   ↓
Review
   ↓
Pembayaran
   ↓
Payment Gateway
   ↓
Server menerima status pembayaran
   ↓
Booking confirmed
   ↓
Notifikasi
```

Server adalah sumber kebenaran status booking.

## Payment
- membuat payment transaction;
- membuka flow payment gateway;
- menerima callback/webhook;
- verifikasi signature;
- update status server-side;
- idempotent handling.

Flutter **tidak boleh** menetapkan sendiri payment menjadi PAID atau booking menjadi CONFIRMED.

## Subscription
- melihat paket;
- melihat harga/benefit;
- membeli sesuai flow;
- melihat status;
- periode aktif;
- histori.

## Learning
- kursus yang diikuti;
- jadwal;
- content/materi;
- status pembelajaran;
- absensi pribadi;
- sertifikat.

## Certificate
- melihat sertifikat;
- membuka detail;
- mengunduh/menampilkan bila tersedia;
- melihat status penerbitan.

## Review
Review diberikan setelah kondisi bisnis yang ditentukan terpenuhi, misalnya kursus selesai.

## Chatbot
- percakapan;
- loading;
- error/fallback;
- histori bila disimpan.

Chatbot harus menggunakan data backend bila menjawab data Skilloka dan tidak boleh mengarang.

## Avatar
- galeri avatar;
- pilih avatar;
- ganti avatar;
- foto profil tetap didukung bila tersedia.

---

# 7. LARAVEL WEB — ADMIN LPK

## Login
- email/password;
- forgot password;
- session security;
- authorization berdasarkan LPK.

Admin LPK pending/rejected tidak dapat mengakses dashboard operasional.

## Registrasi LPK

```text
Step 1 — Akun
   ↓
Step 2 — Legalitas
   ↓
Step 3 — Detail LPK
   ↓
Submit
   ↓
Pending Verification
   ↓
Super Admin Review
   ├── Approve → Active
   └── Reject → Rejected + reason
```

## Dashboard
Action-oriented:
- booking;
- peserta aktif;
- kursus aktif;
- agenda/jadwal terdekat;
- required actions;
- booking terbaru;
- aktivitas terbaru.

Maksimum 3–4 summary metrics dan 1–2 chart yang benar-benar berguna.

## Kelola Profil LPK
- identitas;
- logo;
- deskripsi;
- alamat;
- lokasi;
- kontak;
- fasilitas;
- galeri;
- dokumen.

## Kelola Kursus
CRUD:
- nama;
- kategori;
- deskripsi;
- harga;
- durasi;
- kuota;
- status;
- thumbnail;
- persyaratan;
- content/materi.

## Kelola Jadwal
- membuat;
- mengubah;
- membatalkan/menghapus sesuai aturan;
- kapasitas;
- peserta.

## Kelola Booking
- melihat booking milik LPK;
- detail;
- status pembayaran;
- status booking yang diizinkan;
- peserta;
- pembatalan sesuai business rules.

## Kelola Siswa
- daftar peserta;
- detail yang relevan;
- kursus;
- booking;
- absensi.

## Kelola Content
- materi;
- judul;
- deskripsi;
- file/link;
- urutan;
- status publish.

## Kelola Absensi
- pilih kursus/jadwal;
- peserta;
- hadir/tidak hadir;
- rekap.

## Kelola Sertifikat
- penerbitan;
- status;
- nomor/identitas;
- file jika digunakan.

## Pembayaran
Admin hanya melihat transaksi yang berkaitan dengan LPK dan tidak boleh memalsukan status pembayaran.

## Laporan
- booking;
- peserta;
- kursus;
- pembayaran;
- absensi;
- sertifikat.

---

# 8. LARAVEL WEB — SUPER ADMIN

## Dashboard
Fokus pada:
- verifikasi;
- monitoring;
- tindakan penting;
- aktivitas platform.

Bukan dashboard analytics berlebihan.

## Verifikasi LPK
- antrean;
- detail;
- dokumen;
- approve;
- reject;
- alasan;
- histori.

## Kelola LPK
- search;
- filter;
- detail;
- status;
- suspend/activate sesuai rules.

## Kelola User
- daftar;
- status;
- kebutuhan support;
- minimalkan akses data privat.

## Kelola Admin LPK
- daftar;
- status;
- relasi LPK;
- aktivasi/deaktivasi sesuai rules.

## Kelola Kategori
CRUD kategori.

## Monitoring
- booking;
- payment;
- subscription;
- aktivitas;
- security/audit logs sesuai hak akses.

---

# 9. PUBLIC WEBSITE

Public Website **bukan aplikasi User**.

Tujuan:
- memperkenalkan Skilloka;
- menjelaskan manfaat;
- kategori;
- menampilkan LPK publik yang terverifikasi;
- menarik LPK untuk bergabung;
- registrasi LPK;
- login Admin/Super Admin.

Struktur:
```text
Navbar
 ├── Beranda
 ├── Tentang Skilloka
 ├── Pelatihan/Kategori
 ├── LPK
 ├── Untuk LPK
 ├── FAQ
 └── Login/Daftar LPK

Hero
Purpose
How It Works
Categories
Verified LPK
For LPK
FAQ
Footer
```

Jangan membuat public web menjadi salinan Home Flutter.

---

# 10. REKOMENDASI KURSUS

Rule-based baseline:

- +40 kesesuaian tujuan/minat karier;
- +25 kesesuaian jurusan/bidang;
- +15 kesesuaian lokasi;
- +10 kesesuaian preferensi harga;
- +10 popularitas/ketersediaan.

Bobot berada di satu service/configuration.

```text
score(course, profile):
    score = 0
    if career_goal_matches: score += 40
    if education_field_matches: score += 25
    if location_matches: score += 15
    if price_matches: score += 10
    if course_is_available_or_popular: score += 10
    return score
```

Recommendation hanya membantu discovery dan tidak membatasi pencarian.

---

# 11. DATA MODEL / ERD TARGET

Entity utama:
```text
users
roles / role mechanism
education_profiles
lpk
categories
courses
schedules
bookings
payments
subscriptions
contents
attendances
certificates
reviews
avatars
chatbot_logs
notifications
lpk_verifications
audit_logs
```

Relasi:
```text
User
 ├── EducationProfile
 ├── Bookings
 ├── Subscriptions
 ├── Reviews
 ├── Certificates
 ├── Notifications
 ├── Avatar
 └── ChatbotLogs

LPK
 ├── Admin
 ├── Courses
 ├── Schedules
 ├── Bookings
 ├── Contents
 ├── Attendances
 ├── Certificates
 └── LPKVerification

Category
 └── Courses

Course
 ├── Schedules
 ├── Contents
 ├── Bookings
 └── Reviews

Booking
 ├── Payment
 ├── Attendance
 ├── Certificate
 └── optional Subscription relationship
```

Gunakan foreign key, index, unique constraint, timestamps, dan soft delete hanya jika memang dibutuhkan.

---

# 12. STATE MACHINE

## LPK
```text
PENDING → APPROVED
        └→ REJECTED
```

## Booking
```text
PENDING_PAYMENT
      ↓
PAID / CONFIRMED
      ↓
ONGOING
      ↓
COMPLETED
```

Alternatif:
```text
PENDING_PAYMENT → EXPIRED
CONFIRMED → CANCELLED
```

## Payment
```text
PENDING → PAID
        ├→ FAILED
        └→ EXPIRED
```

## Subscription
```text
PENDING → ACTIVE → EXPIRED
```

CANCELLED dapat digunakan sesuai business rules.

Status tidak boleh diubah sembarangan dari client.

---

# 13. API CONTRACT

Prefix:
`/api/v1`

## Public
```http
GET    /lpk
GET    /lpk/{id}
GET    /courses
GET    /courses/{id}
GET    /categories
GET    /locations
POST   /lpk/register
```

## Auth User
```http
POST /auth/request-otp
POST /auth/verify-otp
POST /auth/logout
GET  /auth/me
```

## Profile
```http
GET    /profile
PUT    /profile
GET    /profile/education
PUT    /profile/education
PUT    /profile/avatar
```

## Recommendation
```http
GET /recommendations/courses
```

## Booking
```http
GET  /bookings
GET  /bookings/{id}
POST /bookings
POST /bookings/{id}/cancel
```

## Payment
```http
POST /payments
GET  /payments/{id}
POST /payments/webhook
```

## Subscription
```http
GET /subscriptions
GET /subscriptions/packages
GET /subscriptions/status
```

Purchase subscription mengikuti aturan booking/payment.

## Learning
```http
GET /learning
GET /learning/{courseId}
GET /learning/{courseId}/contents
GET /learning/{courseId}/attendance
GET /learning/{courseId}/certificate
```

## Review
```http
POST /courses/{courseId}/reviews
GET  /courses/{courseId}/reviews
```

## Chatbot
```http
POST /chatbot/message
GET  /chatbot/history
```

## Admin LPK
```http
GET/PUT /admin/lpk/profile
CRUD    /admin/courses
CRUD    /admin/schedules
GET     /admin/bookings
GET     /admin/students
CRUD    /admin/contents
CRUD    /admin/attendance
CRUD    /admin/certificates
GET     /admin/payments
GET     /admin/reports
```

## Super Admin
```http
GET/PATCH /super-admin/lpks
GET/PATCH /super-admin/verifications
CRUD      /super-admin/categories
GET/PATCH /super-admin/users
GET       /super-admin/logs
GET       /super-admin/monitoring
```

Nama endpoint boleh disesuaikan untuk REST yang konsisten, tetapi role boundary tidak boleh berubah.

---

# 14. AUTHORIZATION

Minimum role:
```text
USER
ADMIN_LPK
SUPER_ADMIN
```

Aturan:
- User hanya resource miliknya;
- Admin LPK hanya LPK yang menjadi tanggung jawabnya;
- Super Admin akses platform sesuai permission;
- jangan percaya `user_id`, `lpk_id`, atau role dari client;
- authorization server-side.

---

# 15. CLEAN CODE BACKEND

Target:
```text
app/
├── Actions/
├── Enums/
├── Exceptions/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Policies/
├── Services/
├── Rules/
└── Support/

database/
├── migrations/
├── seeders/
└── factories/

routes/
├── api.php
└── web.php

resources/
└── views/
```

Prinsip:
- controller tipis;
- validation di Form Request;
- authorization di Policy/middleware;
- business logic di service/action/domain layer;
- API output melalui Resource;
- jangan copy-paste logic;
- hindari God Controller/Model;
- enum untuk state penting;
- transaction untuk operasi multi-step;
- queue untuk pekerjaan asynchronous yang sesuai.

---

# 16. CLEAN CODE FLUTTER

```text
lib/
├── core/
│   ├── network/
│   ├── storage/
│   ├── theme/
│   ├── routing/
│   └── utils/
├── features/
│   ├── auth/
│   ├── onboarding/
│   ├── home/
│   ├── search/
│   ├── lpk/
│   ├── course/
│   ├── booking/
│   ├── payment/
│   ├── subscription/
│   ├── learning/
│   ├── certificate/
│   ├── review/
│   ├── chatbot/
│   └── profile/
└── main.dart
```

Hindari:
- widget terlalu besar;
- API call langsung di UI;
- duplicate model;
- hardcoded URL;
- state logic bercampur dengan presentation.

---

# 17. UI/UX

UI harus:
- modern;
- profesional;
- clean;
- human-designed;
- tidak terlihat template AI;
- tidak berlebihan gradient;
- tidak berlebihan card;
- tidak penuh chart;
- konsisten;
- accessible.

Mobile:
- readability;
- hierarchy;
- thumb-friendly;
- loading/skeleton;
- empty/error state;
- network handling;
- bottom navigation konsisten.

Web:
- responsive desktop-first;
- sidebar;
- topbar;
- table;
- filter;
- modal/drawer secukupnya;
- confirmation destructive action;
- pagination.

---

# 18. UI STATES

Setiap halaman penting:
```text
LOADING
SUCCESS
EMPTY
ERROR
UNAUTHORIZED
FORBIDDEN
NOT_FOUND
NETWORK_ERROR
```

Jangan blank screen.

---

# 19. SECURITY — OWASP TOP 10:2025 BASELINE

## A01 Broken Access Control
RBAC, Policy, ownership check, IDOR prevention, server-side authorization.

## A02 Security Misconfiguration
production debug off, secure headers, controlled CORS, upload restrictions, secure Docker, no exposed secrets.

## A03 Software Supply Chain Failures
lock dependencies, audit packages, review dependencies, pin CI actions, protect CI secrets.

## A04 Cryptographic Failures
HTTPS, password hashing, secure token handling, no secret logging.

## A05 Injection
ORM/query binding, validation, output escaping, XSS protection, chatbot input untrusted.

## A06 Insecure Design
state machines, server-side business rules, server-authoritative payment, ownership enforcement.

## A07 Authentication Failures
OTP expiration, one-time OTP, rate limit, login throttling, token revocation.

## A08 Software/Data Integrity Failures
webhook signature verification, transactions, idempotency, tests.

## A09 Logging/Alerting Failures
audit/security logs, failed auth, verification, payment events, tanpa password/OTP/token.

## A10 Mishandling Exceptional Conditions
centralized exceptions, safe errors, rollback, timeout, safe retry/fallback.

---

# 20. PAYMENT SECURITY

```text
Flutter
   ↓
Laravel create transaction
   ↓
Payment Gateway
   ↓
User pays
   ↓
Gateway webhook
   ↓
Laravel verifies signature
   ↓
Laravel updates payment
   ↓
Laravel updates booking
   ↓
Notification
   ↓
Flutter refreshes status
```

Flutter tidak boleh menetapkan `PAID` atau `CONFIRMED` sendiri.

---

# 21. CHATBOT SECURITY

Chatbot bukan trusted backend.

AI output dianggap untrusted.

Jika bertanya kursus:
- gunakan data backend Skilloka;
- jangan mengarang.

Jika meminta booking/data User lain:
- authorization tetap berlaku.

Prompt injection tidak boleh mengubah permission.

Jangan kirim password, OTP, token, secret, atau data User lain ke AI provider.

---

# 22. FILE UPLOAD

Validasi:
- MIME;
- extension;
- size;
- filename;
- storage path.

Jangan percaya extension client.

File privat seperti sertifikat/dokumen legal harus melalui authorization.

---

# 23. PERFORMANCE

Backend:
- foreign key/index;
- pagination;
- eager loading;
- N+1 prevention;
- query optimization;
- caching jika diperlukan.

Flutter:
- debounce search;
- image caching;
- pagination;
- skeleton;
- avoid unnecessary rebuild.

Hindari premature optimization.

---

# 24. CODEBASE AUDIT

Jika repository sudah memiliki kode:

1. scan struktur;
2. identifikasi Laravel;
3. identifikasi Flutter;
4. identifikasi route;
5. controller;
6. model;
7. service;
8. API;
9. migration;
10. asset;
11. dependency;
12. duplicate/dead code.

Klasifikasi:
```text
USED
UNUSED
DUPLICATE
LEGACY
UNKNOWN
```

**UNKNOWN tidak boleh dihapus tanpa verifikasi.**

Sebelum membuat file baru, cari implementasi serupa dan reuse/refactor bila memungkinkan.

---

# 25. DOCKER

Target:
```text
docker/
├── app
├── web
├── database
└── optional redis
```

Harus reproducible, environment-based, tidak hardcode secret, dan memiliki health check.

---

# 26. GITHUB ACTIONS / CI-CD

```text
Push / Pull Request
        ↓
Install dependencies
        ↓
Lint / Static checks
        ↓
Unit / Feature tests
        ↓
Build
        ↓
Security/dependency checks
        ↓
Deploy jika branch/environment sesuai
```

Credential deployment tidak boleh disimpan di repository.

---

# 27. TESTING

Backend:
- unit;
- feature/API;
- authorization;
- validation;
- payment webhook;
- booking state;
- subscription state.

Flutter:
- widget;
- service/repository;
- integration untuk flow penting.

Critical flows:
1. OTP
2. onboarding
3. search
4. booking
5. payment
6. subscription
7. attendance
8. certificate
9. chatbot
10. LPK verification
11. authorization

---

# 28. OBSERVABILITY

Sediakan:
- application logs;
- security audit logs;
- payment logs;
- error monitoring;
- health endpoint;
- basic performance monitoring.

Health:
`/health`

Tidak boleh membocorkan secret/data sensitif.

---

# 29. MASTER USER FLOW

```text
START
  ↓
Install/Open Flutter
  ↓
Login/Register
  ↓
OTP Verification
  ↓
First Login?
  ├── YES → Education Onboarding
  └── NO
  ↓
HOME
  ↓
Search / Recommendation / Category
  ↓
LPK
  ↓
Course Detail
  ↓
Choose Schedule
  ↓
Check Slot
  ├── Full → Choose Another Schedule
  └── Available
          ↓
      Booking Form
          ↓
      Choose Service
       ├── Regular
       └── + Subscription
          ↓
       Review
          ↓
       Payment
          ↓
      Payment Status
       ├── Failed/Expired → Retry/Cancel
       └── Paid
             ↓
        Booking Confirmed
             ↓
       Notification
             ↓
       Training
             ↓
       Attendance
             ↓
       Course Completed
             ↓
        Certificate
             ↓
          Review
```

---

# 30. MASTER ADMIN FLOW

```text
Public Website
      ↓
LPK Registration
      ↓
Pending
      ↓
Super Admin
      ↓
Review
 ├── Reject
 └── Approve
        ↓
   Admin LPK Login
        ↓
   LPK Dashboard
        ↓
 ┌──────┼────────┬─────────┐
 ↓      ↓        ↓         ↓
Course Schedule Booking Students
 ↓      ↓        ↓         ↓
Content Attendance Payment Certificate
```

---

# 31. DEVELOPMENT ORDER

## Phase 0 — Audit
Scan repository dan mapping implementation.

## Phase 1 — Foundation
Environment, database, auth, roles, API, Flutter architecture.

## Phase 2 — User Core
OTP, profile, onboarding, LPK/course search, detail.

## Phase 3 — Transaction
Schedule, booking, payment, subscription.

## Phase 4 — Learning
Content, attendance, certificate, review.

## Phase 5 — Chatbot
AI integration, scoped context, logging, security.

## Phase 6 — Admin
Admin LPK, Super Admin, verification, management.

## Phase 7 — Public Website
Landing, LPK information, registration, login.

## Phase 8 — Hardening
Security, tests, performance, audit, Docker, CI/CD.

---

# 32. INSTRUKSI KHUSUS ANTIGRAVITY / AI CODING AGENT

Sebelum coding:

```text
1. READ THIS PRD COMPLETELY.
2. SCAN THE EXISTING REPOSITORY.
3. IDENTIFY CURRENT ARCHITECTURE.
4. MAP EXISTING FEATURES TO THIS PRD.
5. FIND CONFLICTS.
6. DO NOT CREATE DUPLICATE MODULES.
7. IMPLEMENT THE SMALLEST CLEAN CHANGE.
8. TEST THE CHANGE.
9. RECHECK ROLE BOUNDARIES.
10. CLEAN UP ONLY VERIFIED DEAD CODE.
11. REPORT WHAT WAS CHANGED.
```

## Hard constraints

Agent **MUST NOT**:
- membuat User dashboard di Laravel Admin Web;
- membuat Admin LPK dashboard di Flutter User;
- memindahkan fitur User ke Admin Web tanpa alasan;
- membuat business logic booking/payment ganda;
- membuat subscription sebagai transaksi yang tidak terkait booking/payment;
- mempercayai status payment dari client;
- bypass authorization;
- membuat chatbot menjadi sumber kebenaran transaksi;
- membuat fitur baru hanya karena terlihat bagus;
- mengganti stack tanpa alasan;
- menghapus kode yang belum diaudit;
- membuat duplicate controller/service/model;
- mengubah role boundary;
- mengubah business rules tanpa mendokumentasikan konflik.

## Agent SHOULD
- reuse existing valid implementation;
- refactor daripada duplicate;
- menjaga API contract;
- menjaga database integrity;
- menulis tests untuk perubahan;
- menjaga UI konsisten;
- menggunakan reusable components;
- memberi laporan singkat setelah pekerjaan.

---

# 33. DEFINITION OF DONE

Fitur selesai apabila:
- requirement sesuai PRD;
- role access benar;
- API benar;
- validation tersedia;
- authorization tersedia;
- UI memiliki loading/error/empty state;
- database aman;
- test relevan tersedia;
- tidak ada duplicate implementation;
- tidak merusak fitur existing;
- tidak ada hardcoded secret;
- tidak ada debug code;
- lint/static checks lolos;
- test relevan lolos;
- dokumentasi diperbarui bila diperlukan.

---

# 34. TRACEABILITY

```text
Proposal
   ↓
PRD FINAL
   ↓
SRS
   ↓
Use Case
   ↓
BPMN / Flowchart
   ↓
Class Diagram
   ↓
ERD
   ↓
Sequence Diagram
   ↓
API Contract
   ↓
UI/UX
   ↓
Implementation
   ↓
Testing
```

Jika ada perbedaan, PRD FINAL menjadi baseline implementasi dan perubahan harus dicatat.

---

# 35. KEPUTUSAN YANG DIKUNCI

1. Flutter = User application.
2. Laravel Web = Admin LPK + Super Admin + Public Website.
3. Laravel = shared backend/domain/API.
4. User tidak mendapatkan Admin Web dashboard.
5. Admin tidak menggunakan Flutter User app sebagai dashboard operasional.
6. Booking adalah transaksi inti.
7. Subscription adalah layanan opsional yang terhubung dengan booking/payment dan tetap memiliki entity sendiri.
8. Education onboarding digunakan untuk personalisasi rekomendasi.
9. Recommendation tidak mengunci User dari kursus lain.
10. Chatbot menggunakan AI pihak ketiga tetapi tidak menjadi sumber kebenaran transaksi.
11. Absensi adalah pencatatan kehadiran sesi, bukan biometrik/geofencing wajib.
12. LPK harus diverifikasi Super Admin sebelum aktif.
13. Laravel API menggunakan versioning `/api/v1`.
14. MySQL menjadi database baseline.
15. Security baseline menggunakan OWASP Top 10:2025.
16. Docker dan GitHub Actions menjadi target development/deployment workflow.
17. Clean code dan codebase audit adalah bagian dari Definition of Done.
18. UI harus modern, profesional, dan tidak generik/AI-looking.
19. Tidak ada penambahan fitur bisnis besar tanpa keputusan tim.
20. Semua implementasi harus menjaga batas role dan platform.

---

# 36. FINAL ARCHITECTURE SUMMARY

```text
                    SKILLOKA
                       │
              ┌────────┴────────┐
              │ Laravel Backend │
              │ API + Domain    │
              │ MySQL           │
              └────────┬────────┘
                       │
          ┌────────────┼─────────────┐
          │            │             │
          ▼            ▼             ▼
     Flutter        Laravel       Laravel
      Mobile          Web          Public
          │            │             │
        USER       ADMIN LPK /     PUBLIC /
                   SUPER ADMIN       LPK
```

**Presentation layer berbeda, tetapi business logic dan data tetap terpusat.**

PRD ini adalah baseline implementasi. Setiap perubahan harus menjaga role boundary, business rules, data integrity, security, dan traceability.
