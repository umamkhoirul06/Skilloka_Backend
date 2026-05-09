<!DOCTYPE html>
<html>
<body style="font-family: Inter, sans-serif; background: #f8fafc; padding: 40px;">
    <div style="max-width: 500px; margin: auto; background: white; border-radius: 16px; padding: 40px;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <span style="font-size: 48px;">🎉</span>
            <h2 style="color: #4f46e5;">Verifikasi Berhasil!</h2>
        </div>

        <p style="color: #374151;">Halo, <strong>{{ $adminName }}</strong>!</p>

        <p style="color: #374151; line-height: 1.6;">
            LPK <strong>{{ $lpkName }}</strong> Anda telah berhasil diverifikasi 
            oleh tim Skilloka. Akun Anda sudah aktif dan dapat digunakan.
        </p>

        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px; margin: 24px 0;">
            ✅ Akun LPK aktif<br>
            ✅ Dapat mengelola kursus<br>
            ✅ Siswa dapat menemukan LPK Anda
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.login') }}" 
               style="background: linear-gradient(135deg, #6366f1, #4f46e5); color: white; padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: 600;">
                Login Sekarang
            </a>
        </div>

        <p style="color: #9ca3af; font-size: 12px; text-align: center; margin-top: 30px;">
            © 2025 Skilloka
        </p>
    </div>
</body>
</html>