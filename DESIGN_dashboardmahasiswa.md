---
name: Heritage
colors:
    primary: "#1A1C1E"
    secondary: "#6C7278"
    tertiary: "#B8422E"
    neutral: "#F7F5F2"
typography:
    h1:
        fontFamily: Public Sans
        fontSize: 3rem
    body-md:
        fontFamily: Public Sans
        fontSize: 1rem
    label-caps:
        fontFamily: Space Grotesk
        fontSize: 0.75rem
rounded:
    sm: 4px
    md: 8px
spacing:
    sm: 8px
    md: 16px
---

# Dokumen Desain Dashboard Mahasiswa - InternLog

Dokumen ini menerapkan tema **Heritage** secara spesifik untuk **dashboard internal mahasiswa** (halaman setelah login) — berbeda dari `DESIGN_LandingPage.md` yang murni marketing. Di sinilah tampilan aplikasi/data sungguhan boleh muncul: tabel, kartu status, formulir, kalender aktivitas.

Referensi skema data yang jadi dasar tampilan:

- `users` — kolom `role` (`mahasiswa`/`mentor`) menentukan apa yang tampil di sidebar.
- `mentor_student` — relasi 1 mentor ke banyak mahasiswa, tapi 1 mahasiswa hanya 1 mentor (`student_id` unik). Artinya dashboard mahasiswa **selalu menampilkan tepat satu mentor**, tidak perlu UI pemilihan mentor.
- `logbooks` — entitas inti: `activity_date`, `activity_detail`, `challenges`, `is_holiday`, `holiday_name`, `status` (pending/approved/rejected), `mentor_note`.
- `logbook_documents` — lampiran per logbook (banyak dokumen per satu entri).

Prinsip visual tetap sama seperti dokumen sebelumnya: **tanpa gradient**, kombinasi warna lewat blok solid & border tegas, aksen `#B8422E` dijatah hemat untuk status kritis/aksi utama saja.

---

## 1. Konsep Dashboard Mahasiswa

Jika landing page meniru **halaman depan koran**, dashboard mahasiswa meniru **buku agenda/jurnal kerja pribadi** — bukan admin panel generik dengan sidebar gelap dan banyak ikon berwarna-warni.

- Latar tetap `neutral` (`#F7F5F2`), bukan putih polos atau dashboard bertema gelap — konsisten dari halaman publik ke halaman privat.
- Sidebar tipis, tanpa ikon berwarna-warni; hanya `primary` untuk item aktif.
- Fokus utama layar: **entri logbook hari ini** dan **status logbook terakhir**, bukan grafik/statistik berat yang tidak relevan untuk mahasiswa.

---

## 2. Struktur Layout

```
┌───────────┬────────────────────────────────────────────┐
│           │  Topbar: nama mahasiswa · nama mentor · +Log│
│  Sidebar  ├────────────────────────────────────────────┤
│           │  Ringkasan (3 kartu status)                 │
│  • Ringkasan│                                            │
│  • Logbook  │  Logbook Hari Ini (CTA jika belum diisi)   │
│  • Riwayat  │                                            │
│  • Dokumen  │  Riwayat Logbook (tabel/list)               │
│  • Profil   │                                            │
└───────────┴────────────────────────────────────────────┘
```

- **Sidebar** (`w-60`, `border-r border-slate-900/10`, latar `neutral`): logo kecil InternLog di atas, lalu 5 menu: Ringkasan, Logbook (form entri), Riwayat, Dokumen, Profil. Item aktif ditandai `border-l-2 border-slate-900` + teks lebih tebal — bukan latar warna solid mencolok.
- **Topbar** menampilkan nama mahasiswa + nama mentor pembimbing (karena relasinya 1:1, tampilkan langsung tanpa dropdown), dan tombol utama `+ Logbook Baru` di kanan atas memakai `bg-slate-900`.

```html
<aside class="w-60 border-r border-slate-900/10 bg-[#F7F5F2] min-h-screen p-6">
  <p class="font-mono text-xs uppercase tracking-wider text-slate-500 mb-8">InternLog</p>
  <nav class="space-y-1 text-sm">
    <a class="block border-l-2 border-slate-900 pl-3 py-1.5 font-medium text-slate-900">Ringkasan</a>
    <a class="block border-l-2 border-transparent pl-3 py-1.5 text-slate-600 hover:text-slate-900">Logbook</a>
    <a class="block border-l-2 border-transparent pl-3 py-1.5 text-slate-600 hover:text-slate-900">Riwayat</a>
    <a class="block border-l-2 border-transparent pl-3 py-1.5 text-slate-600 hover:text-slate-900">Dokumen</a>
    <a class="block border-l-2 border-transparent pl-3 py-1.5 text-slate-600 hover:text-slate-900">Profil</a>
  </nav>
</aside>
```

```html
<header class="flex items-center justify-between border-b border-slate-900/10 px-8 py-5">
  <div>
    <p class="text-sm font-semibold text-slate-900">Halo, Raka Pradipta</p>
    <p class="text-xs text-slate-500">Mentor: Dr. Andi Wijaya</p>
  </div>
  <button class="bg-slate-900 text-[#F7F5F2] text-sm font-medium px-4 py-2 rounded-sm hover:bg-slate-800">
    + Logbook Baru
  </button>
</header>
```

---

## 3. Ringkasan (3 Kartu Status)

Turunan langsung dari kolom `status` di tabel `logbooks`. Tiga kartu, bukan grafik donat/bar berwarna — angka besar + label, gaya "papan skor jurnal".

```html
<section class="grid grid-cols-3 gap-4 px-8 py-6">
  <div class="border border-slate-900/15 rounded-md p-5">
    <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Approved</p>
    <p class="text-3xl font-bold text-slate-900 mt-2">24</p>
  </div>
  <div class="border border-dashed border-slate-400 rounded-md p-5">
    <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Pending</p>
    <p class="text-3xl font-bold text-slate-600 mt-2">2</p>
  </div>
  <div class="border border-[#B8422E]/40 rounded-md p-5">
    <p class="font-mono text-xs uppercase tracking-wider text-[#B8422E]">Rejected</p>
    <p class="text-3xl font-bold text-[#B8422E] mt-2">1</p>
  </div>
</section>
```

Catatan: border kartu Rejected memakai `#B8422E` dengan opacity rendah (`/40`) agar tetap tegas tapi tidak berteriak — sejalan aturan "aksen dipakai sangat selektif" dari dokumen sistem.

---

## 4. Logbook Hari Ini (Status Kosong / Sudah Diisi)

Karena `activity_date` terindeks dan satu hari idealnya satu entri, dashboard perlu jelas membedakan dua kondisi:

**A. Belum mengisi logbook hari ini** — kartu ajakan, aksen `#B8422E` boleh dipakai di sini karena ini kondisi yang butuh perhatian:

```html
<div class="mx-8 border border-[#B8422E] rounded-md p-6 flex items-center justify-between bg-[#F7F5F2]">
  <div>
    <p class="font-mono text-xs uppercase tracking-wider text-[#B8422E]">Belum Diisi</p>
    <p class="text-slate-900 font-semibold mt-1">Logbook hari ini, Senin 13 Juli 2026, belum kamu catat.</p>
  </div>
  <button class="bg-[#B8422E] text-[#F7F5F2] text-sm font-medium px-5 py-2.5 rounded-sm hover:opacity-90">
    Isi Sekarang
  </button>
</div>
```

**B. Sudah mengisi** — kartu netral menampilkan ringkasan singkat + badge status, tanpa tombol mencolok:

```html
<div class="mx-8 border border-slate-900/15 rounded-md p-6">
  <div class="flex items-center justify-between">
    <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Senin, 13 Juli 2026</p>
    <span class="border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase rounded-sm">Pending</span>
  </div>
  <p class="text-sm text-slate-800 mt-3 line-clamp-2">
    Melakukan setup environment development, mengikuti briefing tim, dan mereview dokumentasi API internal.
  </p>
</div>
```

---

## 5. Form Tambah/Edit Logbook

Formulir memetakan langsung ke kolom tabel `logbooks`. Layout satu kolom, label di atas field (bukan floating label bergaya modern generik — konsisten dengan nuansa "formulir arsip").

| Kolom Database | Elemen Form |
|---|---|
| `activity_date` | Date picker, default hari ini |
| `is_holiday` | Toggle "Tandai sebagai hari libur" |
| `holiday_name` | Muncul hanya jika `is_holiday` aktif (conditional field) |
| `activity_detail` | Textarea utama, wajib diisi |
| `challenges` | Textarea opsional, label "Kendala (opsional)" |
| lampiran → `logbook_documents` | Drop zone upload dokumen, multi-file |

```html
<form class="max-w-xl mx-8 space-y-6">
  <div>
    <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Tanggal Aktivitas</label>
    <input type="date" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-slate-900" />
  </div>

  <label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="checkbox" class="rounded-sm border-slate-400" />
    Tandai sebagai hari libur
  </label>

  <!-- muncul jika checkbox di atas aktif -->
  <div>
    <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Nama Hari Libur</label>
    <input type="text" placeholder="mis. Hari Kemerdekaan RI" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm" />
  </div>

  <div>
    <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Detail Aktivitas</label>
    <textarea rows="5" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm" placeholder="Ceritakan apa yang kamu kerjakan hari ini..."></textarea>
  </div>

  <div>
    <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Kendala (opsional)</label>
    <textarea rows="3" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm" placeholder="Ada hambatan yang ditemui?"></textarea>
  </div>

  <div>
    <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Lampiran Dokumen</label>
    <div class="mt-1 border border-dashed border-slate-400 rounded-md p-8 text-center text-sm text-slate-500">
      Seret file ke sini atau <span class="text-slate-900 underline">pilih file</span>
    </div>
  </div>

  <button type="submit" class="bg-slate-900 text-[#F7F5F2] text-sm font-medium px-6 py-2.5 rounded-sm hover:bg-slate-800">
    Simpan Logbook
  </button>
</form>
```

Catatan interaksi: field `holiday_name` disembunyikan (`hidden`/`max-h-0`) selama checkbox `is_holiday` tidak dicentang, ditampilkan dengan transisi tinggi sederhana — bukan modal terpisah, agar formulir tetap satu alur.

---

## 6. Riwayat Logbook (List/Tabel)

Karena `activity_date` terindeks dan dipakai sebagai sumber pengurutan utama, riwayat ditampilkan sebagai **list bergaya jurnal per-tanggal**, bukan tabel spreadsheet padat dengan banyak kolom kecil.

```html
<div class="mx-8 divide-y divide-slate-900/10 border-t border-b border-slate-900/10">
  <a class="flex items-center justify-between py-4 hover:bg-slate-900/[0.02] px-2">
    <div>
      <p class="text-sm font-medium text-slate-900">Senin, 13 Juli 2026</p>
      <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">Setup environment development, briefing tim...</p>
    </div>
    <span class="border border-slate-900 px-2 py-0.5 text-xs font-mono uppercase rounded-sm">Approved</span>
  </a>
  <a class="flex items-center justify-between py-4 hover:bg-slate-900/[0.02] px-2">
    <div>
      <p class="text-sm font-medium text-slate-900">Minggu, 12 Juli 2026</p>
      <p class="text-xs text-[#B8422E] mt-0.5">Hari Libur — Cuti Bersama</p>
    </div>
    <span class="text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase rounded-sm">Libur</span>
  </a>
  <a class="flex items-center justify-between py-4 hover:bg-slate-900/[0.02] px-2">
    <div>
      <p class="text-sm font-medium text-slate-900">Jumat, 10 Juli 2026</p>
      <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">Revisi dokumentasi API sesuai catatan mentor.</p>
    </div>
    <span class="text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase rounded-sm">Rejected</span>
  </a>
</div>
```

Baris dengan `is_holiday = true` diberi label khusus "Libur" (memakai warna aksen yang sama dengan Rejected agar tetap hemat palet, tapi teksnya beda supaya tidak membingungkan dengan status ditolak).

---

## 7. Detail Logbook (Termasuk `mentor_note` & Dokumen)

Halaman detail dibuka saat mahasiswa klik satu baris riwayat. Di sinilah `mentor_note` dan `logbook_documents` ditampilkan.

```html
<div class="mx-8 max-w-2xl">
  <div class="flex items-center justify-between border-b border-slate-900/10 pb-4">
    <div>
      <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Jumat, 10 Juli 2026</p>
      <h2 class="text-xl font-bold text-slate-900 mt-1">Detail Logbook</h2>
    </div>
    <span class="text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase rounded-sm">Rejected</span>
  </div>

  <div class="mt-6">
    <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Detail Aktivitas</p>
    <p class="text-sm text-slate-800 mt-2 leading-relaxed">
      Revisi dokumentasi API sesuai catatan sebelumnya, menguji ulang endpoint autentikasi.
    </p>
  </div>

  <div class="mt-6">
    <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Kendala</p>
    <p class="text-sm text-slate-800 mt-2 leading-relaxed">Belum sempat menguji endpoint upload file.</p>
  </div>

  <!-- mentor_note hanya tampil jika status bukan pending -->
  <div class="mt-6 border-l-2 border-[#B8422E] pl-4">
    <p class="font-mono text-xs uppercase tracking-wider text-[#B8422E]">Catatan Mentor</p>
    <p class="text-sm text-slate-800 mt-2 leading-relaxed">
      Tolong lengkapi juga pengujian endpoint upload sebelum entri berikutnya.
    </p>
  </div>

  <div class="mt-6">
    <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Dokumen Lampiran</p>
    <div class="mt-2 space-y-2">
      <div class="flex items-center justify-between border border-slate-900/15 rounded-sm px-3 py-2 text-sm">
        <span class="text-slate-800">laporan-revisi-api.pdf</span>
        <a class="text-slate-500 text-xs underline">Unduh</a>
      </div>
    </div>
  </div>
</div>
```

- `mentor_note` ditampilkan dengan `border-l-2 border-[#B8422E]` **hanya saat status `rejected`** — memberi sinyal visual "perlu tindakan". Untuk status `approved`, gunakan `border-l-2 border-slate-900` (netral, bukan aksen), karena tidak butuh perhatian mendesak.
- Dokumen dari `logbook_documents` ditampilkan sebagai list baris sederhana (nama file + tombol unduh), bukan grid thumbnail besar — konsisten gaya arsip dokumen, bukan galeri media sosial.

---

## 8. Halaman Dokumen

Menu sidebar "Dokumen" adalah **pandangan lain dari data yang sama** (`logbook_documents`), tapi dikelompokkan per-file, bukan per-entri — berguna saat mahasiswa ingin cari satu laporan tanpa mengingat tanggalnya.

- Bukan grid thumbnail besar bergaya galeri foto — tetap list baris tipis, konsisten dengan gaya "arsip dokumen" yang sudah dipakai di halaman Detail Logbook.
- Tiap baris menampilkan nama file, tanggal logbook asalnya (link balik ke entri tersebut), dan tombol unduh.
- Jika dokumen berupa gambar (jpg/png), tampilkan ikon dokumen generik, bukan thumbnail otomatis — mencegah halaman terasa ramai/berwarna.

```html
<div class="px-8 py-6 border-b border-slate-900/10">
  <h1 class="text-xl font-bold text-slate-900">Dokumen</h1>
  <p class="text-sm text-slate-500 mt-1">Semua lampiran yang pernah kamu unggah.</p>
</div>

<div class="mx-8 my-6 divide-y divide-slate-900/10 border-t border-b border-slate-900/10">
  <div class="flex items-center justify-between py-4 px-2">
    <div class="flex items-center gap-3">
      <span class="text-slate-400 text-lg">📄</span>
      <div>
        <p class="text-sm font-medium text-slate-900">laporan-revisi-api.pdf</p>
        <a href="#" class="text-xs text-slate-500 hover:text-slate-900">dari logbook Jumat, 10 Juli 2026</a>
      </div>
    </div>
    <a class="text-slate-500 text-xs underline">Unduh</a>
  </div>
  <div class="flex items-center justify-between py-4 px-2">
    <div class="flex items-center gap-3">
      <span class="text-slate-400 text-lg">📄</span>
      <div>
        <p class="text-sm font-medium text-slate-900">setup-log.pdf</p>
        <a href="#" class="text-xs text-slate-500 hover:text-slate-900">dari logbook Senin, 13 Juli 2026</a>
      </div>
    </div>
    <a class="text-slate-500 text-xs underline">Unduh</a>
  </div>
</div>
```

Jika dokumen kosong, tampilkan state kosong tanpa ilustrasi besar — cukup satu baris teks `text-slate-400`, konsisten dengan gaya minim dekorasi di seluruh dashboard: *"Belum ada dokumen yang diunggah."*

---

## 9. Halaman Profil

Formulir sederhana, satu kolom, tanpa kartu berlapis atau tab yang tidak perlu — mahasiswa hanya butuh mengubah nama, email, dan password sesekali.

```html
<div class="px-8 py-6 border-b border-slate-900/10">
  <h1 class="text-xl font-bold text-slate-900">Profil</h1>
</div>

<div class="px-8 py-8 max-w-lg">
  <div class="flex items-center gap-4 border-b border-slate-900/10 pb-6 mb-6">
    <div class="w-14 h-14 rounded-full bg-slate-900 text-[#F7F5F2] text-lg flex items-center justify-center font-mono">RP</div>
    <div>
      <p class="text-sm font-semibold text-slate-900">Raka Pradipta</p>
      <p class="text-xs text-slate-500 mt-0.5">raka.pradipta@kampus.ac.id</p>
      <p class="text-xs text-slate-500 mt-0.5">Mentor: Dr. Andi Wijaya</p>
    </div>
  </div>

  <form class="space-y-6">
    <div>
      <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Nama</label>
      <input type="text" value="Raka Pradipta" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm" />
    </div>
    <div>
      <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Email</label>
      <input type="email" value="raka.pradipta@kampus.ac.id" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm" />
    </div>

    <div class="border-t border-slate-900/10 pt-6">
      <p class="font-mono text-xs uppercase tracking-wider text-slate-500 mb-3">Ubah Kata Sandi (opsional)</p>
      <label class="font-mono text-xs uppercase tracking-wider text-slate-500">Kata Sandi Baru</label>
      <input type="password" class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm" />
    </div>

    <button type="submit" class="bg-slate-900 text-[#F7F5F2] text-sm font-medium px-6 py-2.5 rounded-sm hover:bg-slate-800">
      Simpan Perubahan
    </button>
  </form>
</div>
```

Baris "Mentor: Dr. Andi Wijaya" sengaja ditampilkan sebagai info read-only (bukan field yang bisa diedit) — sesuai batasan skema `mentor_student`, penugasan mentor bukan sesuatu yang diubah mahasiswa sendiri.

---

## 10. Status Badge (Ditetapkan Ulang dari Dokumen Sistem)

Dashboard mahasiswa memakai badge yang **sama persis** dengan dokumen arsitektur sistem, plus satu varian tambahan untuk `is_holiday`:

| Status | Class |
|---|---|
| `approved` | `border border-slate-900 px-2 py-0.5 text-xs font-mono uppercase rounded-sm` |
| `pending` | `border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase rounded-sm` |
| `rejected` | `text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase rounded-sm` |
| `is_holiday = true` (label "Libur") | sama styling dengan `rejected` (warna aksen), tapi teks berbeda agar tidak tertukar makna |

---

## 11. Aturan Kombinasi Warna di Dashboard

Berbeda dari landing page yang mayoritas satu warna latar, dashboard butuh **hierarki data** — tapi tetap tanpa gradient:

| Elemen | Warna | Alasan |
|---|---|---|
| Latar utama | `bg-[#F7F5F2]` | Konsisten dari landing page, tidak berubah jadi putih polos ala admin panel |
| Kartu/border | `border-slate-900/15` solid | Pemisah antar blok data, tanpa shadow |
| Status netral (approved/pending) | `slate-900` / `slate-400` | Info biasa, tidak butuh perhatian |
| Status/aksi kritis (rejected, belum isi logbook) | `#B8422E` solid | Satu-satunya warna yang "menarik mata" — dipakai konsisten untuk makna yang sama: perlu tindakan |
| Hover baris tabel | `bg-slate-900/[0.02]` | Efek sangat halus, bukan highlight warna terang |

---

## 12. Signature Element Dashboard

Selaras dengan identitas "jurnal/arsip" pada dokumen-dokumen sebelumnya: **riwayat logbook ditampilkan sebagai daftar per-tanggal bergaya buku agenda** (bukan tabel spreadsheet padat kolom), dan **warna aksen `#B8422E` dipakai sebagai satu bahasa visual tunggal untuk "butuh perhatian"** — entah itu logbook yang belum diisi, status `rejected`, atau catatan mentor yang perlu ditindaklanjuti. Konsistensi makna warna ini membuat mahasiswa cukup melihat sekilas warna, tanpa perlu membaca teks, untuk tahu mana yang harus segera ditindaklanjuti.
