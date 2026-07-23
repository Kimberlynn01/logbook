@extends('layouts.guest')

@section('title', 'Daftar')

@section('content')
    <div class="bg-white border border-[#1A1C1E]/[0.12] rounded-lg p-6 md:p-8">
        <h1 class="font-['Space_Grotesk'] text-xl font-bold text-[#1A1C1E] mb-1">Buat Akun</h1>
        <p class="text-sm text-[#6C7278] mb-6">Daftar sebagai mahasiswa magang atau mentor pembimbing.</p>

        @if ($errors->any())
            <div class="mb-5 px-3 py-2.5 bg-[#B8422E]/[0.08] border-l-2 border-[#B8422E] text-sm text-[#B8422E]">
                <ul class="list-disc list-inside grid gap-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="grid gap-5">
            @csrf

            {{-- Pilih role --}}
            <div class="grid gap-1.5">
                <label class="text-sm font-medium text-[#6C7278]">Daftar sebagai</label>
                <div class="grid grid-cols-2 gap-2">
                    <label
                        class="role-option flex items-center justify-center gap-2 border border-[#1A1C1E]/[0.16] px-3 py-2.5 text-sm cursor-pointer">
                        <input type="radio" name="role" value="mahasiswa" class="role-radio"
                            {{ old('role', 'mahasiswa') === 'mahasiswa' ? 'checked' : '' }}>
                        Mahasiswa
                    </label>
                    <label
                        class="role-option flex items-center justify-center gap-2 border border-[#1A1C1E]/[0.16] px-3 py-2.5 text-sm cursor-pointer">
                        <input type="radio" name="role" value="mentor" class="role-radio"
                            {{ old('role') === 'mentor' ? 'checked' : '' }}>
                        Mentor
                    </label>
                </div>
            </div>

            <div class="grid gap-1.5">
                <label for="name" class="text-sm font-medium text-[#6C7278]">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
            </div>

            <div class="grid gap-1.5">
                <label for="email" class="text-sm font-medium text-[#6C7278]">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
            </div>

            {{-- Field khusus mahasiswa --}}
            <div id="academicFields" class="grid gap-5">
                <div class="grid gap-1.5">
                    <label for="university" class="text-sm font-medium text-[#6C7278]">Kampus</label>
                    <input type="text" id="university" name="university" value="{{ old('university') }}"
                        placeholder="Contoh: Universitas Sebelas Maret"
                        class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
                </div>

                <div class="grid gap-1.5">
                    <label for="major" class="text-sm font-medium text-[#6C7278]">Jurusan</label>
                    <input type="text" id="major" name="major" value="{{ old('major') }}"
                        placeholder="Contoh: Sistem Informasi"
                        class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
                </div>

                <div class="grid gap-1.5">
                    <label for="degree_level" class="text-sm font-medium text-[#6C7278]">Jenjang</label>
                    <select id="degree_level" name="degree_level"
                        class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
                        <option value="">Pilih jenjang</option>
                        @foreach (['D3', 'D4', 'S1', 'S2', 'S3'] as $level)
                            <option value="{{ $level }}" {{ old('degree_level') === $level ? 'selected' : '' }}>
                                {{ $level }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-1.5">
                <label for="password" class="text-sm font-medium text-[#6C7278]">Kata Sandi</label>
                <input type="password" id="password" name="password" required minlength="8"
                    class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
            </div>

            <div class="grid gap-1.5">
                <label for="password_confirmation" class="text-sm font-medium text-[#6C7278]">Konfirmasi Kata
                    Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="rounded-md border border-[#1A1C1E]/[0.16] px-3 py-2 text-sm text-[#1A1C1E] focus:outline-none focus:border-[#1A1C1E] focus:ring-1 focus:ring-[#1A1C1E]">
            </div>

            <button type="submit"
                class="mt-1 px-5 py-2.5 rounded-md bg-[#1A1C1E] text-white text-sm font-semibold hover:opacity-90 transition">
                Daftar
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#6C7278]">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#1A1C1E] font-semibold hover:underline">Masuk</a>
        </p>
    </div>
@endsection

@push('styles')
    <style>
        .role-option:has(.role-radio:checked) {
            border-color: #1A1C1E;
            background: rgba(26, 28, 30, 0.04);
            font-weight: 600;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const roleRadios = document.querySelectorAll('.role-radio');
        const academicFields = document.getElementById('academicFields');
        const universityInput = document.getElementById('university');
        const majorInput = document.getElementById('major');
        const degreeSelect = document.getElementById('degree_level');

        function toggleAcademicFields() {
            const isMahasiswa = document.querySelector('.role-radio:checked')?.value === 'mahasiswa';

            academicFields.classList.toggle('hidden', !isMahasiswa);
            universityInput.required = isMahasiswa;
            majorInput.required = isMahasiswa;
            degreeSelect.required = isMahasiswa;
        }

        roleRadios.forEach((radio) => radio.addEventListener('change', toggleAcademicFields));
        toggleAcademicFields();
    </script>
@endpush
