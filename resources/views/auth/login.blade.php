@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Iniciar sesión</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="loginEmail">Correo electrónico</label>
                <input type="email" id="loginEmail" name="email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition duration-200"
                    placeholder="tu@email.com" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="loginPassword">Contraseña</label>
                <input type="password" id="loginPassword" name="password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition duration-200"
                    placeholder="••••••••" required>
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                Iniciar Sesión
            </button>

        </form>
        <a href="/registrarUsuario"> no tienes cuenta registrte</a>
    </div>
</div>
@endsection
