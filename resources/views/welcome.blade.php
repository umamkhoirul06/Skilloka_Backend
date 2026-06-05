<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Skilloka - Platform LPK Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="bg-slate-900 text-white flex items-center justify-center min-h-screen selection:bg-blue-500 selection:text-white">
    <div class="text-center px-6">
        <div class="mb-6 flex justify-center">
            <div
                class="w-20 h-20 bg-blue-600/20 text-blue-500 rounded-full flex items-center justify-center text-4xl shadow-[0_0_40px_rgba(37,99,235,0.3)]">
                🚀
            </div>
        </div>

        <h1
            class="text-5xl font-extrabold mb-4 bg-gradient-to-r from-blue-400 to-indigo-500 bg-clip-text text-transparent">
            Skilloka Server
        </h1>
        <p class="text-slate-400 mb-10 max-w-md mx-auto leading-relaxed">
            Sistem API Backend dan Manajemen Database untuk Aplikasi Mobile Skilloka berjalan dengan normal (Status:
            Active).
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/admin/login') }}"
                class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-all duration-300 shadow-lg shadow-blue-600/30">
                🔒 Masuk ke Admin Panel
            </a>
        </div>

        <div class="mt-16 text-slate-500 text-sm">
            &copy; {{ date('Y') }} Skilloka. Hak Cipta Dilindungi.
        </div>
    </div>
</body>

</html>