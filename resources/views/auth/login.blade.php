@extends('layouts.app')

@section('content')
@vite('resources/css/app.css')

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
    <div class="w-full max-w-md">
        <!-- Contenedor principal -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Pestañas de navegación -->
            <div class="flex border-b border-gray-200">
                <button id="loginTab"
                    class="flex-1 py-4 text-center font-semibold transition-all duration-300 border-b-2 border-indigo-600 text-indigo-600"
                    onclick="showLogin()">
                    Iniciar Sesión
                </button>
                <button id="registerTab"
                    class="flex-1 py-4 text-center font-semibold transition-all duration-300 border-b-2 border-transparent text-gray-500 hover:text-gray-700"
                    onclick="showRegister()">
                    Registrarse
                </button>
            </div>

            <!-- Formulario de Login -->
            <div id="loginForm" class="p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Bienvenido de nuevo</h2>

                <form onsubmit="handleLogin(event)">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="loginEmail">Correo electrónico</label>
                        <input type="email" id="loginEmail"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="tu@email.com" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="loginPassword">Contraseña</label>
                        <input type="password" id="loginPassword"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="••••••••" required>
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                        </label>
                        <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        Iniciar Sesión
                    </button>
                </form>

                <p class="text-center text-gray-600 text-sm mt-6">
                    ¿No tienes cuenta?
                    <button onclick="showRegister()" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        Regístrate aquí
                    </button>
                </p>
            </div>

            <!-- Formulario de Registro -->
            <div id="registerForm" class="p-8 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Crear cuenta nueva</h2>

                <form onsubmit="handleRegister(event)">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="registerName">Nombre completo</label>
                        <input type="text" id="registerName"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="Juan Pérez" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="registerEmail">Correo electrónico</label>
                        <input type="email" id="registerEmail"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="tu@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="registerPassword">Contraseña</label>
                        <input type="password" id="registerPassword"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="••••••••" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-semibold mb-2" for="registerPasswordConfirm">Confirmar contraseña</label>
                        <input type="password" id="registerPasswordConfirm"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
                            placeholder="••••••••" required>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-start">
                            <input type="checkbox"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mt-1"
                                required>
                            <span class="ml-2 text-sm text-gray-600">
                                Acepto los <a href="#" class="text-indigo-600 hover:text-indigo-800 font-semibold">términos y condiciones</a>
                            </span>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        Crear Cuenta
                    </button>
                </form>

                <p class="text-center text-gray-600 text-sm mt-6">
                    ¿Ya tienes cuenta?
                    <button onclick="showLogin()" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                        Inicia sesión aquí
                    </button>
                </p>
            </div>

        </div>
    </div>

    <script>
        function showLogin() {
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('loginTab').classList.add('border-indigo-600', 'text-indigo-600');
            document.getElementById('loginTab').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('registerTab').classList.remove('border-indigo-600', 'text-indigo-600');
            document.getElementById('registerTab').classList.add('border-transparent', 'text-gray-500');
        }

        function showRegister() {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.getElementById('registerTab').classList.add('border-indigo-600', 'text-indigo-600');
            document.getElementById('registerTab').classList.remove('border-transparent', 'text-gray-500');
            document.getElementById('loginTab').classList.remove('border-indigo-600', 'text-indigo-600');
            document.getElementById('loginTab').classList.add('border-transparent', 'text-gray-500');
        }



    </script>
</div>
@endsection
