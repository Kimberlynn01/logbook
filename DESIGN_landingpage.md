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

# Dokumen Desain Landing Page - InternLog

Dokumen ini melanjutkan spesifikasi tema **Heritage** yang sudah ditetapkan pada dokumen arsitektur sistem, dan menerapkannya secara spesifik untuk halaman pemasaran (_landing page_) InternLog. Tujuan halaman ini adalah satu hal: meyakinkan mahasiswa dan dosen pembimbing bahwa logbook magang digital ini serius, rapi, dan bisa dipercaya — tanpa gimmick visual "AI generik".

Prinsip yang dipegang: **tidak ada gradient warna-warni**. Warna dikombinasikan lewat kontras blok solid (_neutral_ vs _primary_), bukan lewat transisi gradasi. Aksen `#B8422E` tetap dijatah sangat hemat, layaknya di dokumen sebelumnya.

**Catatan penting soal cakupan:** Dokumen ini murni untuk halaman **akuisisi/pemasaran** (marketing page) — halaman yang dilihat pengunjung _sebelum_ login. Tidak ada tampilan dashboard, tabel data, grafik statistik, atau elemen aplikasi internal di sini. Semua visual pendukung berupa **ilustrasi editorial/foto**, bukan screenshot produk atau mockup UI aplikasi.

---

## 1. Konsep Hero (Thesis Statement)

Hero bukan sekadar judul besar + tombol. Hero InternLog meniru **halaman depan surat kabar (masthead)** — karena metafora sistem ini adalah "log/jurnal", bukan "app startup".

**Struktur:**

```
┌──────────────────────────────────────────────────┐
│  INTERNLOG          Fitur  Cara Kerja  Masuk  ►CTA│  ← navbar tipis, border-b saja
├──────────────────────────────────────────────────┤
│  VOL. 01 — LOGBOOK MAGANG DIGITAL                 │  ← eyebrow ala kop koran, font-mono
│                                                    │
│  Satu Catatan Harian,                             │
│  Seribu Bukti Kerja.                              │  ← H1, serif/sans tegas, tracking-tight
│                                                    │
│  Deskripsi singkat 1-2 kalimat, warna secondary.  │
│                                                    │
│  [ Mulai Sekarang ]        Pelajari Lebih Lanjut → │  ← primary solid + text link
├────────────────────────────────────────────────────┤
│           ilustrasi editorial / foto mahasiswa      │
│              magang mencatat aktivitas               │
└────────────────────────────────────────────────────┘
```

- Eyebrow **"VOL. 01 — LOGBOOK MAGANG DIGITAL"** memakai `font-mono text-xs uppercase tracking-wider text-slate-600`, meniru label edisi koran — ini konsisten dengan identitas "jurnalisme" dari dokumen sebelumnya, bukan numbering generik 01/02/03 yang tidak berarti apa-apa.
- Headline besar dua baris, dipecah agar baris kedua jadi penekanan (bukan satu kalimat panjang rata).
- Visual pendukung hero **bukan mockup dashboard atau screenshot aplikasi** — melainkan satu foto/ilustrasi editorial bernuansa dokumenter (misalnya: mahasiswa magang di lapangan, tangan menulis catatan). Ini menjaga hero tetap sebagai halaman "pengenalan", bukan preview produk.

```html
<section class="border-b border-slate-900/10 bg-[#F7F5F2]">
    <div class="mx-auto max-w-6xl px-6 pt-24 pb-16">
        <div class="max-w-2xl">
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-600 mb-4"
            >
                Vol. 01 — Logbook Magang Digital
            </p>
            <h1
                class="font-sans tracking-tight text-slate-900 text-5xl font-bold leading-[1.05]"
            >
                Satu Catatan Harian,<br />Seribu Bukti Kerja.
            </h1>
            <p class="mt-6 text-base text-slate-600 max-w-md">
                InternLog mencatat progres magang mahasiswa secara harian,
                terverifikasi langsung oleh mentor — tanpa spreadsheet, tanpa
                laporan yang tercecer.
            </p>
            <div class="mt-8 flex items-center gap-6">
                <button
                    class="bg-slate-900 text-[#F7F5F2] px-6 py-3 rounded-sm text-sm font-medium hover:bg-slate-800 transition-colors"
                >
                    Mulai Sekarang
                </button>
                <a
                    href="#cara-kerja"
                    class="text-sm font-medium text-slate-900 border-b border-slate-900/40 hover:border-slate-900"
                >
                    Pelajari Lebih Lanjut →
                </a>
            </div>
        </div>
    </div>
    <!-- foto/ilustrasi editorial full-width di bawah teks hero, bukan mockup aplikasi -->
    <div class="mx-auto max-w-6xl px-6 pb-20">
        <div
            class="aspect-[16/7] bg-slate-200 rounded-md border border-slate-900/10"
        ></div>
        <!-- placeholder: foto dokumenter mahasiswa magang -->
    </div>
</section>
```

---

## 2. Struktur Section Landing Page

Bukan urutan generik "Fitur → Testimoni → CTA" tanpa alasan. Urutan berikut mengikuti alur kepercayaan pengguna: **apa masalahnya → bagaimana sistem menjawabnya → bukti nyata → ajakan bertindak**.

| #   | Section                 | Tujuan Naratif                                                                                                                                        |
| --- | ----------------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1   | Hero                    | Menyatakan tesis produk dalam satu kalimat                                                                                                            |
| 2   | Masalah (Problem Strip) | Menunjukkan pain point logbook manual/Excel                                                                                                           |
| 3   | Cara Kerja (3 Langkah)  | Alur nyata: Tulis → Verifikasi → Terekap — di sini numbering 01/02/03 **memang tepat** karena ini benar-benar proses berurutan                        |
| 4   | Fitur Utama             | Grid kartu, tiap kartu = satu manfaat konkret (catatan harian, dokumentasi, notifikasi), ditulis sebagai narasi manfaat — bukan daftar tabel database |
| 5   | Kutipan/Testimoni       | 1 kutipan mahasiswa + 1 kutipan dosen pembimbing                                                                                                      |
| 6   | CTA Penutup             | Ajakan mendaftar, dengan aksen `#B8422E` dipakai sekali di sini                                                                                       |
| 7   | Footer                  | Minimal, garis pembatas tipis, tanpa dekorasi                                                                                                         |

> Catatan: tidak ada section "preview dashboard/aplikasi". Landing page berhenti di titik meyakinkan pengunjung untuk mendaftar — tampilan aplikasi sesungguhnya baru dilihat pengguna setelah login.

---

## 3. Komponen Section per Bagian

### 3.1 Problem Strip

Bar sempit dengan latar `primary` (`#1A1C1E`) agar kontras tegas dari `neutral`, bukan gradient. Teks warna `neutral`.

```html
<section class="bg-slate-900 text-[#F7F5F2]">
    <div class="mx-auto max-w-6xl px-6 py-16 grid md:grid-cols-3 gap-8">
        <div>
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-400"
            >
                Masalah 01
            </p>
            <p class="mt-2 text-lg">
                Logbook Excel gampang hilang & tidak sinkron.
            </p>
        </div>
        <div>
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-400"
            >
                Masalah 02
            </p>
            <p class="mt-2 text-lg">
                Mentor kesulitan memantau progres harian.
            </p>
        </div>
        <div>
            <p
                class="font-mono text-xs uppercase tracking-wider text-slate-400"
            >
                Masalah 03
            </p>
            <p class="mt-2 text-lg">
                Rekap akhir magang dikerjakan manual, terburu-buru.
            </p>
        </div>
    </div>
</section>
```

### 3.2 Cara Kerja (3 Langkah — numbering sah dipakai)

```html
<section class="mx-auto max-w-6xl px-6 py-24">
    <h2
        class="font-sans tracking-tight text-slate-900 text-3xl font-bold mb-12"
    >
        Cara Kerja
    </h2>
    <div class="grid md:grid-cols-3 gap-10">
        <div class="border-t-2 border-slate-900 pt-4">
            <span class="font-mono text-xs text-slate-500">01</span>
            <h3 class="font-semibold text-slate-900 mt-1">Tulis Logbook</h3>
            <p class="text-sm text-slate-600 mt-2">
                Catat aktivitas harian lengkap dengan foto dokumentasi.
            </p>
        </div>
        <div class="border-t-2 border-slate-900 pt-4">
            <span class="font-mono text-xs text-slate-500">02</span>
            <h3 class="font-semibold text-slate-900 mt-1">
                Mentor Memverifikasi
            </h3>
            <p class="text-sm text-slate-600 mt-2">
                Status berubah otomatis: Pending → Approved.
            </p>
        </div>
        <div class="border-t-2 border-slate-900 pt-4">
            <span class="font-mono text-xs text-slate-500">03</span>
            <h3 class="font-semibold text-slate-900 mt-1">Rekap Otomatis</h3>
            <p class="text-sm text-slate-600 mt-2">
                Laporan akhir tersusun otomatis, tinggal unduh PDF.
            </p>
        </div>
    </div>
</section>
```

### 3.3 Fitur Utama (Grid Kartu)

Kartu tanpa shadow, hanya `border`. Rounded dibatasi `rounded-md` (8px) sesuai aturan sistem. Ditulis sebagai **manfaat untuk pengunjung**, bukan nama tabel/field teknis — pengunjung landing page tidak peduli struktur data, mereka peduli apa yang mereka dapat.

```html
<div class="grid md:grid-cols-3 gap-6">
    <div class="border border-slate-900/15 rounded-md p-6 bg-[#F7F5F2]">
        <p class="font-mono text-xs uppercase tracking-wider text-slate-500">
            Setiap Hari
        </p>
        <h3 class="text-slate-900 font-semibold text-lg mt-2">
            Catat dalam Hitungan Menit
        </h3>
        <p class="text-sm text-slate-600 mt-2">
            Tulis aktivitas harian langsung dari HP, lengkap dengan foto bukti
            kerja.
        </p>
    </div>
    <div class="border border-slate-900/15 rounded-md p-6 bg-[#F7F5F2]">
        <p class="font-mono text-xs uppercase tracking-wider text-slate-500">
            Tanpa Ribet
        </p>
        <h3 class="text-slate-900 font-semibold text-lg mt-2">
            Satu Tempat untuk Semua Bukti
        </h3>
        <p class="text-sm text-slate-600 mt-2">
            Foto lapangan dan laporan PDF tersimpan rapi, tidak tercecer di chat
            atau email.
        </p>
    </div>
    <div class="border border-slate-900/15 rounded-md p-6 bg-[#F7F5F2]">
        <p class="font-mono text-xs uppercase tracking-wider text-slate-500">
            Selalu Update
        </p>
        <h3 class="text-slate-900 font-semibold text-lg mt-2">
            Mentor Tahu Progresmu Saat Itu Juga
        </h3>
        <p class="text-sm text-slate-600 mt-2">
            Setiap catatan baru langsung diketahui mentor, tanpa perlu menunggu
            ditanya.
        </p>
    </div>
</div>
```

### 3.4 Kutipan/Testimoni

Dua kutipan berdampingan, tanpa foto avatar bulat besar bergaya generik — cukup nama, peran, dan garis pembatas kiri sebagai penanda kutipan (seperti kutipan pull-quote di koran).

```html
<section class="mx-auto max-w-6xl px-6 py-20 border-t border-slate-900/10">
    <div class="grid md:grid-cols-2 gap-10">
        <blockquote class="border-l-2 border-slate-900 pl-6">
            <p class="text-lg text-slate-800 leading-relaxed">
                Nggak perlu lagi rekap manual di akhir magang, semua sudah
                tercatat rapi per hari.
            </p>
            <footer
                class="mt-4 text-xs font-mono uppercase tracking-wider text-slate-500"
            >
                — Mahasiswa Teknik Informatika
            </footer>
        </blockquote>
        <blockquote class="border-l-2 border-slate-900 pl-6">
            <p class="text-lg text-slate-800 leading-relaxed">
                Saya bisa memantau progres bimbingan tanpa harus menunggu
                laporan mingguan.
            </p>
            <footer
                class="mt-4 text-xs font-mono uppercase tracking-wider text-slate-500"
            >
                — Dosen Pembimbing Lapangan
            </footer>
        </blockquote>
    </div>
</section>
```

### 3.5 CTA Penutup — satu-satunya tempat aksen tertiary dipakai luas

Latar tetap `neutral`, tapi tombol utama pakai `tertiary` sebagai penanda momen keputusan — sejalan dengan aturan "aksen dipakai sangat selektif".

```html
<section
    class="mx-auto max-w-4xl px-6 py-24 text-center border-t border-slate-900/10"
>
    <h2 class="font-sans tracking-tight text-slate-900 text-3xl font-bold">
        Mulai catat progres magangmu hari ini.
    </h2>
    <p class="text-slate-600 mt-3">
        Gratis untuk mahasiswa dan dosen pembimbing.
    </p>
    <button
        class="mt-8 bg-[#B8422E] text-[#F7F5F2] px-8 py-3 rounded-sm text-sm font-medium hover:opacity-90 transition-opacity"
    >
        Daftar Sekarang
    </button>
</section>
```

### 3.6 Status Badge (opsional, jika ingin tetap konsisten dengan sistem)

Jika ingin tetap menyinggung status logbook secara ringan di landing page (misalnya sebagai elemen dekoratif kecil di section fitur, bukan tabel data), gunakan gaya badge yang sama dari dokumen sistem — tapi tanpa membangunnya jadi tampilan tabel/dashboard:

- `Approved` → `border border-slate-900 px-2 py-0.5 text-xs font-mono uppercase`
- `Pending` → `border border-dashed border-slate-400 text-slate-600 px-2 py-0.5 text-xs font-mono uppercase`
- `Rejected` / `Holiday Alert` → `text-[#B8422E] border border-[#B8422E] px-2 py-0.5 text-xs font-mono uppercase`

### 3.7 Footer

Minimal, satu garis pembatas, tanpa kolom dekoratif berlebihan.

```html
<footer class="border-t border-slate-900/10">
    <div
        class="mx-auto max-w-6xl px-6 py-10 flex justify-between text-xs text-slate-500 font-mono uppercase tracking-wider"
    >
        <span>InternLog © 2026</span>
        <span>Dibangun untuk mahasiswa & mentor</span>
    </div>
</footer>
```

---

## 4. Aturan Kombinasi Warna (Tanpa Gradient)

Karena instruksinya "jangan terlalu di-gradient-kan, kombinasikan saja", berikut pola kombinasi blok solid yang dipakai di landing page ini:

| Kombinasi                               | Kegunaan                                                                                                          |
| --------------------------------------- | ----------------------------------------------------------------------------------------------------------------- |
| `bg-[#F7F5F2]` + teks `slate-900`       | Section default (hero, fitur, cara kerja)                                                                         |
| `bg-slate-900` + teks `#F7F5F2`         | Section kontras untuk jeda ritme (Problem Strip) — bukan gradasi, tapi blok solid gelap yang memutus ritme terang |
| `border-slate-900/10`                   | Semua pembatas antar-section, konsisten tipis                                                                     |
| `#B8422E` solid (tanpa opacity/gradasi) | Hanya tombol CTA final & badge kritis                                                                             |

Warna tidak pernah dicampur lewat `bg-gradient-to-*`. Transisi visual antar-section dicapai lewat **pergantian blok warna solid** (`neutral` → `primary` → `neutral`), bukan gradasi — ini yang membuat halaman terasa seperti lembaran koran yang disusun berlapis, bukan produk AI generik.

---

## 5. Tipografi pada Landing Page

- **H1 Hero:** `font-sans tracking-tight text-5xl font-bold leading-[1.05]` — lebih besar dari H1 standar dokumen (3rem) karena hero butuh dominasi visual, tapi tetap keluarga font yang sama (Public Sans).
- **Eyebrow/label:** `font-mono text-xs uppercase tracking-wider` (Space Grotesk) — dipakai konsisten di setiap section sebagai penanda "kop berita".
- **Body:** `text-base text-slate-600` (Public Sans) — tidak pernah lebih dari `max-w-md` per paragraf agar terasa seperti kolom koran, bukan blok teks lebar generik.

---

## 6. Signature Element

Elemen yang membuat landing page ini dikenali dan berbeda dari template AI pada umumnya: **eyebrow bergaya "Vol. 01 / Edisi Kop Koran"** yang muncul konsisten di setiap section (Vol. 01 di hero, "Masalah 01/02/03" di problem strip, "01/02/03" di cara kerja). Ini bukan numbering dekoratif — ini adalah identitas "jurnalisme arsitektural" yang sudah ditetapkan sejak dokumen sistem, dibawa konsisten sampai ke halaman pemasaran.
