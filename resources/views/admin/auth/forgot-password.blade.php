<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Skilloka</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin:0; padding:0; box-sizing:border-box; }
        body { background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%); min-height: 100vh; display:flex; align-items:center; justify-content:center; padding:16px; }
        
        .card { background: rgba(255,255,255,0.08); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 32px; width: 100%; max-width: 420px; }
        
        .icon-wrap { width:64px; height:64px; border-radius:16px; background: linear-gradient(135deg,#3b82f6,#7c3aed); display:flex; align-items:center; justify-content:center; margin: 0 auto 16px; }
        
        .title { font-size:24px; font-weight:700; color:white; text-align:center; margin-bottom:8px; }
        .subtitle { font-size:14px; color:#9ca3af; text-align:center; margin-bottom:28px; }
        
        .label { display:block; color:#d1d5db; font-size:14px; font-weight:500; margin-bottom:8px; }
        
        .input-wrap { position:relative; margin-bottom:20px; }
        .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#6b7280; }
        .input-field { width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:12px; padding:14px 14px 14px 44px; color:white; font-size:14px; }
        .input-field::placeholder { color:#6b7280; }
        .input-field:focus { outline:none; background:rgba(255,255,255,0.1); border-color:rgba(102,126,234,0.6); }
        
        .btn { width:100%; padding:14px; background:linear-gradient(135deg,#667eea,#764ba2); color:white; font-weight:600; font-size:15px; border:none; border-radius:12px; cursor:pointer; transition:.2s; }
        .btn:hover { transform:translateY(-2px); box-shadow:0 10px 40px rgba(102,126,234,0.4); }
        
        .back-link { display:block; text-align:center; margin-top:20px; color:#a78bfa; font-size:14px; text-decoration:none; }
        .back-link:hover { color:#c4b5fd; }

        .alert-success { background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.2); border-radius:10px; padding:14px; color:#4ade80; font-size:14px; margin-bottom:20px; }
        .alert-error { background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.2); border-radius:10px; padding:14px; color:#f87171; font-size:14px; margin-bottom:20px; }
    </style>
</head>
<body>
<div class="card">

    <div class="icon-wrap">
        <svg width="32" height="32" fill="none" stroke="white" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
        </svg>
    </div>

    <h2 class="title">Lupa Password?</h2>
    <p class="subtitle">Masukkan email kamu, kami akan kirimkan link reset password.</p>

    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label class="label">Email Address</label>
        <div class="input-wrap">
            <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <input type="email" name="email" class="input-field" placeholder="admin@lpk.com" value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="btn">Kirim Link Reset Password</button>
    </form>

    <a href="{{ route('admin.login') }}" class="back-link">← Kembali ke Login</a>
</div>
</body>
</html>