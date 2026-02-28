<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - Sistem KKA UM Kendari</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --maroon-dark: #800000;
            --maroon-main: #A52A2A;
            --maroon-light: #C41E3A;
            --maroon-lighter: #E8B4B8;
            --gray-dark: #1f2937;
            --gray-light: #f3f4f6;
            --gray-border: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --success: #10b981;
            --error: #ef4444;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('/img/bg.png');
            background-attachment: fixed;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-primary);
            position: relative;
            padding: 20px;
        }

        body::before {
            content: '';
            position: fixed;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            top: 0; left: 0; z-index: 0;
        }

        .register-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            z-index: 1;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── LEFT PANEL ─────────────────────────── */
        .reg-left {
            flex: 1;
            background: linear-gradient(135deg, var(--maroon-dark) 0%, var(--maroon-main) 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .reg-left::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(232,180,184,0.1);
            border-radius: 50%;
            top: -50px; right: -50px;
        }

        .reg-left::after {
            content: '';
            position: absolute;
            width: 150px; height: 150px;
            background: rgba(232,180,184,0.08);
            border-radius: 50%;
            bottom: 20px; left: -30px;
        }

        .reg-left-content { position: relative; z-index: 1; }

        .reg-logo { font-size: 48px; margin-bottom: 20px; }

        .reg-left h1 { font-size: 28px; font-weight: 700; margin-bottom: 12px; line-height: 1.3; }
        .reg-left > .reg-left-content > p { font-size: 13px; opacity: 0.9; margin-bottom: 36px; line-height: 1.6; }

        .reg-features { display: flex; flex-direction: column; gap: 15px; }
        .reg-feature  { display: flex; align-items: center; gap: 12px; }
        .reg-feature-icon {
            width: 40px; height: 40px;
            background: rgba(232,180,184,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .reg-feature-text h3 { font-size: 13px; font-weight: 700; margin: 0 0 3px; }
        .reg-feature-text p  { font-size: 12px; opacity: 0.8; margin: 0; }

        /* ─── RIGHT PANEL ─────────────────────────── */
        .reg-right {
            flex: 1;
            padding: 50px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
            max-height: 100vh;
        }

        .reg-right::-webkit-scrollbar { width: 6px; }
        .reg-right::-webkit-scrollbar-track { background: #f3f4f6; }
        .reg-right::-webkit-scrollbar-thumb { background: var(--maroon-main); border-radius: 3px; }

        .reg-form-header h2 { font-size: 26px; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
        .reg-form-header p  { font-size: 13px; color: var(--text-secondary); margin-bottom: 24px; }

        /* ─── ALERT ─────────────────────────── */
        .alert-error {
            padding: 12px 15px;
            background: rgba(239,68,68,0.1);
            border-left: 4px solid var(--error);
            border-radius: 6px;
            color: #7f1d1d;
            font-size: 13px;
            margin-bottom: 20px;
        }
        .alert-error ul { margin: 4px 0 0 16px; }
        .alert-error li { margin-bottom: 2px; }
        .alert-success {
            padding: 12px 15px;
            background: rgba(16,185,129,0.1);
            border-left: 4px solid var(--success);
            border-radius: 6px;
            color: #065f46;
            font-size: 13px;
            margin-bottom: 20px;
        }

        /* ─── FORM ─────────────────────────── */
        .form-group { margin-bottom: 18px; }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 7px;
        }
        .form-group label .required { color: var(--error); }

        .form-group-wrapper { position: relative; }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 14px;
            padding-left: 40px;
            border: 1px solid var(--gray-border);
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text-primary);
            background: white;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--maroon-main);
            box-shadow: 0 0 0 3px rgba(165,42,42,0.1);
            background-color: #fafafa;
        }

        .form-group input.is-invalid,
        .form-group select.is-invalid {
            border-color: var(--error);
        }

        .form-group-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 14px;
            pointer-events: none;
            z-index: 1;
        }

        .password-toggle {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--text-secondary);
            cursor: pointer; font-size: 14px;
            transition: color 0.2s;
        }
        .password-toggle:hover { color: var(--maroon-main); }

        .field-error {
            display: block;
            font-size: 11px;
            color: var(--error);
            margin-top: 5px;
        }

        .form-hint {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 5px;
        }
        .form-hint i { font-size: 10px; margin-right: 3px; }

        /* Email domain badge */
        .email-domain-badge {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600;
            padding: 2px 8px; border-radius: 10px;
            background: rgba(165,42,42,0.1); color: var(--maroon-main);
            margin-top: 5px;
        }

        /* ─── SUBMIT BUTTON ─────────────────────────── */
        .btn-register {
            width: 100%;
            padding: 12px 20px;
            background: linear-gradient(135deg, var(--maroon-light) 0%, var(--maroon-main) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 4px;
            margin-bottom: 18px;
            position: relative;
            overflow: hidden;
        }
        .btn-register::before {
            content: ''; position: absolute;
            top: 0; left: -100%; width: 100%; height: 100%;
            background: rgba(255,255,255,0.2); transition: left 0.3s ease;
        }
        .btn-register:hover::before { left: 100%; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(165,42,42,0.4); }

        /* ─── FOOTER ─────────────────────────── */
        .reg-footer {
            text-align: center;
            font-size: 13px;
            color: var(--text-secondary);
        }
        .reg-footer a {
            color: var(--maroon-main);
            text-decoration: none;
            font-weight: 600;
        }
        .reg-footer a:hover { color: var(--maroon-dark); text-decoration: underline; }

        /* ─── RESPONSIVE ─────────────────────────── */
        @media (max-width: 768px) {
            body { padding: 10px; }
            .register-container { flex-direction: column; border-radius: 12px; }
            .reg-left { padding: 40px 30px; }
            .reg-right { padding: 40px 30px; max-height: none; }
            .reg-left h1 { font-size: 22px; }
            .reg-features { display: none; }
        }

        @media (max-width: 480px) {
            body { padding: 0; }
            .register-container { border-radius: 0; }
            .reg-left { padding: 30px 20px; }
            .reg-right { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<div class="register-container">

    {{-- LEFT PANEL --}}
    <div class="reg-left">
        <div class="reg-left-content">
            <div class="reg-logo">
                <i class="fas fa-user-graduate"></i>
            </div>
            <h1>Daftar Akun Mahasiswa</h1>
            <p>Buat akun untuk mengakses Sistem Informasi Kuliah Kerja Amaliah Universitas Muhammadiyah Kendari.</p>

            <div class="reg-features">
                <div class="reg-feature">
                    <div class="reg-feature-icon"><i class="fas fa-id-card"></i></div>
                    <div class="reg-feature-text">
                        <h3>NIM Resmi</h3>
                        <p>Gunakan NIM sesuai kartu mahasiswa</p>
                    </div>
                </div>
                <div class="reg-feature">
                    <div class="reg-feature-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <div class="reg-feature-text">
                        <h3>Email Institusi</h3>
                        <p>Wajib menggunakan @umkendari.ac.id</p>
                    </div>
                </div>
                <div class="reg-feature">
                    <div class="reg-feature-icon"><i class="fas fa-tasks"></i></div>
                    <div class="reg-feature-text">
                        <h3>Akses KKA</h3>
                        <p>Pendaftaran, pelaksanaan & pelaporan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="reg-right">
        <div class="reg-form-header">
            <h2>Buat Akun</h2>
            <p>Isi data diri Anda dengan lengkap dan benar</p>
        </div>

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>
            <strong>Terdapat kesalahan pada form:</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('mahasiswa.register.post') }}">
            @csrf

            {{-- NIM --}}
            <div class="form-group">
                <label>NIM <span class="required">*</span></label>
                <div class="form-group-wrapper">
                    <i class="fas fa-id-card form-group-icon"></i>
                    <input type="text" name="nim" value="{{ old('nim') }}"
                        placeholder="Nomor Induk Mahasiswa" maxlength="20"
                        class="{{ $errors->has('nim') ? 'is-invalid' : '' }}"
                        required autofocus>
                </div>
                @error('nim')
                <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Nama Lengkap --}}
            <div class="form-group">
                <label>Nama Lengkap <span class="required">*</span></label>
                <div class="form-group-wrapper">
                    <i class="fas fa-user form-group-icon"></i>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        placeholder="Nama sesuai KTP / Kartu Mahasiswa"
                        class="{{ $errors->has('nama') ? 'is-invalid' : '' }}"
                        required>
                </div>
                @error('nama')
                <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Program Studi --}}
            <div class="form-group">
                <label>Program Studi <span class="required">*</span></label>
                <div class="form-group-wrapper">
                    <i class="fas fa-graduation-cap form-group-icon"></i>
                    <select name="program_studi_id"
                        class="{{ $errors->has('program_studi_id') ? 'is-invalid' : '' }}"
                        required>
                        <option value="">-- Pilih Program Studi --</option>
                        @php
                            $grouped = $programStudiList->groupBy(fn($p) => $p->fakultas?->nama ?? 'Lainnya');
                        @endphp
                        @foreach($grouped as $fakultasNama => $list)
                        <optgroup label="{{ $fakultasNama }}">
                            @foreach($list as $prodi)
                            <option value="{{ $prodi->id }}"
                                {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->jenjang }} - {{ $prodi->nama }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>
                @error('program_studi_id')
                <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <div class="form-group-wrapper">
                    <i class="fas fa-envelope form-group-icon"></i>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="nim@umkendari.ac.id"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        required>
                </div>
                <div class="form-hint">
                    <i class="fas fa-info-circle"></i>
                    Gunakan email institusi &nbsp;
                    <span class="email-domain-badge">
                        <i class="fas fa-at" style="font-size:9px;"></i> umkendari.ac.id
                    </span>
                </div>
                @error('email')
                <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <div class="form-group-wrapper">
                    <i class="fas fa-lock form-group-icon"></i>
                    <input type="password" id="pw1" name="password"
                        placeholder="Minimal 8 karakter"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required>
                    <button type="button" class="password-toggle" onclick="togglePassword('pw1','icon-pw1')">
                        <i class="fas fa-eye" id="icon-pw1"></i>
                    </button>
                </div>
                @error('password')
                <span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div class="form-group">
                <label>Konfirmasi Password <span class="required">*</span></label>
                <div class="form-group-wrapper">
                    <i class="fas fa-lock form-group-icon"></i>
                    <input type="password" id="pw2" name="password_confirmation"
                        placeholder="Ulangi password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('pw2','icon-pw2')">
                        <i class="fas fa-eye" id="icon-pw2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i>
                <span>Daftar Sekarang</span>
            </button>

        </form>

        <div class="reg-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

</div>

@include('components.toast')

<script>
    function togglePassword(fieldId, iconId) {
        const input = document.getElementById(fieldId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>
