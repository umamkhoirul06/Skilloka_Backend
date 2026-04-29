<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Skilloka</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top left, #1e1b4b, #0f172a, #020617);
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        /* Animasi Melayang (Floating) - Ringan & Smooth */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(2deg);
            }
        }

        .animate-float {
            animation: float 5s ease-in-out infinite;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.8);
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body>
    <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-600/20 blur-[100px] rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-600/10 blur-[120px] rounded-full"></div>

    <div
        class="glass relative z-10 p-10 md:p-16 rounded-[2.5rem] text-center max-w-lg w-[90%] transition-all hover:scale-[1.02]">
        <div class="animate-float inline-block p-5 rounded-3xl bg-white/5 mb-6">
            @yield('icon')
        </div>

        <h1
            class="text-7xl md:text-8xl font-extrabold mb-4 opacity-20 absolute -top-10 left-1/2 -translate-x-1/2 w-full">
            @yield('code')
        </h1>

        <h2 class="text-3xl font-bold mb-3 relative">@yield('headline')</h2>
        <p class="text-gray-400 mb-8 leading-relaxed">@yield('message')</p>

        <a href="{{ url('/') }}"
            class="inline-flex items-center gap-2 px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-2xl transition-all shadow-lg shadow-indigo-500/20 active:scale-95">
            <i data-lucide="home" class="w-5 h-5"></i>
            Kembali ke Dashboard
        </a>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>