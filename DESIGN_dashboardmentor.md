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

# Dokumen Desain Dashboard Mentor - InternLog

Dokumen ini menerapkan tema **Heritage** untuk dashboard internal **mentor** — pasangan dari `DESIGN_DashboardMahasiswa.md`. Kalau dashboard mahasiswa berpusat pada "menulis satu entri", dashboard mentor berpusat pada **satu pekerjaan berulang: meninjau antrian logbook dari banyak mahasiswa bimbingan, secepat mungkin, tanpa kehilangan satupun**.

Referensi skema yang jadi dasar tampilan:

- `mentor_student` — satu mentor bisa membimbing **banyak** mahasiswa (`student_id` unik per baris, tapi `mentor_id` bisa berulang). Artinya dashboard mentor **wajib** punya konsep daftar/pemilihan mahasiswa — beda dari dashboard mahasiswa yang tidak butuh itu.
- `logbooks` — mentor adalah pihak yang mengubah `status` (pending → approved/rejected) dan mengisi `mentor_note`. Ini aksi inti, bukan sekadar tampilan data.
- `logbook_documents` — mentor perlu bisa membuka lampiran saat meninjau, tanpa pindah halaman.
- `notifications` — sesuai dokumen arsitektur sistem, tabel ini dirancang ringan untuk **AJAX polling setiap 5 detik** khusus di dashboard mentor. Ini konsekuensi desain langsung: harus ada indikator notifikasi real-time yang terasa "hidup" tapi tidak mengganggu.

Prinsip visual tetap konsisten dari dua dokumen sebelumnya: tanpa gradient, warna dikombinasikan lewat blok solid, aksen `#B8422E` dijatah untuk hal yang benar-benar butuh tindakan.

---

## 1. Konsep Dashboard Mentor

Bukan admin panel dengan tabel data mentah dan tombol aksi di mana-mana. Mentor dashboard meniru **meja kerja editor di ruang redaksi** — tumpukan "naskah masuk" (logbook pending) yang harus dibaca dan diberi keputusan satu per satu, dengan daftar "kontributor" (mahasiswa bimbingan) di sisi lain.

Dua mode utama yang harus dilayani UI:

1. **Mode antrian** — mentor login karena ada notifikasi logbook baru, langsung ingin meninjau dan menyelesaikan tumpukan pending.
2. **Mode pengawasan** — mentor ingin melihat progres satu mahasiswa tertentu secara menyeluruh (riwayat, tren keterlambatan, dsb).

---

## 2. Struktur Layout

```
┌───────────┬────────────────────────────────────────────┐
│           │  Topbar: nama mentor         🔔 3   Profil  │
│  Sidebar  ├────────────────────────────────────────────┤
│           │  Ringkasan (kartu: total mahasiswa, pending)│
│ • Antrian  │                                            │
│ • Mahasiswa│  Antrian Review (list logbook pending)      │
│ • Riwayat  │                                            │
│ • Profil   │  (klik satu baris → panel review di kanan) │
└───────────┴────────────────────────────────────────────┘
```

- **Sidebar**: 4 menu — Antrian (default/halaman utama), Mahasiswa Bimbingan, Riwayat, Profil. "Antrian" jadi menu pertama (bukan "Ringkasan" seperti di dashboard mahasiswa) karena ini tugas utama mentor.
- **Topbar** menampilkan lonceng notifikasi dengan badge angka — sumber datanya tabel `notifications`, di-refresh polling 5 detik, ditandai titik kecil `#B8422E` saat ada yang belum dibaca (bukan animasi berkedip mencolok, cukup badge angka statis yang berubah).

```html
<header
    class="flex items-center justify-between border-b border-slate-900/10 px-8 py-5"
>
    <p class="text-sm font-semibold text-slate-900">Dr. Andi Wijaya</p>
    <div class="flex items-center gap-6">
        <button class="relative">
            <span class="text-slate-700 text-lg">🔔</span>
            <span
                class="absolute -top-1 -right-2 bg-[#B8422E] text-[#F7F5F2] text-[10px] font-mono w-4 h-4 rounded-full flex items-center justify-center"
                >3</span
            >
        </button>
        <div
            class="w-8 h-8 rounded-full bg-slate-900 text-[#F7F5F2] text-xs flex items-center justify-center font-mono"
        >
            AW
        </div>
    </div>
</header>
```

---

## 3. Ringkasan Singkat (Bar Statistik)

Bukan grid kartu besar seperti dashboard mahasiswa — cukup satu baris ringkas, karena fokus mentor harus langsung jatuh ke antrian, bukan ke angka.

```html
<section
    class="flex items-center gap-8 px-8 py-5 border-b border-slate-900/10 text-sm"
>
    <div>
        <span class="text-2xl font-bold text-slate-900">12</span>
        <span class="text-slate-500 ml-1">Mahasiswa Bimbingan</span>
    </div>
    <div class="w-px h-6 bg-slate-900/10"></div>
    <div>
        <span class="text-2xl font-bold text-[#B8422E]">5</span>
        <span class="text-slate-500 ml-1">Menunggu Review</span>
    </div>
    <div class="w-px h-6 bg-slate-900/10"></div>
    <div>
        <span class="text-2xl font-bold text-slate-900">3</span>
        <span class="text-slate-500 ml-1">Direview Hari Ini</span>
    </div>
</section>
```

Angka "Menunggu Review" satu-satunya yang memakai warna aksen — konsisten dengan aturan "aksen = butuh tindakan" dari dokumen dashboard mahasiswa.

---

## 4. Antrian Review (Halaman Utama)

Ini jantung dashboard mentor. Layout dua panel: **list antrian di kiri, panel detail/keputusan di kanan** — supaya mentor bisa menyelesaikan banyak logbook berturut-turut tanpa reload halaman.

```
┌──────────────────────┬─────────────────────────────────┐
│  ANTRIAN (5)          │  Detail Logbook Terpilih         │
│                        │                                   │
│  ● Raka P.  13 Jul     │  Raka Pradipta — Senin, 13 Juli   │
│    Setup environment...│  ─────────────────────────────   │
│  ○ Dinda A.  13 Jul    │  Detail Aktivitas: ...            │
│    Meeting client...   │  Kendala: ...                     │
│  ○ Fajar S.  12 Jul     │  Dokumen: laporan.pdf             │
│  ...                    │                                   │
│                        │  Catatan Mentor: [ textarea ]     │
│                        │  [ Setujui ]   [ Tolak ]          │
└──────────────────────┴─────────────────────────────────┘
```

- List kiri diurutkan berdasarkan `activity_date` terlama dahulu (FIFO) — bukan terbaru dahulu — supaya logbook yang sudah lama menunggu tidak terus tertimbun di bawah.
- Baris terpilih ditandai `●` (bullet solid) vs `○` (bullet outline) — bukan highlight warna besar, agar tetap tenang secara visual saat daftar panjang.

```html
<div class="grid grid-cols-[320px_1fr] h-full">
    <!-- List Antrian -->
    <div class="border-r border-slate-900/10 overflow-y-auto">
        <p
            class="font-mono text-xs uppercase tracking-wider text-slate-500 px-5 py-4"
        >
            Antrian (5)
        </p>
        <a
            class="block px-5 py-3 border-l-2 border-slate-900 bg-slate-900/[0.03]"
        >
            <p class="text-sm font-medium text-slate-900">Raka Pradipta</p>
            <p class="text-xs text-slate-500 mt-0.5">
                Senin, 13 Juli · Setup environment development...
            </p>
        </a>
        <a
            class="block px-5 py-3 border-l-2 border-transparent hover:bg-slate-900/[0.02]"
        >
            <p class="text-sm font-medium text-slate-900">Dinda Amelia</p>
            <p class="text-xs text-slate-500 mt-0.5">
                Senin, 13 Juli · Meeting dengan tim client...
            </p>
        </a>
        <a
            class="block px-5 py-3 border-l-2 border-transparent hover:bg-slate-900/[0.02]"
        >
            <p class="text-sm font-medium text-slate-900">Fajar Setiawan</p>
            <p class="text-xs text-slate-500 mt-0.5">
                Minggu, 12 Juli · Dokumentasi hasil testing...
            </p>
        </a>
    </div>

    <!-- Panel Detail + Keputusan -->
    <div class="p-8 max-w-2xl">
        <p class="font-mono text-xs uppercase tracking-wider text-slate-500">
            Senin, 13 Juli 2026
        </p>
        <h2 class="text-xl font-bold text-slate-900 mt-1">Raka Pradipta</h2>

        <div class="mt-6">
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
            >
                Detail Aktivitas
            </p>
            <p class="text-sm text-slate-800 mt-2 leading-relaxed">
                Melakukan setup environment development, mengikuti briefing tim,
                dan mereview dokumentasi API internal.
            </p>
        </div>

        <div class="mt-6">
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
            >
                Kendala
            </p>
            <p class="text-sm text-slate-800 mt-2 leading-relaxed">
                Belum ada.
            </p>
        </div>

        <div class="mt-6">
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
            >
                Dokumen Lampiran
            </p>
            <div
                class="mt-2 flex items-center justify-between border border-slate-900/15 rounded-sm px-3 py-2 text-sm max-w-sm"
            >
                <span class="text-slate-800">setup-log.pdf</span>
                <a class="text-slate-500 text-xs underline">Buka</a>
            </div>
        </div>

        <div class="mt-8 border-t border-slate-900/10 pt-6">
            <label
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
                >Catatan Mentor (opsional untuk setuju, wajib untuk
                tolak)</label
            >
            <textarea
                rows="3"
                class="mt-2 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm"
                placeholder="Tulis catatan untuk mahasiswa..."
            ></textarea>

            <div class="mt-4 flex gap-3">
                <button
                    class="bg-slate-900 text-[#F7F5F2] text-sm font-medium px-6 py-2.5 rounded-sm hover:bg-slate-800"
                >
                    Setujui
                </button>
                <button
                    class="border border-[#B8422E] text-[#B8422E] text-sm font-medium px-6 py-2.5 rounded-sm hover:bg-[#B8422E]/5"
                >
                    Tolak
                </button>
            </div>
        </div>
    </div>
</div>
```

**Aturan interaksi penting:** tombol "Tolak" memakai `border` (outline), bukan `bg-[#B8422E]` solid seperti tombol utama di halaman lain. Ini disengaja: warna solid aksen dipakai untuk hal yang perlu _diperhatikan pengguna_, sedangkan di sini justru mentor yang melakukan tindakan tegas — jadi tombol "Setujui" (aksi paling sering dipakai, low-friction) yang tampil solid `primary`, dan "Tolak" tampil sebagai outline agar butuh kesadaran ekstra tanpa terasa seperti tombol berbahaya bergaya generik (bukan merah terang, tetap dalam palet Boston Clay). Saat "Tolak" diklik tanpa catatan mentor terisi, tampilkan validasi ringan di bawah textarea, bukan alert popup.

---

## 5. Daftar Mahasiswa Bimbingan

Karena satu mentor punya banyak mahasiswa (`mentor_student`), perlu halaman/list terpisah dari antrian — untuk kasus "mentor ingin cek satu mahasiswa spesifik", bukan menunggu masuk antrian.

```html
<div
    class="mx-8 divide-y divide-slate-900/10 border-t border-b border-slate-900/10"
>
    <a
        class="flex items-center justify-between py-4 px-2 hover:bg-slate-900/[0.02]"
    >
        <div>
            <p class="text-sm font-medium text-slate-900">Raka Pradipta</p>
            <p class="text-xs text-slate-500 mt-0.5">
                24 logbook · terakhir mengisi 13 Juli 2026
            </p>
        </div>
        <span
            class="border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase rounded-sm"
            >1 Pending</span
        >
    </a>
    <a
        class="flex items-center justify-between py-4 px-2 hover:bg-slate-900/[0.02]"
    >
        <div>
            <p class="text-sm font-medium text-slate-900">Dinda Amelia</p>
            <p class="text-xs text-slate-500 mt-0.5">
                21 logbook · terakhir mengisi 13 Juli 2026
            </p>
        </div>
        <span
            class="border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase rounded-sm"
            >1 Pending</span
        >
    </a>
    <a
        class="flex items-center justify-between py-4 px-2 hover:bg-slate-900/[0.02]"
    >
        <div>
            <p class="text-sm font-medium text-slate-900">Fajar Setiawan</p>
            <p class="text-xs text-slate-500 mt-0.5">
                18 logbook · terakhir mengisi 10 Juli 2026
            </p>
        </div>
        <span class="text-slate-400 text-xs font-mono uppercase"
            >Tidak ada pending</span
        >
    </a>
</div>
```

Klik satu baris membuka halaman **Riwayat per Mahasiswa** (lihat Section 6) — pola datanya sama persis dengan yang dilihat mahasiswa sendiri, menjaga konsistensi mental model di kedua sisi produk.

---

## 6. Riwayat per Mahasiswa

Dibuka dari satu baris di "Daftar Mahasiswa Bimbingan". Layout-nya **sengaja identik** dengan halaman Riwayat di `DESIGN_DashboardMahasiswa.md` (list bergaya buku agenda, badge status sama) — hanya ditambah judul nama mahasiswa dan tautan kembali, supaya mentor tidak perlu belajar pola visual baru untuk data yang sama.

```html
<div class="px-8 py-6 border-b border-slate-900/10">
    <a href="#" class="text-xs text-slate-500 hover:text-slate-900"
        >← Mahasiswa Bimbingan</a
    >
    <h1 class="text-xl font-bold text-slate-900 mt-2">Raka Pradipta</h1>
    <p class="text-sm text-slate-500 mt-1">24 logbook tercatat.</p>
</div>

<div
    class="mx-8 my-6 divide-y divide-slate-900/10 border-t border-b border-slate-900/10"
>
    <a
        class="flex items-center justify-between py-4 px-2 hover:bg-slate-900/[0.02]"
    >
        <div>
            <p class="text-sm font-medium text-slate-900">
                Senin, 13 Juli 2026
            </p>
            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                Setup environment development, briefing tim...
            </p>
        </div>
        <span
            class="border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase rounded-sm"
            >Pending</span
        >
    </a>
    <a
        class="flex items-center justify-between py-4 px-2 hover:bg-slate-900/[0.02]"
    >
        <div>
            <p class="text-sm font-medium text-slate-900">
                Jumat, 10 Juli 2026
            </p>
            <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                Revisi dokumentasi API sesuai catatan mentor.
            </p>
        </div>
        <span
            class="text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase rounded-sm"
            >Rejected</span
        >
    </a>
</div>
```

Satu-satunya beda dari sisi mahasiswa: setiap baris di sini boleh diklik lagi untuk membuka logbook tersebut langsung di **panel review** (Section 4) — bukan halaman detail read-only seperti versi mahasiswa — karena mentor mungkin ingin mengubah keputusan lama.

---

## 7. Halaman Profil

Sama sederhananya dengan Profil di dashboard mahasiswa: satu kolom, tanpa tab. Tambahan satu baris info yang tidak ada di sisi mahasiswa — jumlah mahasiswa bimbingan — karena itu konteks yang relevan untuk mentor melihat dirinya sendiri.

```html
<div class="px-8 py-6 border-b border-slate-900/10">
    <h1 class="text-xl font-bold text-slate-900">Profil</h1>
</div>

<div class="px-8 py-8 max-w-lg">
    <div class="flex items-center gap-4 border-b border-slate-900/10 pb-6 mb-6">
        <div
            class="w-14 h-14 rounded-full bg-slate-900 text-[#F7F5F2] text-lg flex items-center justify-center font-mono"
        >
            AW
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-900">Dr. Andi Wijaya</p>
            <p class="text-xs text-slate-500 mt-0.5">
                andi.wijaya@kampus.ac.id
            </p>
            <p class="text-xs text-slate-500 mt-0.5">12 mahasiswa bimbingan</p>
        </div>
    </div>

    <form class="space-y-6">
        <div>
            <label
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
                >Nama</label
            >
            <input
                type="text"
                value="Dr. Andi Wijaya"
                class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm"
            />
        </div>
        <div>
            <label
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
                >Email</label
            >
            <input
                type="email"
                value="andi.wijaya@kampus.ac.id"
                class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm"
            />
        </div>

        <div class="border-t border-slate-900/10 pt-6">
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-500 mb-3"
            >
                Ubah Kata Sandi (opsional)
            </p>
            <label
                class="font-mono text-xs uppercase tracking-wider text-slate-500"
                >Kata Sandi Baru</label
            >
            <input
                type="password"
                class="mt-1 w-full border border-slate-900/20 rounded-sm px-3 py-2 text-sm"
            />
        </div>

        <button
            type="submit"
            class="bg-slate-900 text-[#F7F5F2] text-sm font-medium px-6 py-2.5 rounded-sm hover:bg-slate-800"
        >
            Simpan Perubahan
        </button>
    </form>
</div>
```

---

## 8. Notifikasi Real-Time

Berdasarkan catatan arsitektur (`notifications` — polling AJAX tiap 5 detik), dropdown notifikasi harus terasa ringan, bukan seperti feed media sosial:

```html
<div
    class="absolute right-8 top-16 w-80 border border-slate-900/15 rounded-md bg-[#F7F5F2] divide-y divide-slate-900/10"
>
    <p
        class="font-mono text-xs uppercase tracking-wider text-slate-500 px-4 py-3"
    >
        Notifikasi
    </p>
    <div class="px-4 py-3">
        <p class="text-sm text-slate-800">
            Raka Pradipta mengisi logbook baru.
        </p>
        <p class="text-xs text-slate-400 mt-1 font-mono">2 menit lalu</p>
    </div>
    <div class="px-4 py-3">
        <p class="text-sm text-slate-800">Dinda Amelia mengisi logbook baru.</p>
        <p class="text-xs text-slate-400 mt-1 font-mono">18 menit lalu</p>
    </div>
</div>
```

- Tidak ada avatar bulat berwarna atau ikon lonceng animasi berulang — teks polos dengan timestamp relatif, gaya "buletin singkat", konsisten dengan nada "jurnalisme arsitektural" dari seluruh produk.
- Karena polling terjadi tiap 5 detik, badge angka di lonceng topbar **hanya boleh berubah, tidak boleh ada animasi bounce/pulse** setiap kali refresh — mencegah dashboard terasa "berkedut" saat dibiarkan terbuka lama.

---

## 9. Status Badge (Konsisten dari Dua Dokumen Sebelumnya)

| Status                            | Class                                                                                                     |
| --------------------------------- | --------------------------------------------------------------------------------------------------------- |
| `approved`                        | `border border-slate-900 px-2 py-0.5 text-xs font-mono uppercase rounded-sm`                              |
| `pending`                         | `border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase rounded-sm` |
| `rejected`                        | `text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase rounded-sm`               |
| Jumlah pending di kartu mahasiswa | badge sama dengan `pending`, sekadar diberi angka                                                         |

---

## 10. Aturan Kombinasi Warna di Dashboard Mentor

| Elemen                  | Warna                                                 | Alasan                                                                 |
| ----------------------- | ----------------------------------------------------- | ---------------------------------------------------------------------- |
| Latar utama             | `bg-[#F7F5F2]`                                        | Konsisten di seluruh produk                                            |
| Baris antrian terpilih  | `border-l-2 border-slate-900` + `bg-slate-900/[0.03]` | Penanda seleksi halus, bukan blok warna solid besar                    |
| Tombol "Setujui"        | `bg-slate-900` solid                                  | Aksi paling sering dipakai → netral, low-friction                      |
| Tombol "Tolak"          | `border border-[#B8422E]` outline                     | Aksi tegas tapi disengaja tidak dibuat "menakutkan" dengan warna solid |
| Angka "Menunggu Review" | `text-[#B8422E]`                                      | Satu-satunya angka di bar statistik yang perlu menarik perhatian       |
| Notifikasi              | teks polos, tanpa ikon berwarna                       | Menjaga nada tenang meski data live-update tiap 5 detik                |

---

## 11. Signature Element Dashboard Mentor

Kalau dashboard mahasiswa punya "riwayat bergaya buku agenda", dashboard mentor punya signature-nya sendiri: **layout dua-panel antrian gaya meja editor** (list naskah masuk di kiri, keputusan di kanan, FIFO berdasarkan tanggal tertua) — dipadukan dengan **aturan tombol "Setujui vs Tolak" yang sengaja asimetris** (solid vs outline) sebagai bahasa visual bahwa menyetujui itu alur normal yang cepat, sementara menolak butuh kesadaran ekstra tanpa perlu terasa dramatis. Ini konsisten dengan identitas keseluruhan produk: tenang, seperti ruang redaksi, bukan dashboard SaaS generik yang penuh tombol merah-hijau kontras tinggi.
