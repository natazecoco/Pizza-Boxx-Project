<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pizza Boxx</title>
    {{-- Kita akan menambahkan CSS dari Tailwind di sini --}}
    @vite('resources/css/app.css')
</head>
<body>
    {{-- Bagian header dan navbar akan ada di sini --}}
    
    <main>
        {{-- Konten unik setiap halaman akan masuk di sini --}}
        @yield('content')
    </main>

    {{-- Bagian footer akan ada di sini --}}

    {{-- Kita akan menambahkan JavaScript di sini --}}
    @vite('resources/js/app.js')
</body>
</html>