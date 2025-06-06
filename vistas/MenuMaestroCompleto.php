<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Maestro - Sistema Escolar</title>
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
                    <i class="fas fa-school text-xl"></i>
                </div>
                <span class="logo-text ml-3 font-bold text-xl">TecNM Campus Monclova</span>
            </div>
            
            <!-- User Profile -->
            <div class="p-4 flex items-center border-b border-gray-700">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                    <span id="user-initials">M</span>
                </div>
                <div class="ml-3 sidebar-text">
                    <p class="font-semibold" id="user-name">Cargando...</p>
                    <p class="text-sm text-gray-400" id="user-role">Maestro</p>
                    <p class="text-xs text-gray-500" id="user-id">ID: --</p>
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
                    
                    <!-- Mi Actividad -->
                    <li class="nav-item" data-seccion="actividad">
                        <a href="#" onclick="mostrarSeccion('actividad')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded">
                            <i class="fas fa-running text-lg mr-3"></i>
                            <span class="sidebar-text">Mi Actividad</span>
                        </a>
                    </li>
                    
                    <!-- Mis Alumnos -->
                    <li class="nav-item" data-seccion="alumnos">
                        <a href="#" onclick="mostrarSeccion('alumnos')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded">
                            <i class="fas fa-users text-lg mr-3"></i>
                            <span class="sidebar-text">Mis Alumnos</span>
                        </a>
                    </li>
                    
                    <!-- Registros -->
                    <li class="nav-item" data-seccion="registros">
                        <a href="#" onclick="mostrarSeccion('registros')" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-700 rounded">
                            <i class="fas fa-list-alt text-lg mr-3"></i>
                            <span class="sidebar-text">Registros</span>
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
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                            <span id="header-user-initials">M</span>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Total Alumnos</p>
                                    <p class="text-2xl font-bold text-gray-900" id="total-students">0</p>
                                </div>
                                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-lg shadow-md p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Estado Actividad</p>
                                    <p class="text-2xl font-bold text-green-600" id="activity-status-card">Activo</p>
                                </div>
                                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-running text-purple-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Info Card -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6 card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-800" id="activity-name">Mi Actividad</h2>
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium" id="activity-status">Activo</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Instructor</p>
                                <p class="font-semibold text-gray-800" id="activity-instructor">Cargando...</p>
                                <p class="text-xs text-gray-500" id="activity-teacher-id">ID: --</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Especialidad</p>
                                <p class="font-semibold text-gray-800" id="activity-specialty">Cargando...</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Estado</p>
                                <p class="font-semibold text-green-600" id="activity-state">Activo</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN MI ACTIVIDAD -->
                <div id="seccion-actividad" class="seccion">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-bold text-gray-800">Gestión de Mi Actividad</h2>
                            <button class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                                <i class="fas fa-edit mr-2"></i>Editar Actividad
                            </button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-semibold mb-2">Información General</h3>
                                <div class="space-y-2">
                                    <p><strong>Nombre:</strong> <span id="activity-detail-name">Cargando...</span></p>
                                    <p><strong>Descripción:</strong> <span id="activity-description">Actividad deportiva para estudiantes</span></p>
                                    <p><strong>Horario:</strong> <span id="activity-schedule">Lunes a Viernes 10:00 - 12:00</span></p>
                                    <p><strong>Lugar:</strong> <span id="activity-location">Gimnasio Principal</span></p>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-2">Estadísticas</h3>
                                <div class="space-y-2">
                                    <p><strong>Alumnos Inscritos:</strong> <span id="activity-students-count">0</span></p>
                                    <p><strong>Capacidad Máxima:</strong> <span id="activity-capacity">25 estudiantes</span></p>
                                    <p><strong>Clases Impartidas:</strong> <span id="activity-classes">45</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN ALUMNOS -->
                <div id="seccion-alumnos" class="seccion">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Mis Alumnos Inscritos</h3>
                            <button onclick="cargarAlumnos()" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                                <i class="fas fa-sync-alt mr-2"></i>Actualizar
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Matrícula</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semestre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="students-table-body">
                                    <!-- Datos dinámicos -->
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div>
                                <p class="text-sm text-gray-500" id="pagination-info">
                                    Mostrando <span class="font-medium">0</span> a <span class="font-medium">0</span> de <span class="font-medium">0</span> alumnos
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECCIÓN REGISTROS -->
                <div id="seccion-registros" class="seccion">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-800">Registros de Actividad</h3>
                            <button onclick="cargarRegistros()" class="px-4 py-2 bg-primary text-white rounded hover:bg-blue-600">
                                <i class="fas fa-sync-alt mr-2"></i>Actualizar
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actividad</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="registros-table-body">
                                    <!-- Datos dinámicos -->
                                </tbody>
                            </table>
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
        let currentMaestroId = null;
        let currentData = null;
        let currentSection = 'dashboard';

        // Función para obtener datos del usuario (VERSIÓN CORREGIDA)
        function obtenerDatosUsuario() {
            // 1. Intentar desde sessionStorage (datos del login)
            const sessionData = sessionStorage.getItem('userData');
            if (sessionData) {
                try {
                    const userData = JSON.parse(sessionData);
                    
                    // Verificar que sea un maestro y que la sesión no haya expirado
                    if (userData.id && userData.tipoUsuario === 2) {
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
                        console.log('Usuario no es maestro o datos inválidos');
                        sessionStorage.removeItem('userData');
                    }
                } catch (e) {
                    console.error('Error parsing sessionData:', e);
                    sessionStorage.removeItem('userData');
                }
            }
            
            // 2. Intentar desde URL params (para testing)
            const urlParams = new URLSearchParams(window.location.search);
            const maestroIdFromUrl = urlParams.get('maestroId');
            
            if (maestroIdFromUrl) {
                console.log('Usuario obtenido desde URL params:', maestroIdFromUrl);
                return { id: parseInt(maestroIdFromUrl) };
            }
            
            // 3. Intentar desde localStorage (fallback)
            const localData = localStorage.getItem('userData');
            if (localData) {
                try {
                    const userData = JSON.parse(localData);
                    if (userData.id && userData.tipoUsuario === 2) {
                        console.log('Usuario obtenido desde localStorage:', userData);
                        return userData;
                    }
                } catch (e) {
                    console.error('Error parsing localStorage:', e);
                    localStorage.removeItem('userData');
                }
            }
            
            // 4. Si no hay sesión válida, redirigir al login
            console.log('No hay sesión válida, redirigiendo al login');
            alert('No hay sesión activa. Redirigiendo al login...');
            window.location.href = 'login.html';
            return null;
        }

        // Función para obtener iniciales del nombre
        function obtenerIniciales(nombre) {
            if (!nombre) return 'M';
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
                'actividad': 'Mi Actividad',
                'alumnos': 'Mis Alumnos',
                'registros': 'Registros'
            };
            document.getElementById('page-title').textContent = titles[seccion] || 'Panel del Maestro';
            
            currentSection = seccion;
            
            // Cargar datos específicos de la sección
            switch(seccion) {
                case 'alumnos':
                    cargarAlumnos();
                    break;
                case 'registros':
                    cargarRegistros();
                    break;
            }
        }

        // Función principal para cargar datos del maestro
        async function cargarDatosMaestroDesdeAPI() {
            const userData = obtenerDatosUsuario();
            
            if (!userData || !userData.id) {
                console.error('No se encontró ID de maestro');
                mostrarError('No se encontró información del maestro');
                return;
            }

            currentMaestroId = userData.id;
            toggleLoading(true);

            try {
                const response = await fetch(`${API_BASE_URL}/maestro/datos/${currentMaestroId}`);
                
                if (response.ok) {
                    const data = await response.json();
                    currentData = data;
                    
                    console.log('Datos recibidos:', data);
                    
                    actualizarInfoMaestro(data.maestro);
                    actualizarInfoActividad(data.actividad);
                    actualizarEstadisticas(data);
                    
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

        // Función para actualizar información del maestro
        function actualizarInfoMaestro(maestro) {
            const iniciales = obtenerIniciales(maestro.nombre);
            
            document.getElementById('user-name').textContent = maestro.nombre;
            document.getElementById('user-id').textContent = `ID: ${maestro.id}`;
            document.getElementById('user-initials').textContent = iniciales;
            document.getElementById('header-user-initials').textContent = iniciales;
            document.getElementById('header-user-info').textContent = `${maestro.nombre} (Maestro)`;
            document.getElementById('activity-instructor').textContent = maestro.nombre;
            document.getElementById('activity-teacher-id').textContent = `ID: ${maestro.id}`;
            document.getElementById('activity-specialty').textContent = maestro.especialidad || 'No especificada';
        }

        // Función para actualizar información de la actividad
        function actualizarInfoActividad(actividad) {
            document.getElementById('activity-name').textContent = actividad.nombre;
            document.getElementById('activity-status').textContent = actividad.estado;
            document.getElementById('activity-state').textContent = actividad.estado;
            document.getElementById('activity-status-card').textContent = actividad.estado;
            
            if (document.getElementById('activity-detail-name')) {
                document.getElementById('activity-detail-name').textContent = actividad.nombre;
            }
            
            // Actualizar colores según el estado
            const statusElements = [
                document.getElementById('activity-status'),
                document.getElementById('activity-state'),
                document.getElementById('activity-status-card')
            ];
            
            statusElements.forEach(element => {
                if (actividad.estado === 'Activo') {
                    element.className = element.className.replace(/text-red-\d+|bg-red-\d+/, '');
                    if (element.id === 'activity-status') {
                        element.className = 'px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium';
                    } else {
                        element.className = element.className.replace(/text-red-\d+/, 'text-green-600');
                    }
                } else {
                    element.className = element.className.replace(/text-green-\d+|bg-green-\d+/, '');
                    if (element.id === 'activity-status') {
                        element.className = 'px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium';
                    } else {
                        element.className = element.className.replace(/text-green-\d+/, 'text-red-600');
                    }
                }
            });
        }

        // Función para actualizar estadísticas
        function actualizarEstadisticas(data) {
            document.getElementById('total-students').textContent = data.totalAlumnos;
            
            if (document.getElementById('activity-students-count')) {
                document.getElementById('activity-students-count').textContent = data.totalAlumnos;
            }
        }

        // Funciones para cargar datos específicos de cada sección
        async function cargarAlumnos() {
            if (!currentData || !currentData.alumnos) return;
            
            const tbody = document.getElementById('students-table-body');
            tbody.innerHTML = '';
            
            if (currentData.alumnos.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No hay alumnos inscritos en esta actividad
                        </td>
                    </tr>
                `;
            } else {
                currentData.alumnos.forEach(alumno => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #${alumno.matricula}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">
                                    ${obtenerIniciales(alumno.nombre)}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${alumno.nombre}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${alumno.semestre}°</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div>${alumno.correo}</div>
                            <div class="text-xs text-gray-400">${alumno.telefono || 'No disponible'}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button onclick="verDetalleAlumno(${alumno.id})" class="text-blue-600 hover:text-blue-900 mr-2">Ver</button>
                            <button onclick="editarAlumno(${alumno.id})" class="text-green-600 hover:text-green-900">Editar</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
            
            // Actualizar información de paginación
            const paginationInfo = document.getElementById('pagination-info');
            paginationInfo.innerHTML = `
                Mostrando <span class="font-medium">1</span> a 
                <span class="font-medium">${currentData.alumnos.length}</span> de 
                <span class="font-medium">${currentData.alumnos.length}</span> alumnos
            `;
        }

        async function cargarRegistros() {
            if (!currentData || !currentData.actividad) return;
            
            try {
                const response = await fetch(`${API_BASE_URL}/registro/por-actividad/${currentData.actividad.id}`);
                
                if (response.ok) {
                    const registros = await response.json();
                    actualizarTablaRegistros(registros);
                }
            } catch (error) {
                console.error('Error al cargar registros:', error);
            }
        }

        function actualizarTablaRegistros(registros) {
            const tbody = document.getElementById('registros-table-body');
            tbody.innerHTML = '';
            
            if (registros.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No hay registros disponibles
                        </td>
                    </tr>
                `;
                return;
            }
            
            registros.forEach(registro => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        #${registro.id}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Usuario ID: ${registro.idUsuario}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        Actividad ID: ${registro.idActividad}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full ${registro.estatus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            ${registro.estatus ? 'Activo' : 'Inactivo'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${new Date(registro.fechaCreacion).toLocaleDateString()}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Funciones de utilidad
        function verDetalleAlumno(id) {
            console.log('Ver detalle del alumno:', id);
            alert(`Ver detalle del alumno ID: ${id}`);
        }

        function editarAlumno(id) {
            console.log('Editar alumno:', id);
            alert(`Editar alumno ID: ${id}`);
        }

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
            cargarDatosMaestroDesdeAPI();
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const userData = obtenerDatosUsuario();
            if (!userData) {
                return;
            }
            
            document.getElementById('sidebar-toggle').addEventListener('click', toggleSidebar);
            cargarDatosMaestroDesdeAPI();
        });

        setInterval(refrescarDatos, 300000);
    </script>
</body>
</html>
