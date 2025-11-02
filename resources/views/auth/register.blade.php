@extends('layouts.app')

@section('content')
    @vite('resources/css/app.css')

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-indigo-100 p-6">
        <div class="w-full max-w-md">
            <!-- Card principal -->
            <div class="bg-white rounded-3xl shadow-2xl p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Crear cuenta nueva</h2>

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="registerName">Nombre completo</label>
                        <input type="text" id="registerName" name="name"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition duration-200"
                            placeholder="Juan Pérez" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="registerEmail">Correo electrónico</label>
                        <input type="email" id="registerEmail" name="email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition duration-200"
                            placeholder="tu@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="registerPassword">Contraseña</label>
                        <input type="password" id="registerPassword" name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition duration-200"
                            placeholder="••••••••" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-2" for="registerPasswordConfirm">Confirmar contraseña</label>
                        <input type="password" id="registerPasswordConfirm" name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent transition duration-200"
                            placeholder="••••••••" required>
                    </div>

                    <div class="mb-6 flex items-start">
                        <input type="checkbox" name="terms"
                            class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1" required>
                        <span class="ml-3 text-sm text-gray-600">
                            Acepto los <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">términos y condiciones</a>
                        </span>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        Crear Cuenta
                    </button>
                </form>

                <p class="text-center text-gray-600 text-sm mt-6">
                    ¿Ya tienes cuenta?
                    <button onclick="showLogin()" class="text-indigo-600 hover:text-indigo-800 font-semibold ml-1">
                        Inicia sesión aquí
                    </button>
                </p>
            </div>
        </div>
    </div>
@endsection
