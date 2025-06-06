<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Alumno - Actividades Extraescolares</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB'
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar {
            transition: width 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .logo-text {
            display: none;
        }
        .main-content {
            transition: margin-left 0.3s ease;
        }
        .main-content.expanded {
            margin-left: 80px;
        }
        .nav-item:hover {
            background-color: rgba(55, 65, 81, 0.8);
        }
        .nav-item.active {
            background-color: rgba(79, 70, 229, 0.8);
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .loading {
            display: none;
        }
        .loading.active {
            display: block;
        }
        .seccion {
            display: none;
        }
        .seccion.active {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-gray-800 text-white shadow-lg w-64 fixed h-full flex flex-col">
            <!-- Logo -->
            <div class="p-4 flex items-center border-b border-gray-700">
                <div class="w-20 h-14 rounded-full bg-primary flex items-center justify-center text-white">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <span class="logo-text ml-3 font-bold text-xl">TecNM Campus Monclova</span>
            </div>
            
            <!-- User Profile -->
            <div class="p-4 flex items-center border-b border-gray-700">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center text-white font-bold">
                    <span id="user-initials">A</span>
                </div>
                <div class="ml-3 sidebar-text">
                    <p class="font-semibold" id="user-name">Cargando...</p>
                    <p class="text-sm text-gray-400" id="user-role">Alumno</p>
                    <p class="text-xs text-gray-500" id="user-matricula">Matrícula: --</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto">
                <ul class="py-2">
                    <!-- Dashboard -->
                    <li class="nav-item active" data-seccion="dashboard">
                        <a href="#" onclick="mostrarSeccion('dashboard')" class="flex items-center px-4 py-3 text-white rounded">
                            <i class="fas fa-tachometer-alt text-lg mr-3"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Mis Actividades -->
                    <li class="nav-item" data-seccion="mis-actividades">
                        <a href="#" onclick="mostrarSeccion('mis-actividades')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded">
                            <i class="fas fa-check-circle text-lg mr-3"></i>
                            <span class="sidebar-text">Mis Actividades</span>
                        </a>
                    </li>
                    
                    <!-- Actividades Disponibles -->
                    <li class="nav-item" data-seccion="disponibles">
                        <a href="#" onclick="mostrarSeccion('disponibles')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded">
                            <i class="fas fa-plus-circle text-lg mr-3"></i>
                            <span class="sidebar-text">Actividades Disponibles</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Logout -->
            <div class="p-4 border-t border-gray-700">
                <a href="#" onclick="logout()" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded">
                    <i class="fas fa-sign-out-alt text-lg mr-3"></i>
                    <span class="sidebar-text">Cerrar Sesión</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 ml-64 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 hover:text-gray-700 focus:outline-none mr-4">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-2xl font-bold text-gray-800" id="page-title">Dashboard</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <!-- Refrescar -->
                        <button onclick="refrescarDatos()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        
                        <!-- Usuario -->
                        <span class="text-sm text-gray-600" id="header-user-info">Cargando...</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center text-white font-bold">
                            <span id="header-user-initials">A</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Loading Indicator -->
            <div id="loading" class="loading p-6">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    <span class="ml-2 text-gray-600">Cargando datos...</span>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6" id="main-content">
                
                <!-- SECCIÓN DASHBOARD -->
                <div id="seccion-dashboard" class="seccion active">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Actividades Inscritas</p>
                                    <p class="text-2xl font-bold text-gray-900" id="total-mis-actividades">0</p>
                                </div>
                                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Actividades Disponibles</p>
                                    <p class="text-2xl font-bold text-gray-900" id="total-disponibles">0</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-plus-circle text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Mi Semestre</p>
                                    <p class="text-2xl font-bold text-gray-900" id="user-semestre">--</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Student Info Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Mi Información</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Nombre Completo</p>
                                <p class="font-semibold text-gray-800" id="student-name">Cargando...</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Carrera</p>
                                <p class="font-semibold text-gray-800" id="student-carrera">Cargando...</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Correo</p>
                                <p class="font-semibold text-gray-800" id="student-email">Cargando...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN MIS ACTIVIDADES -->
                <div id="seccion-mis-actividades" class="seccion">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Mis Actividades Inscritas</h2>
                            <button onclick="cargarMisActividades()" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                                <i class="fas fa-sync-alt mr-2"></i>Actualizar
                            </button>
                        </div>
                        <div id="mis-actividades-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Las actividades se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN ACTIVIDADES DISPONIBLES -->
                <div id="seccion-disponibles" class="seccion">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Actividades Disponibles</h2>
                            <button onclick="cargarActividadesDisponibles()" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                                <i class="fas fa-sync-alt mr-2"></i>Actualizar
                            </button>
                        </div>
                        <div id="actividades-disponibles-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Las actividades se cargarán dinámicamente -->
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        // Configuración de la API
        const API_BASE_URL = 'http://localhost:5111/api';
        
        // Variables globales
        let currentAlumnoId = null;
        let currentData = null;
        let misActividades = [];
        let actividadesDisponibles = [];

        // Función para obtener datos del usuario
        function obtenerDatosUsuario() {
            // 1. Intentar desde sessionStorage (datos del login)
            const sessionData = sessionStorage.getItem('userData');
            if (sessionData) {
                try {
                    const userData = JSON.parse(sessionData);
                    
                    // Verificar que sea un alumno y que la sesión no haya expirado
                    if (userData.id && userData.tipoUsuario === 3) {
                        const currentTime = new Date().getTime();
                        const loginTime = userData.loginTime || 0;
                        const timeLimit = 8 * 60 * 60 * 1000; // 8 horas
                        
                        if ((currentTime - loginTime) < timeLimit) {
                            console.log('Usuario obtenido desde sessionStorage:', userData);
                            return userData;
                        } else {
                            // Sesión expirada
                            console.log('Sesión expirada');
                            sessionStorage.removeItem('userData');
                        }
                    } else {
                        console.log('Usuario no es alumno o datos inválidos');
                        sessionStorage.removeItem('userData');
                    }
                } catch (e) {
                    console.error('Error parsing sessionData:', e);
                    sessionStorage.removeItem('userData');
                }
            }
            
            // 2. Intentar desde URL params (para testing)
            const urlParams = new URLSearchParams(window.location.search);
            const alumnoIdFromUrl = urlParams.get('alumnoId');
            
            if (alumnoIdFromUrl) {
                console.log('Usuario obtenido desde URL params:', alumnoIdFromUrl);
                return { id: parseInt(alumnoIdFromUrl) };
            }
            
            // 3. Si no hay sesión válida, redirigir al login
            console.log('No hay sesión válida, redirigiendo al login');
            alert('No hay sesión activa. Redirigiendo al login...');
            window.location.href = 'login.html';
            return null;
        }

        // Función para obtener iniciales del nombre
        function obtenerIniciales(nombre) {
            if (!nombre) return 'A';
            const palabras = nombre.split(' ');
            if (palabras.length >= 2) {
                return (palabras[0][0] + palabras[1][0]).toUpperCase();
            }
            return nombre.substring(0, 2).toUpperCase();
        }

        // Función para mostrar/ocultar loading
        function toggleLoading(show) {
            const loading = document.getElementById('loading');
            const mainContent = document.getElementById('main-content');
            
            if (show) {
                loading.classList.add('active');
                mainContent.style.display = 'none';
            } else {
                loading.classList.remove('active');
                mainContent.style.display = 'block';
            }
        }

        // Función para mostrar secciones
        function mostrarSeccion(seccion) {
            // Ocultar todas las secciones
            document.querySelectorAll('.seccion').forEach(s => s.classList.remove('active'));
            
            // Mostrar la sección seleccionada
            document.getElementById(`seccion-${seccion}`).classList.add('active');
            
            // Actualizar navegación activa
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
                item.querySelector('a').className = item.querySelector('a').className.replace('text-white', 'text-gray-300');
            });
            
            const activeNavItem = document.querySelector(`[data-seccion="${seccion}"]`);
            if (activeNavItem) {
                activeNavItem.classList.add('active');
                activeNavItem.querySelector('a').className = activeNavItem.querySelector('a').className.replace('text-gray-300', 'text-white');
            }
            
            // Actualizar título de la página
            const titles = {
                'dashboard': 'Dashboard',
                'mis-actividades': 'Mis Actividades',
                'disponibles': 'Actividades Disponibles'
            };
            document.getElementById('page-title').textContent = titles[seccion] || 'Panel del Alumno';
            
            // Cargar datos específicos de la sección
            switch(seccion) {
                case 'mis-actividades':
                    cargarMisActividades();
                    break;
                case 'disponibles':
                    cargarActividadesDisponibles();
                    break;
            }
        }

        // Función principal para cargar datos del alumno
        async function cargarDatosAlumnoDesdeAPI() {
            const userData = obtenerDatosUsuario();
            
            if (!userData || !userData.id) {
                console.error('No se encontró ID de alumno');
                mostrarError('No se encontró información del alumno');
                return;
            }

            currentAlumnoId = userData.id;
            toggleLoading(true);

            try {
                const response = await fetch(`${API_BASE_URL}/alumno/datos/${currentAlumnoId}`);
                
                if (response.ok) {
                    const data = await response.json();
                    currentData = data;
                    
                    console.log('Datos recibidos:', data);
                    
                    actualizarInfoAlumno(data.alumno);
                    
                    // Cargar actividades
                    await cargarMisActividades();
                    await cargarActividadesDisponibles();
                    
                    console.log('Datos cargados correctamente');
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error al cargar datos');
                }
            } catch (error) {
                console.error('Error al cargar datos desde API:', error);
                mostrarError('Error al cargar los datos: ' + error.message);
            } finally {
                toggleLoading(false);
            }
        }

        // Función para actualizar información del alumno
        function actualizarInfoAlumno(alumno) {
            const iniciales = obtenerIniciales(alumno.nombre);
            
            document.getElementById('user-name').textContent = alumno.nombre;
            document.getElementById('user-matricula').textContent = `Matrícula: ${alumno.matricula}`;
            document.getElementById('user-initials').textContent = iniciales;
            document.getElementById('header-user-initials').textContent = iniciales;
            document.getElementById('header-user-info').textContent = `${alumno.nombre} (Alumno)`;
            document.getElementById('user-semestre').textContent = `${alumno.semestre}°`;
            
            // Información en dashboard
            document.getElementById('student-name').textContent = alumno.nombre;
            document.getElementById('student-carrera').textContent = alumno.especialidad || 'No especificada';
            document.getElementById('student-email').textContent = alumno.correo;
        }

        // Función para cargar mis actividades
        async function cargarMisActividades() {
            if (!currentAlumnoId) return;
            
            try {
                const response = await fetch(`${API_BASE_URL}/alumno/mis-actividades/${currentAlumnoId}`);
                
                if (response.ok) {
                    misActividades = await response.json();
                    actualizarMisActividades();
                    document.getElementById('total-mis-actividades').textContent = misActividades.length;
                }
            } catch (error) {
                console.error('Error al cargar mis actividades:', error);
            }
        }

        // Función para cargar actividades disponibles
        async function cargarActividadesDisponibles() {
            if (!currentAlumnoId) return;
            
            try {
                const response = await fetch(`${API_BASE_URL}/alumno/actividades-disponibles/${currentAlumnoId}`);
                
                if (response.ok) {
                    actividadesDisponibles = await response.json();
                    actualizarActividadesDisponibles();
                    document.getElementById('total-disponibles').textContent = actividadesDisponibles.length;
                }
            } catch (error) {
                console.error('Error al cargar actividades disponibles:', error);
            }
        }

        // Función para actualizar la vista de mis actividades (SIN descripción)
        function actualizarMisActividades() {
            const container = document.getElementById('mis-actividades-container');
            container.innerHTML = '';
            
            if (misActividades.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-info-circle text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">No tienes actividades inscritas aún</p>
                        <button onclick="mostrarSeccion('disponibles')" class="mt-4 px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                            Ver Actividades Disponibles
                        </button>
                    </div>
                `;
                return;
            }
            
            misActividades.forEach(actividad => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md p-6 card-hover';
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">${actividad.nombre}</h3>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Inscrito</span>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <p><strong>Instructor:</strong> ${actividad.maestro}</p>
                        <p><strong>Especialidad:</strong> ${actividad.maestroEspecialidad || 'General'}</p>
                        <p><strong>Fecha de inscripción:</strong> ${new Date(actividad.fechaInscripcion).toLocaleDateString()}</p>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button onclick="desinscribirse(${actividad.actividadId})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>Desinscribirse
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Función para actualizar la vista de actividades disponibles (SIN descripción)
        function actualizarActividadesDisponibles() {
            const container = document.getElementById('actividades-disponibles-container');
            container.innerHTML = '';
            
            if (actividadesDisponibles.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-600">No hay actividades disponibles en este momento</p>
                    </div>
                `;
                return;
            }
            
            actividadesDisponibles.forEach(actividad => {
                const card = document.createElement('div');
                card.className = 'bg-white rounded-lg shadow-md p-6 card-hover';
                card.innerHTML = `
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">${actividad.nombre}</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Disponible</span>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <p><strong>Instructor:</strong> ${actividad.maestro}</p>
                        <p><strong>Especialidad:</strong> ${actividad.maestroEspecialidad || 'General'}</p>
                        <p><strong>Cupos:</strong> ${actividad.cuposDisponibles}/${actividad.cuposTotal}</p>
                    </div>
                    <div class="mt-4">
                        <button onclick="inscribirse(${actividad.id})" 
                                class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 ${actividad.cuposDisponibles <= 0 ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${actividad.cuposDisponibles <= 0 ? 'disabled' : ''}>
                            <i class="fas fa-plus mr-2"></i>
                            ${actividad.cuposDisponibles <= 0 ? 'Sin Cupos' : 'Inscribirse'}
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Función para inscribirse a una actividad
        async function inscribirse(actividadId) {
            if (!currentAlumnoId) return;
            
            if (!confirm('¿Estás seguro de que quieres inscribirte a esta actividad?')) return;
            
            try {
                const response = await fetch(`${API_BASE_URL}/alumno/inscribirse`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        alumnoId: currentAlumnoId,
                        actividadId: actividadId
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('¡Inscripción exitosa!');
                    // Recargar datos
                    await cargarMisActividades();
                    await cargarActividadesDisponibles();
                } else {
                    alert(result.message || 'Error al inscribirse');
                }
            } catch (error) {
                console.error('Error al inscribirse:', error);
                alert('Error de conexión');
            }
        }

        // Función para desinscribirse de una actividad
        async function desinscribirse(actividadId) {
            if (!currentAlumnoId) return;
            
            if (!confirm('¿Estás seguro de que quieres desinscribirte de esta actividad?')) return;
            
            try {
                const response = await fetch(`${API_BASE_URL}/alumno/desinscribirse/${currentAlumnoId}/${actividadId}`, {
                    method: 'DELETE'
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('Te has desinscrito exitosamente');
                    // Recargar datos
                    await cargarMisActividades();
                    await cargarActividadesDisponibles();
                } else {
                    alert(result.message || 'Error al desinscribirse');
                }
            } catch (error) {
                console.error('Error al desinscribirse:', error);
                alert('Error de conexión');
            }
        }

        // Función para mostrar errores
        function mostrarError(mensaje) {
            const mainContent = document.getElementById('main-content');
            mainContent.innerHTML = `
                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                    <h3 class="text-lg font-semibold text-red-800 mb-2">Error</h3>
                    <p class="text-red-600">${mensaje}</p>
                    <button onclick="location.reload()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Reintentar
                    </button>
                </div>
            `;
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }

        function logout() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                sessionStorage.removeItem('userData');
                localStorage.removeItem('userData');
                
                try {
                    fetch(`${API_BASE_URL}/login/logout`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
                } catch (error) {
                    console.log('Error en logout API:', error);
                }
                
                window.location.href = 'login.html';
            }
        }

        function refrescarDatos() {
            cargarDatosAlumnoDesdeAPI();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const userData = obtenerDatosUsuario();
            if (!userData) {
                return;
            }
            
            document.getElementById('sidebar-toggle').addEventListener('click', toggleSidebar);
            cargarDatosAlumnoDesdeAPI();
        });

        setInterval(refrescarDatos, 300000);
    </script>
</body>
</html>
