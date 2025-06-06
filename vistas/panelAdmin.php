<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { transition: all 0.3s ease; }
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .menu-text { display: none; }
        .sidebar.collapsed .logo-text { display: none; }
        .sidebar.collapsed .menu-item { justify-content: center; }
        .content { transition: all 0.3s ease; }
        .content.expanded { margin-left: 80px; }
        @media (max-width: 768px) {
            .sidebar { position: fixed; z-index: 50; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .content { margin-left: 0 !important; }
        }
        
        /* Nueva paleta de colores */
        .bg-primary { background-color: #1e293b; } /* Slate 800 */
        .bg-primary-light { background-color: #334155; } /* Slate 700 */
        .bg-accent { background-color: #0ea5e9; } /* Sky 500 */
        .bg-accent-light { background-color: #38bdf8; } /* Sky 400 */
        .text-accent { color: #0ea5e9; }
        .border-accent { border-color: #0ea5e9; }
        
        /* Colores de estado */
        .bg-success { background-color: #10b981; } /* Emerald 500 */
        .bg-warning { background-color: #f59e0b; } /* Amber 500 */
        .bg-danger { background-color: #ef4444; } /* Red 500 */
        .bg-info { background-color: #8b5cf6; } /* Violet 500 */
        
        /* Hover effects */
        .hover-accent:hover { background-color: #0284c7; } /* Sky 600 */
        .hover-primary:hover { background-color: #475569; } /* Slate 600 */
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="sidebar bg-primary text-white w-64 flex flex-col">
            <div class="p-4 flex items-center border-b border-primary-light">
                <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <span class="logo-text ml-3 text-xl font-bold">TecNM Campus Monclova</span>
            </div>
            <div class="flex-1 overflow-y-auto py-4">
                <div class="px-4 space-y-2">
                    <a href="panelAdmin.php" class="menu-item flex items-center px-4 py-3 rounded-lg bg-accent text-white">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="menu-text ml-3">Dashboard</span>
                    </a>
                    <a href="tablaMaestro.php" class="menu-item flex items-center px-4 py-3 rounded-lg hover-primary text-white">
                        <i class="fas fa-users"></i>
                        <span class="menu-text ml-3">Usuarios</span>
                    </a>
                    <a href="tablaMaestro.php" class="menu-item flex items-center px-4 py-3 rounded-lg hover-primary text-white">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="menu-text ml-3">Maestros</span>
                    </a>
                    <a href="tablaMaestro.php" class="menu-item flex items-center px-4 py-3 rounded-lg hover-primary text-white">
                        <i class="fas fa-user-tag"></i>
                        <span class="menu-text ml-3">Tipos de Usuario</span>
                    </a>
                    <a href="tablaMaestro.php" class="menu-item flex items-center px-4 py-3 rounded-lg hover-primary text-white">
                        <i class="fas fa-tasks"></i>
                        <span class="menu-text ml-3">Actividades</span>
                    </a>
                    <a href="tablaMaestro.php" class="menu-item flex items-center px-4 py-3 rounded-lg hover-primary text-white">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="menu-text ml-3">Periodos</span>
                    </a>
                    <a href="tablaMaestro.php" class="menu-item flex items-center px-4 py-3 rounded-lg hover-primary text-white">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="menu-text ml-3">Registros</span>
                    </a>
                </div>
            </div>
            <div class="p-4 border-t border-primary-light flex items-center">
                <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
                <div class="ml-3">
                    <div class="text-sm font-medium">Admin</div>
                    <div class="text-xs text-gray-300">Administrador</div>
                </div>
                <button id="toggle-sidebar" class="ml-auto text-gray-300 hover:text-white">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
        </div>
        <!-- Main Content -->
        <div class="content flex-1 overflow-y-auto">
            <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b border-gray-200">
                <div class="flex items-center">
                    <button onclick="toggleMobileSidebar()" class="md:hidden mr-4 text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Panel Administrador</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="logout()" class="flex items-center text-gray-600 hover:text-gray-800">
                        <span class="mr-2">Cerrar sesión</span>
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </header>
            <!-- Page Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Bienvenido al Sistema de Gestión Escolar</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-6 flex items-center border border-blue-200">
                            <div class="w-12 h-12 rounded-full bg-accent flex items-center justify-center mr-4">
                                <i class="fas fa-chalkboard-teacher text-white"></i>
                            </div>
                            <div>
                                <div class="text-gray-600">Maestros</div>
                                <div class="text-2xl font-bold text-gray-800" id="totalMaestros">--</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg p-6 flex items-center border border-emerald-200">
                            <div class="w-12 h-12 rounded-full bg-success flex items-center justify-center mr-4">
                                <i class="fas fa-user-graduate text-white"></i>
                            </div>
                            <div>
                                <div class="text-gray-600">Alumnos</div>
                                <div class="text-2xl font-bold text-gray-800" id="totalAlumnos">--</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-violet-50 to-violet-100 rounded-lg p-6 flex items-center border border-violet-200">
                            <div class="w-12 h-12 rounded-full bg-info flex items-center justify-center mr-4">
                                <i class="fas fa-tasks text-white"></i>
                            </div>
                            <div>
                                <div class="text-gray-600">Actividades</div>
                                <div class="text-2xl font-bold text-gray-800" id="totalActividades">--</div>
                            </div>
                        </div>
                        <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-lg p-6 flex items-center border border-amber-200">
                            <div class="w-12 h-12 rounded-full bg-warning flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-alt text-white"></i>
                            </div>
                            <div>
                                <div class="text-gray-600">Periodos</div>
                                <div class="text-2xl font-bold text-gray-800">3</div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Accesos rápidos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="FormularioMaestros.php" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center transition-colors">
                                <i class="fas fa-plus-circle text-accent mr-3"></i>
                                <span>Agregar nuevo maestro</span>
                            </a>
                            <a href="formularioAlumnos.php" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center transition-colors">
                                <i class="fas fa-user-plus text-success mr-3"></i>
                                <span>Registrar alumno</span>
                            </a>
                            <a href="FormularioActividad.php" class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center transition-colors">
                                <i class="fas fa-clipboard-list text-info mr-3"></i>
                                <span>Programar actividad</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Área de contenido de Inscripciones -->
                <div class="flex-grow mt-6">
                    <!-- Encabezado de la página de Inscripciones -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-running mr-3 text-accent"></i>
                                    Inscripciones a Actividades Físicas
                                </h2>
                                <p class="text-gray-600 mt-1">Consulta y gestiona las inscripciones de los alumnos</p>
                            </div>
                            <a href="NuevaActividadFisica.php" class="mt-4 md:mt-0 bg-accent hover-accent text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                                <i class="fas fa-plus mr-2"></i> Nueva Actividad
                            </a>
                        </div>
                    </div>
                    <!-- Filtros y Búsqueda -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="activity" class="block text-sm font-medium text-gray-700 mb-1">Actividad</label>
                                <select id="activity" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                                    <option value="">Selecciona una actividad</option>
                                </select>
                            </div>
                            <div>
                                <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Semestre</label>
                                <select id="grade" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                                    <option value="all">Todos los semestres</option>
                                </select>
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar alumno</label>
                                <div class="relative">
                                    <input type="text" id="search" placeholder="Nombre o matrícula" class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button id="resetFilters" class="text-accent hover:text-blue-700 text-sm font-medium flex items-center">
                                <i class="fas fa-redo mr-1"></i> Reiniciar filtros
                            </button>
                        </div>
                    </div>
                    <!-- Tarjetas de Actividad (dinámicas) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6" id="actividadesContainer">
                        <!-- Las tarjetas de actividades se llenan con JS -->
                    </div>
                    <!-- Lista de Alumnos -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-800 flex items-center">
                                <i class="fas fa-users mr-2 text-accent"></i>
                                <span id="tituloActividad">Lista de Alumnos Inscritos</span>
                            </h3>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600" id="totalAlumnosFutbol">Total: --</span>
                                <button class="text-accent hover:text-blue-700">
                                    <i class="fas fa-file-export"></i>
                                </button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matrícula</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semestre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carrera</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaAlumnos" class="bg-white divide-y divide-gray-200 custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                                    <!-- Las filas de alumnos se insertarán aquí -->
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Anterior</button>
                                <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Siguiente</button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Mostrando <span class="font-medium">1</span> a <span class="font-medium">10</span> de <span class="font-medium" id="totalAlumnosFooter">--</span> resultados
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Anterior</span>
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                        <button aria-current="page" class="z-10 bg-blue-50 border-accent text-accent relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</button>
                                        <button class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">2</button>
                                        <button class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">3</button>
                                        <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Siguiente</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Fin área inscripciones -->
            </div>
        </div>
    </div>
    <script>
        // Sidebar toggle
        const toggleSidebarBtn = document.getElementById('toggle-sidebar');
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        if (toggleSidebarBtn) {
            toggleSidebarBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
                toggleSidebarBtn.innerHTML = sidebar.classList.contains('collapsed') ?
                    '<i class="fas fa-chevron-right"></i>' : '<i class="fas fa-chevron-left"></i>';
            });
        }
        function toggleMobileSidebar() {
            sidebar.classList.toggle('open');
        }
        document.addEventListener('click', (e) => {
            const mobileHamburgerButton = document.querySelector('button[onclick="toggleMobileSidebar()"]');
            if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && mobileHamburgerButton && !mobileHamburgerButton.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });

        // Variables globales para almacenar datos
        let actividadesData = [];
        let alumnosData = [];

        // Cargar maestros dinámicamente
        async function cargarMaestros() {
            try {
                const response = await fetch('http://localhost:5111/api/admin/maestros?pageSize=100');
                const data = await response.json();
                const maestros = data.Data || data.data || data;
                
                // Actualizar estadísticas
                document.getElementById('totalMaestros').textContent = maestros.length;
            } catch (error) {
                console.error('Error al cargar maestros:', error);
                document.getElementById('totalMaestros').textContent = '0';
            }
        }

        // Cargar alumnos dinámicamente
        async function cargarAlumnos() {
            try {
                const response = await fetch('http://localhost:5111/api/admin/alumnos?pageSize=100');
                const data = await response.json();
                alumnosData = data.Data || data.data || data;
                
                // Actualizar estadísticas
                document.getElementById('totalAlumnos').textContent = alumnosData.length;
            } catch (error) {
                console.error('Error al cargar alumnos:', error);
            }
        }

        // Cargar actividades y contar inscritos
        async function cargarActividades() {
            try {
                const response = await fetch('http://localhost:5111/api/actividad');
                const actividades = await response.json();
                
                // Para cada actividad, obtener el número de inscritos
                const actividadesConInscritos = await Promise.all(
                    actividades.map(async (actividad) => {
                        try {
                            const registrosResponse = await fetch(`http://localhost:5111/api/registro/por-actividad/${actividad.id}`);
                            const registros = await registrosResponse.json();
                            return {
                                ...actividad,
                                inscritos: registros.length
                            };
                        } catch (error) {
                            console.error(`Error obteniendo inscritos para actividad ${actividad.id}:`, error);
                            return {
                                ...actividad,
                                inscritos: 0
                            };
                        }
                    })
                );

                actividadesData = actividadesConInscritos;
                
                // Llenar dropdown
                const select = document.getElementById('activity');
                select.innerHTML = '<option value="">Selecciona una actividad</option>' +
                    actividadesConInscritos.map(a => `<option value="${a.id}">${a.nombre}</option>`).join('');
                
                // Llenar tarjetas
                const container = document.getElementById('actividadesContainer');
                container.innerHTML = actividadesConInscritos.map(actividad => `
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 border border-gray-200">
                        <div class="bg-accent text-white p-4">
                            <h3 class="font-bold text-lg flex items-center">
                                <i class="fas ${obtenerIcono(actividad.nombre)} mr-2"></i> 
                                ${actividad.nombre}
                            </h3>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-gray-600 text-sm">
                                    Inscritos: <span class="font-bold">${actividad.inscritos}</span>
                                </span>
                                <span class="${obtenerClaseEstado(actividad.estatus)}">
                                    ${actividad.estatus ? 'Activo' : 'Inactivo'}
                                </span>
                            </div>
                            <button onclick="mostrarInscritos(${actividad.id}, '${actividad.nombre}')" 
                                class="w-full bg-blue-50 hover:bg-blue-100 text-accent py-2 rounded-lg font-medium transition-colors">
                                Ver inscritos
                            </button>
                        </div>
                    </div>
                `).join('');
                
                // Actualizar estadísticas
                document.getElementById('totalActividades').textContent = actividadesConInscritos.length;
            } catch (error) {
                console.error('Error cargando actividades:', error);
            }
        }

        // Mostrar alumnos inscritos en una actividad específica
        async function mostrarInscritos(idActividad, nombreActividad) {
            try {
                const registrosResponse = await fetch(`http://localhost:5111/api/registro/por-actividad/${idActividad}`);
                const registros = await registrosResponse.json();
                
                if (registros.length === 0) {
                    document.getElementById('tablaAlumnos').innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No hay alumnos inscritos en esta actividad
                            </td>
                        </tr>
                    `;
                    document.getElementById('totalAlumnosFutbol').textContent = 'Total: 0';
                    document.getElementById('totalAlumnosFooter').textContent = '0';
                    document.getElementById('tituloActividad').textContent = `Lista de Alumnos Inscritos en ${nombreActividad}`;
                    return;
                }

                const alumnosInscritos = await Promise.all(
                    registros.map(async (registro) => {
                        try {
                            const alumnoResponse = await fetch(`http://localhost:5111/api/usuarios/${registro.idUsuario}`);
                            if (alumnoResponse.ok) {
                                return await alumnoResponse.json();
                            } else {
                                return {
                                    matricula: 'N/A',
                                    nombre: 'Usuario no encontrado',
                                    semestre: 'N/A',
                                    especialidad: 'N/A'
                                };
                            }
                        } catch (error) {
                            console.error(`Error obteniendo datos del alumno ${registro.idUsuario}:`, error);
                            return {
                                matricula: 'N/A',
                                nombre: 'Error al cargar',
                                semestre: 'N/A',
                                especialidad: 'N/A'
                            };
                        }
                    })
                );

                actualizarTablaAlumnos(alumnosInscritos, nombreActividad);
            } catch (error) {
                console.error('Error mostrando inscritos:', error);
            }
        }

        // Actualizar la tabla de alumnos
        function actualizarTablaAlumnos(alumnos, nombreActividad = '') {
            const tabla = document.getElementById('tablaAlumnos');
            tabla.innerHTML = alumnos.map(alumno => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">${alumno.matricula}</td>
                    <td class="px-6 py-4">${alumno.nombre}</td>
                    <td class="px-6 py-4">${alumno.semestre}</td>
                    <td class="px-6 py-4">${alumno.especialidad || alumno.carrera || ''}</td>
                </tr>
            `).join('');
            
            document.getElementById('totalAlumnosFutbol').textContent = `Total: ${alumnos.length}`;
            document.getElementById('totalAlumnosFooter').textContent = alumnos.length;
            
            if (nombreActividad) {
                document.getElementById('tituloActividad').textContent = `Lista de Alumnos Inscritos en ${nombreActividad}`;
            }
        }

        // Event listener para el dropdown de actividades
        document.getElementById('activity').addEventListener('change', function() {
            const idActividad = this.value;
            if (idActividad) {
                const actividad = actividadesData.find(a => a.id == idActividad);
                mostrarInscritos(idActividad, actividad ? actividad.nombre : 'Actividad');
            } else {
                document.getElementById('tablaAlumnos').innerHTML = '';
                document.getElementById('totalAlumnosFutbol').textContent = 'Total: --';
                document.getElementById('totalAlumnosFooter').textContent = '--';
                document.getElementById('tituloActividad').textContent = 'Lista de Alumnos Inscritos';
            }
        });

        // Funciones auxiliares
        function obtenerIcono(nombreActividad) {
            const iconos = {
                'Fútbol': 'fa-futbol',
                'Voleibol': 'fa-volleyball-ball',
                'Básquetbol': 'fa-basketball-ball',
                'Atletismo': 'fa-running',
                'Natación': 'fa-swimmer'
            };
            return iconos[nombreActividad] || 'fa-dumbbell';
        }

        function obtenerClaseEstado(estatus) {
            return estatus ? 
                'bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full' : 
                'bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full';
        }

        // Función para logout (NUEVA)
        function logout() {
            if (confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                // Limpiar todos los datos de sesión
                sessionStorage.removeItem('userData');
                localStorage.removeItem('userData');
                
                // Opcional: llamar a API de logout
                try {
                    fetch('http://localhost:5111/api/login/logout', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });
                } catch (error) {
                    console.log('Error en logout API:', error);
                }
                
                // Redirigir al login
                window.location.href = 'login.html';
            }
        }

        // Inicializar al cargar la página
        document.addEventListener('DOMContentLoaded', () => {
            cargarAlumnos();
            cargarMaestros();
            cargarActividades();
        });
    </script>
</body>
</html>
