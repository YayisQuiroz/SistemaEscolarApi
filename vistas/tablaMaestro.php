<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Sistema Escolar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e293b; /* Slate 800 */
            --primary-light: #334155; /* Slate 700 */
            --accent-color: #0ea5e9; /* Sky 500 */
            --accent-light: #38bdf8; /* Sky 400 */
            --success-color: #10b981; /* Emerald 500 */
            --warning-color: #f59e0b; /* Amber 500 */
            --danger-color: #ef4444; /* Red 500 */
            --info-color: #8b5cf6; /* Violet 500 */
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
        }
        
        .sidebar {
            background: var(--primary-color);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 4px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--accent-color);
            transform: translateX(4px);
        }
        
        .main-content {
            margin-left: 280px;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 80px;
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .stats-card.primary {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-color: var(--accent-color);
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-color: var(--success-color);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: var(--warning-color);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            border-color: var(--info-color);
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        
        .btn-action {
            padding: 6px 12px;
            margin: 2px;
            border-radius: 6px;
            border: none;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .btn-edit {
            background-color: var(--warning-color);
            color: white;
        }
        
        .btn-delete {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-view {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #d97706;
        }
        
        .btn-delete:hover {
            background-color: #dc2626;
        }
        
        .btn-view:hover {
            background-color: #0284c7;
        }
        
        .section {
            display: none;
        }
        
        .section.active {
            display: block;
        }
        
        .loading {
            text-align: center;
            padding: 20px;
            color: #64748b;
        }
        
        .error {
            background-color: #fef2f2;
            color: var(--danger-color);
            padding: 12px;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        .success {
            background-color: #f0fdf4;
            color: var(--success-color);
            padding: 12px;
            border-radius: 8px;
            margin: 10px 0;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge.bg-success {
            background-color: var(--success-color) !important;
        }
        
        .badge.bg-danger {
            background-color: var(--danger-color) !important;
        }
        
        .table th {
            background-color: #f8fafc;
            color: #374151;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-primary:hover {
            background-color: #0284c7;
            border-color: #0284c7;
        }
        
        .text-primary {
            color: var(--accent-color) !important;
        }
        
        .text-success {
            color: var(--success-color) !important;
        }
        
        .text-warning {
            color: var(--warning-color) !important;
        }
        
        .text-info {
            color: var(--info-color) !important;
        }

        .btn-reactivate {
    background-color: var(--success-color);
    color: white;
}

.btn-reactivate:hover:not(:disabled) {
    background-color: #059669;
}

.btn-reactivate:disabled {
    background-color: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
}

    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="p-4">
            <h4 class="text-white mb-4">
                <i class="fas fa-graduation-cap me-2"></i>
                <span class="sidebar-text">Sistema Escolar</span>
            </h4>
            
            <div class="input-group mb-4">
                <input type="text" class="form-control" placeholder="Buscar..." id="searchInput">
                <button class="btn btn-outline-light" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="panelAdmin.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="usuarios">
                    <i class="fas fa-users me-2"></i>
                    <span class="sidebar-text">Usuarios</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#" data-section="maestros">
                    <i class="fas fa-chalkboard-teacher me-2"></i>
                    <span class="sidebar-text">Maestros</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="tipos-usuarios">
                    <i class="fas fa-user-tag me-2"></i>
                    <span class="sidebar-text">Tipos de Usuario</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="actividades">
                    <i class="fas fa-tasks me-2"></i>
                    <span class="sidebar-text">Actividades</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="periodos">
                    <i class="fas fa-calendar-alt me-2"></i>
                    <span class="sidebar-text">Periodos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-section="registros">
                    <i class="fas fa-clipboard-list me-2"></i>
                    <span class="sidebar-text">Registros</span>
                </a>
            </li>
        </ul>
        
        <div class="mt-auto p-4">
            <button class="btn btn-outline-light w-100" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="container-fluid p-4">
            
            <!-- Usuarios Section -->
            <section id="usuarios" class="section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Usuarios</h2>
                    <button class="btn btn-primary" onclick="loadUsuarios()">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Matrícula</th>
                                    <th>Correo</th>
                                    <th>Tipo</th>
                                    <th>Estatus</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usuariosTableBody">
                                <tr>
                                    <td colspan="7" class="loading">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando usuarios...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Maestros Section -->
            <section id="maestros" class="section active">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Gestión de Maestros</h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" onclick="window.location.href='FormularioMaestros.php'">
                            <i class="fas fa-plus me-2"></i>Nuevo Maestro
                        </button>
                        <button class="btn btn-primary" onclick="cargarMaestros()">
                            <i class="fas fa-sync-alt me-2"></i>Actualizar
                        </button>
                    </div>
                </div>
                
                <!-- Estadísticas de Maestros -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card primary">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Total Maestros</div>
                                    <div class="h4 mb-0" id="totalMaestrosCount">--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card success">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-check fa-2x text-success"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Maestros Activos</div>
                                    <div class="h4 mb-0" id="maestrosActivos">--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stats-card info">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-graduation-cap fa-2x text-info"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="small text-muted">Especialidades</div>
                                    <div class="h4 mb-0" id="totalEspecialidades">--</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Matrícula</th>
                                    <th>Correo</th>
                                    <th>Especialidad</th>
                                    <th>Fecha Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="maestrosTableBody">
                                <tr>
                                    <td colspan="7" class="loading">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando maestros...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Tipos de Usuario Section -->
            <section id="tipos-usuarios" class="section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tipos de Usuario</h2>
                    <button class="btn btn-primary" onclick="loadTiposUsuarios()">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estatus</th>
                                    <th>Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody id="tiposUsuariosTableBody">
                                <tr>
                                    <td colspan="6" class="loading">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando tipos de usuario...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Actividades Section -->
            <section id="actividades" class="section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Actividades</h2>
                    <button class="btn btn-primary" onclick="loadActividades()">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Maestro</th>
                                    <th>Estatus</th>
                                    <th>Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody id="actividadesTableBody">
                                <tr>
                                    <td colspan="7" class="loading">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando actividades...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Periodos Section -->
            <section id="periodos" class="section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Periodos</h2>
                    <button class="btn btn-primary" onclick="loadPeriodos()">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estatus</th>
                                    <th>Fecha Creación</th>
                                </tr>
                            </thead>
                            <tbody id="periodosTableBody">
                                <tr>
                                    <td colspan="6" class="loading">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando periodos...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <!-- Registros Section -->
            <section id="registros" class="section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Registros</h2>
                    <button class="btn btn-primary" onclick="loadRegistros()">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descripción</th>
                                    <th>Usuario</th>
                                    <th>Actividad</th>
                                    <th>Periodo</th>
                                    <th>Estatus</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody id="registrosTableBody">
                                <tr>
                                    <td colspan="8" class="loading">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando registros...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      const API_BASE_URL = 'http://localhost:5111/api';

// Variables globales para almacenar datos
let usuarios = [];
let maestros = [];
let actividades = [];
let periodos = [];
let tiposUsuarios = [];
let registros = [];

// Variables de paginación
let currentPage = 1;
let pageSize = 10;
let totalPages = 1;

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    setupEventListeners();
});

function initializeApp() {
    cargarMaestros(); // Cargar maestros por defecto ya que es la sección activa
    addCreateButtons(); // Agregar botones de crear
}

function setupEventListeners() {
    // Navigation
    document.querySelectorAll('.nav-link[data-section]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            showSection(section);
            
            // Update active nav
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Sidebar toggle
    document.getElementById('toggleSidebar').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        filterCurrentSection(searchTerm);
    });
}

function showSection(sectionName) {
    // Hide all sections
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show selected section
    document.getElementById(sectionName).classList.add('active');
    
    // Reset pagination
    currentPage = 1;
    
    // Load data for the section
    switch(sectionName) {
        case 'usuarios':
            loadUsuarios();
            break;
        case 'maestros':
            cargarMaestros();
            break;
        case 'tipos-usuarios':
            loadTiposUsuarios();
            break;
        case 'actividades':
            loadActividades();
            break;
        case 'periodos':
            loadPeriodos();
            break;
        case 'registros':
            loadRegistros();
            break;
    }
}

// API Functions con manejo de paginación
async function apiRequest(endpoint, options = {}) {
    try {
        const response = await fetch(`${API_BASE_URL}${endpoint}`, {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('API Error:', error);
        showError(`Error: ${error.message}`);
        throw error;
    }
}

// Función de paginación avanzada universal
function createAdvancedPagination(section, data) {
    const sectionElement = document.getElementById(section);
    let paginationContainer = sectionElement.querySelector('.pagination-container');
    
    if (!paginationContainer) {
        paginationContainer = document.createElement('div');
        paginationContainer.className = 'pagination-container d-flex justify-content-between align-items-center mt-3';
        sectionElement.querySelector('.table-container').after(paginationContainer);
    }
    
    const currentPage = data.currentPage || 1;
    const totalPages = data.totalPages || 1;
    const totalRecords = data.totalRecords || 0;
    
    // Generar números de página
    const pageNumbers = generatePageNumbers(currentPage, totalPages);
    
    paginationContainer.innerHTML = `
        <div class="pagination-info">
            <small class="text-muted">
                Mostrando ${data.data ? data.data.length : 0} de ${totalRecords} registros (Página ${currentPage} de ${totalPages})
            </small>
        </div>
        <div class="pagination-controls">
            <nav aria-label="Paginación">
                <ul class="pagination pagination-sm mb-0">
                    <!-- Primera página -->
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <button class="page-link" onclick="changePage('${section}', 1)" ${currentPage === 1 ? 'disabled' : ''}>
                            <i class="fas fa-angle-double-left"></i>
                        </button>
                    </li>
                    
                    <!-- Página anterior -->
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <button class="page-link" onclick="changePage('${section}', ${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
                            <i class="fas fa-angle-left"></i>
                        </button>
                    </li>
                    
                    ${pageNumbers.map(pageNum => {
                        if (pageNum === '...') {
                            return '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        return `
                            <li class="page-item ${pageNum === currentPage ? 'active' : ''}">
                                <button class="page-link" onclick="changePage('${section}', ${pageNum})">
                                    ${pageNum}
                                </button>
                            </li>
                        `;
                    }).join('')}
                    
                    <!-- Página siguiente -->
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <button class="page-link" onclick="changePage('${section}', ${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
                            <i class="fas fa-angle-right"></i>
                        </button>
                    </li>
                    
                    <!-- Última página -->
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <button class="page-link" onclick="changePage('${section}', ${totalPages})" ${currentPage === totalPages ? 'disabled' : ''}>
                            <i class="fas fa-angle-double-right"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    `;
}

// Generar números de página con lógica avanzada
function generatePageNumbers(currentPage, totalPages) {
    const pages = [];
    const delta = 2; // Número de páginas a mostrar a cada lado de la actual
    
    if (totalPages <= 7) {
        // Si hay pocas páginas, mostrar todas
        for (let i = 1; i <= totalPages; i++) {
            pages.push(i);
        }
    } else {
        // Lógica compleja para muchas páginas
        pages.push(1);
        
        if (currentPage > delta + 3) {
            pages.push('...');
        }
        
        const start = Math.max(2, currentPage - delta);
        const end = Math.min(totalPages - 1, currentPage + delta);
        
        for (let i = start; i <= end; i++) {
            if (!pages.includes(i)) {
                pages.push(i);
            }
        }
        
        if (currentPage < totalPages - delta - 2) {
            pages.push('...');
        }
        
        if (!pages.includes(totalPages)) {
            pages.push(totalPages);
        }
    }
    
    return pages;
}

// Función para cambiar página universal
function changePage(section, newPage) {
    if (newPage < 1) return;
    
    const searchTerm = document.getElementById('searchInput').value;
    
    switch(section) {
        case 'usuarios':
            loadUsuarios(newPage, searchTerm);
            break;
        case 'maestros':
            cargarMaestros(newPage, searchTerm);
            break;
        case 'tipos-usuarios':
            loadTiposUsuarios(newPage, searchTerm);
            break;
        case 'actividades':
            loadActividades(newPage, searchTerm);
            break;
        case 'periodos':
            loadPeriodos(newPage, searchTerm);
            break;
        case 'registros':
            loadRegistros(newPage, searchTerm);
            break;
    }
}

// Usuarios Functions - USANDO PAGINACIÓN
async function loadUsuarios(page = 1, search = '') {
    try {
        console.log('🟡 Cargando usuarios...');
        
        const params = new URLSearchParams({
            page: page.toString(),
            pageSize: pageSize.toString()
        });
        
        if (search) {
            params.append('search', search);
        }
        
        const data = await apiRequest(`/usuarios?${params}`);
        
        usuarios = data.data || [];
        currentPage = data.currentPage || 1;
        totalPages = data.totalPages || 1;
        
        renderUsuarios(usuarios);
        createAdvancedPagination('usuarios', data);
        
        console.log('✅ Usuarios cargados:', usuarios.length);
        
    } catch (error) {
        console.error('❌ Error cargando usuarios:', error);
        document.getElementById('usuariosTableBody').innerHTML = 
            '<tr><td colspan="7" class="error">❌ Error al cargar usuarios: ' + error.message + '</td></tr>';
    }
}

function renderUsuarios(data) {
    const tbody = document.getElementById('usuariosTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay usuarios registrados</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(usuario => `
        <tr>
            <td>${usuario.id}</td>
            <td>${usuario.nombre}</td>
            <td>${usuario.matricula}</td>
            <td>${usuario.correo}</td>
            <td>${getTipoUsuarioText(usuario.idTipoUsuario)}</td>
            <td>
                <span class="badge ${usuario.estatus ? 'bg-success' : 'bg-danger'}">
                    ${usuario.estatus ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>
                <button class="btn-action btn-reactivate" onclick="reactivarUsuario(${usuario.id})" 
                        ${usuario.estatus ? 'disabled' : ''}>
                    <i class="fas fa-undo"></i>
                </button>
                <button class="btn-action btn-edit" onclick="editUsuario(${usuario.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-delete" onclick="deleteUsuario(${usuario.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function renderMaestros(data) {
    const tbody = document.getElementById('maestrosTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay maestros registrados</td></tr>';
        return;
    }
    
    tbody.innerHTML = data.map(maestro => `
        <tr>
            <td>${maestro.id}</td>
            <td>${maestro.nombre}</td>
            <td>${maestro.matricula}</td>
            <td>${maestro.correo}</td>
            <td>
                <span class="badge bg-info">
                    ${maestro.especialidad || 'Sin especialidad'}
                </span>
            </td>
            <td>${formatDate(maestro.fechaCreacion)}</td>
            <td>
                <button class="btn-action btn-reactivate" onclick="reactivarMaestro(${maestro.id})" 
                        ${maestro.estatus ? 'disabled' : ''}>
                    <i class="fas fa-undo"></i>
                </button>
                <button class="btn-action btn-edit" onclick="editMaestro(${maestro.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn-action btn-delete" onclick="deleteMaestro(${maestro.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}


// Maestros Functions - USANDO ENDPOINT ESPECÍFICO CON PAGINACIÓN
async function cargarMaestros(page = 1, search = '') {
    try {
        console.log('🟡 Cargando maestros...');
        
        const params = new URLSearchParams({
            page: page.toString(),
            pageSize: pageSize.toString()
        });
        
        if (search) {
            params.append('search', search);
        }
        
        const data = await apiRequest(`/usuarios/maestros?${params}`);
        
        maestros = data.data || [];
        currentPage = data.currentPage || 1;
        totalPages = data.totalPages || 1;
        
        renderMaestros(maestros);
        updateMaestrosStats(data);
        createAdvancedPagination('maestros', data);
        
        console.log('✅ Maestros cargados:', maestros.length);
        
    } catch (error) {
        console.error('❌ Error cargando maestros:', error);
        document.getElementById('maestrosTableBody').innerHTML = 
            '<tr><td colspan="7" class="error">❌ Error al cargar maestros: ' + error.message + '</td></tr>';
    }
}


function updateMaestrosStats(data) {
    const totalMaestros = data.totalRecords || 0;
    const maestrosActivos = data.data ? data.data.length : 0;
    
    // Contar especialidades únicas
    const especialidades = new Set(
        (data.data || [])
            .map(m => m.especialidad)
            .filter(e => e && e !== 'General' && e !== 'Sin especialidad')
    );
    
    document.getElementById('totalMaestrosCount').textContent = totalMaestros;
    document.getElementById('maestrosActivos').textContent = maestrosActivos;
    document.getElementById('totalEspecialidades').textContent = especialidades.size;
}

// Funciones para otros endpoints CON PAGINACIÓN SIMULADA
async function loadTiposUsuarios(page = 1, search = '') {
    try {
        const data = await apiRequest('/tiposusuarios');
        tiposUsuarios = data;
        
        // Simular paginación para endpoints sin paginación nativa
        const filteredData = search ? 
            tiposUsuarios.filter(tipo => 
                tipo.nombre.toLowerCase().includes(search.toLowerCase()) ||
                (tipo.descripcion && tipo.descripcion.toLowerCase().includes(search.toLowerCase()))
            ) : tiposUsuarios;
        
        const paginatedData = simulatePagination(filteredData, page, pageSize);
        renderTiposUsuarios(paginatedData.data);
        createAdvancedPagination('tipos-usuarios', paginatedData);
        
    } catch (error) {
        document.getElementById('tiposUsuariosTableBody').innerHTML = 
            '<tr><td colspan="5" class="error">Error al cargar tipos de usuario</td></tr>';
    }
}

function renderTiposUsuarios(data) {
    const tbody = document.getElementById('tiposUsuariosTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay tipos de usuario registrados</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(tipo => `
        <tr>
            <td>${tipo.id}</td>
            <td>${tipo.nombre}</td>
            <td>${tipo.descripcion || 'Sin descripción'}</td>
            <td>
                <span class="badge ${tipo.estatus ? 'bg-success' : 'bg-danger'}">
                    ${tipo.estatus ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>${formatDate(tipo.fechaCreacion)}</td>
        </tr>
    `).join('');
}

async function loadActividades(page = 1, search = '') {
    try {
        const data = await apiRequest('/actividad');
        actividades = data;
        
        // Simular paginación
        const filteredData = search ? 
            actividades.filter(actividad => 
                actividad.nombre.toLowerCase().includes(search.toLowerCase()) ||
                (actividad.descripcion && actividad.descripcion.toLowerCase().includes(search.toLowerCase()))
            ) : actividades;
        
        const paginatedData = simulatePagination(filteredData, page, pageSize);
        renderActividades(paginatedData.data);
        createAdvancedPagination('actividades', paginatedData);
        
    } catch (error) {
        document.getElementById('actividadesTableBody').innerHTML = 
            '<tr><td colspan="6" class="error">Error al cargar actividades</td></tr>';
    }
}

function renderActividades(data) {
    const tbody = document.getElementById('actividadesTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay actividades registradas</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(actividad => `
        <tr>
            <td>${actividad.id}</td>
            <td>${actividad.nombre}</td>
            <td>${actividad.descripcion || 'Sin descripción'}</td>
            <td>${getMaestroName(actividad.idUsuarioMaestro)}</td>
            <td>
                <span class="badge ${actividad.estatus ? 'bg-success' : 'bg-danger'}">
                    ${actividad.estatus ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>${formatDate(actividad.fechaCreacion)}</td>
        </tr>
    `).join('');
}

async function loadPeriodos(page = 1, search = '') {
    try {
        const data = await apiRequest('/periodo');
        periodos = data;
        
        // Simular paginación
        const filteredData = search ? 
            periodos.filter(periodo => 
                periodo.nombre.toLowerCase().includes(search.toLowerCase()) ||
                (periodo.descripcion && periodo.descripcion.toLowerCase().includes(search.toLowerCase()))
            ) : periodos;
        
        const paginatedData = simulatePagination(filteredData, page, pageSize);
        renderPeriodos(paginatedData.data);
        createAdvancedPagination('periodos', paginatedData);
        
    } catch (error) {
        document.getElementById('periodosTableBody').innerHTML = 
            '<tr><td colspan="5" class="error">Error al cargar periodos</td></tr>';
    }
}

function renderPeriodos(data) {
    const tbody = document.getElementById('periodosTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay periodos registrados</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(periodo => `
        <tr>
            <td>${periodo.id}</td>
            <td>${periodo.nombre}</td>
            <td>${periodo.descripcion || 'Sin descripción'}</td>
            <td>
                <span class="badge ${periodo.estatus ? 'bg-success' : 'bg-danger'}">
                    ${periodo.estatus ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>${formatDate(periodo.fechaCreacion)}</td>
        </tr>
    `).join('');
}

async function loadRegistros(page = 1, search = '') {
    try {
        const data = await apiRequest('/registro');
        registros = data;
        
        // Simular paginación
        const filteredData = search ? 
            registros.filter(registro => 
                (registro.descripcion && registro.descripcion.toLowerCase().includes(search.toLowerCase()))
            ) : registros;
        
        const paginatedData = simulatePagination(filteredData, page, pageSize);
        renderRegistros(paginatedData.data);
        createAdvancedPagination('registros', paginatedData);
        
    } catch (error) {
        document.getElementById('registrosTableBody').innerHTML = 
            '<tr><td colspan="7" class="error">Error al cargar registros</td></tr>';
    }
}

function renderRegistros(data) {
    const tbody = document.getElementById('registrosTableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay registros</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(registro => `
        <tr>
            <td>${registro.id}</td>
            <td>${registro.descripcion || 'Sin descripción'}</td>
            <td>${getUserName(registro.idUsuario)}</td>
            <td>${getActividadName(registro.idActividad)}</td>
            <td>${getPeriodoName(registro.idPeriodo)}</td>
            <td>
                <span class="badge ${registro.estatus ? 'bg-success' : 'bg-danger'}">
                    ${registro.estatus ? 'Activo' : 'Inactivo'}
                </span>
            </td>
            <td>${formatDate(registro.fechaCreacion)}</td>
        </tr>
    `).join('');
}

// Función para simular paginación en endpoints sin paginación nativa
function simulatePagination(data, page, pageSize) {
    const totalRecords = data.length;
    const totalPages = Math.ceil(totalRecords / pageSize);
    const startIndex = (page - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    const paginatedData = data.slice(startIndex, endIndex);
    
    return {
        data: paginatedData,
        currentPage: page,
        totalPages: totalPages,
        totalRecords: totalRecords,
        pageSize: pageSize
    };
}

// Funciones de búsqueda actualizadas
function filterCurrentSection(searchTerm) {
    const activeSection = document.querySelector('.section.active').id;
    
    switch(activeSection) {
        case 'usuarios':
            loadUsuarios(1, searchTerm);
            break;
        case 'maestros':
            cargarMaestros(1, searchTerm);
            break;
        case 'tipos-usuarios':
            loadTiposUsuarios(1, searchTerm);
            break;
        case 'actividades':
            loadActividades(1, searchTerm);
            break;
        case 'periodos':
            loadPeriodos(1, searchTerm);
            break;
        case 'registros':
            loadRegistros(1, searchTerm);
            break;
    }
}

// 
// Funciones de edición con modales para usuarios
async function editUsuario(id) {
    try {
        // Obtener datos del usuario
        const usuario = await apiRequest(`/usuarios/${id}`);
        
        // Crear modal de edición
        const modalHtml = `
            <div class="modal fade" id="editUsuarioModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Usuario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editUsuarioForm">
                                <input type="hidden" id="editUsuarioId" value="${usuario.id}">
                                
                                <div class="mb-3">
                                    <label for="editUsuarioNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="editUsuarioNombre" 
                                           value="${usuario.nombre}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editUsuarioMatricula" class="form-label">Matrícula</label>
                                    <input type="text" class="form-control" id="editUsuarioMatricula" 
                                           value="${usuario.matricula}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editUsuarioCorreo" class="form-label">Correo</label>
                                    <input type="email" class="form-control" id="editUsuarioCorreo" 
                                           value="${usuario.correo}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editUsuarioTipo" class="form-label">Tipo de Usuario</label>
                                    <select class="form-control" id="editUsuarioTipo" required>
                                        <option value="1" ${usuario.idTipoUsuario === 1 ? 'selected' : ''}>Administrador</option>
                                        <option value="2" ${usuario.idTipoUsuario === 2 ? 'selected' : ''}>Maestro</option>
                                        <option value="3" ${usuario.idTipoUsuario === 3 ? 'selected' : ''}>Alumno</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editUsuarioPassword" class="form-label">Nueva Contraseña (opcional)</label>
                                    <input type="password" class="form-control" id="editUsuarioPassword" 
                                           placeholder="Dejar vacío para mantener la actual">
                                </div>
                                
                              
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="updateUsuario()">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remover modal existente si existe
        const existingModal = document.getElementById('editUsuarioModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Agregar modal al DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('editUsuarioModal'));
        modal.show();
        
    } catch (error) {
        showError('Error al cargar datos del usuario');
    }
}

async function updateUsuario() {
    try {
        const id = document.getElementById('editUsuarioId').value;
        const nombre = document.getElementById('editUsuarioNombre').value;
        const matricula = document.getElementById('editUsuarioMatricula').value;
        const correo = document.getElementById('editUsuarioCorreo').value;
        const idTipoUsuario = parseInt(document.getElementById('editUsuarioTipo').value);
        const password = document.getElementById('editUsuarioPassword').value;
        
        const updateData = {
            nombre,
            matricula,
            correo,
            idTipoUsuario,
        };
        
        // Solo incluir password si se proporcionó uno nuevo
        if (password.trim()) {
            updateData.password = password;
        }
        
        await apiRequest(`/usuarios/${id}`, {
            method: 'PUT',
            body: JSON.stringify(updateData)
        });
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editUsuarioModal'));
        modal.hide();
        
        showSuccess('Usuario actualizado correctamente');
        loadUsuarios(currentPage); // Recargar datos
        
    } catch (error) {
        showError('Error al actualizar usuario');
    }
}

// Funciones de edición con modales para maestros
async function editMaestro(id) {
    try {
        // Obtener datos del maestro
        const maestro = await apiRequest(`/usuarios/${id}`);
        
        // Crear modal de edición
        const modalHtml = `
            <div class="modal fade" id="editMaestroModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Maestro</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editMaestroForm">
                                <input type="hidden" id="editMaestroId" value="${maestro.id}">
                                
                                <div class="mb-3">
                                    <label for="editMaestroNombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="editMaestroNombre" 
                                           value="${maestro.nombre}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editMaestroMatricula" class="form-label">Matrícula</label>
                                    <input type="text" class="form-control" id="editMaestroMatricula" 
                                           value="${maestro.matricula}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editMaestroCorreo" class="form-label">Correo</label>
                                    <input type="email" class="form-control" id="editMaestroCorreo" 
                                           value="${maestro.correo}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editMaestroEspecialidad" class="form-label">Especialidad</label>
                                    <input type="text" class="form-control" id="editMaestroEspecialidad" 
                                           value="${maestro.especialidad || ''}" 
                                           placeholder="Ej: Matemáticas, Física, etc.">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="editMaestroPassword" class="form-label">Nueva Contraseña (opcional)</label>
                                    <input type="password" class="form-control" id="editMaestroPassword" 
                                           placeholder="Dejar vacío para mantener la actual">
                                </div>
                                
                               
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="updateMaestro()">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Remover modal existente si existe
        const existingModal = document.getElementById('editMaestroModal');
        if (existingModal) {
            existingModal.remove();
        }
        
        // Agregar modal al DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // Mostrar modal
        const modal = new bootstrap.Modal(document.getElementById('editMaestroModal'));
        modal.show();
        
    } catch (error) {
        showError('Error al cargar datos del maestro');
    }
}

async function updateMaestro() {
    try {
        const id = document.getElementById('editMaestroId').value;
        const nombre = document.getElementById('editMaestroNombre').value;
        const matricula = document.getElementById('editMaestroMatricula').value;
        const correo = document.getElementById('editMaestroCorreo').value;
        const especialidad = document.getElementById('editMaestroEspecialidad').value;
        const password = document.getElementById('editMaestroPassword').value;
        
        const updateData = {
            nombre,
            matricula,
            correo,
            especialidad: especialidad || null,
            idTipoUsuario: 2, // Siempre es maestro
        };
        
        // Solo incluir password si se proporcionó uno nuevo
        if (password.trim()) {
            updateData.password = password;
        }
        
        await apiRequest(`/usuarios/${id}`, {
            method: 'PUT',
            body: JSON.stringify(updateData)
        });
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editMaestroModal'));
        modal.hide();
        
        showSuccess('Maestro actualizado correctamente');
        cargarMaestros(currentPage); // Recargar datos
        
    } catch (error) {
        showError('Error al actualizar maestro');
    }
}

// Modal para crear nuevo usuario
function showCreateUsuarioModal() {
    const modalHtml = `
        <div class="modal fade" id="createUsuarioModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Crear Nuevo Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createUsuarioForm">
                            <div class="mb-3">
                                <label for="createUsuarioNombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="createUsuarioNombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="createUsuarioMatricula" class="form-label">Matrícula</label>
                                <input type="text" class="form-control" id="createUsuarioMatricula" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="createUsuarioCorreo" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="createUsuarioCorreo" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="createUsuarioTipo" class="form-label">Tipo de Usuario</label>
                                <select class="form-control" id="createUsuarioTipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Maestro</option>
                                    <option value="3">Alumno</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="createUsuarioPassword" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="createUsuarioPassword" required>
                            </div>
                            
                            <div class="mb-3" id="especialidadContainer" style="display: none;">
                                <label for="createUsuarioEspecialidad" class="form-label">Especialidad</label>
                                <input type="text" class="form-control" id="createUsuarioEspecialidad" 
                                       placeholder="Solo para maestros">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="createUsuario()">Crear Usuario</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal existente si existe
    const existingModal = document.getElementById('createUsuarioModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Agregar modal al DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Agregar evento para mostrar/ocultar especialidad
    document.getElementById('createUsuarioTipo').addEventListener('change', function() {
        const especialidadContainer = document.getElementById('especialidadContainer');
        if (this.value === '2') { // Maestro
            especialidadContainer.style.display = 'block';
        } else {
            especialidadContainer.style.display = 'none';
        }
    });
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('createUsuarioModal'));
    modal.show();
}

async function createUsuario() {
    try {
        const nombre = document.getElementById('createUsuarioNombre').value;
        const matricula = document.getElementById('createUsuarioMatricula').value;
        const correo = document.getElementById('createUsuarioCorreo').value;
        const idTipoUsuario = parseInt(document.getElementById('createUsuarioTipo').value);
        const password = document.getElementById('createUsuarioPassword').value;
        const especialidad = document.getElementById('createUsuarioEspecialidad').value;
        
        const userData = {
            nombre,
            matricula,
            correo,
            password,
            idTipoUsuario,
            estatus: true
        };
        
        // Solo agregar especialidad si es maestro y se proporcionó
        if (idTipoUsuario === 2 && especialidad.trim()) {
            userData.especialidad = especialidad;
        }
        
        await apiRequest('/usuarios', {
            method: 'POST',
            body: JSON.stringify(userData)
        });
        
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('createUsuarioModal'));
        modal.hide();
        
        showSuccess('Usuario creado correctamente');
        
        // Recargar la sección activa
        const activeSection = document.querySelector('.section.active').id;
        if (activeSection === 'usuarios') {
            loadUsuarios(1);
        } else if (activeSection === 'maestros') {
            cargarMaestros(1);
        }
        
    } catch (error) {
        showError('Error al crear usuario');
    }
}

function showCreateMaestroModal() {
    // Usar el mismo modal pero con tipo fijo a maestro
    showCreateUsuarioModal();
    
    // Después de mostrar el modal, establecer tipo como maestro
    setTimeout(() => {
        document.getElementById('createUsuarioTipo').value = '2';
        document.getElementById('createUsuarioTipo').dispatchEvent(new Event('change'));
        document.getElementById('createUsuarioTipo').disabled = true;
    }, 100);
}

// Agregar botones de crear en el HTML
function addCreateButtons() {
    // Botón para crear usuario en la sección usuarios
    const usuariosSection = document.getElementById('usuarios');
    const usuariosHeader = usuariosSection.querySelector('h2');
    if (usuariosHeader && !usuariosHeader.querySelector('.btn-create')) {
        usuariosHeader.innerHTML += `
            <button class="btn btn-success btn-create ms-3" onclick="showCreateUsuarioModal()">
                <i class="fas fa-plus"></i> Nuevo Usuario
            </button>
        `;
    }
    
    // Botón para crear maestro en la sección maestros
    const maestrosSection = document.getElementById('maestros');
    const maestrosHeader = maestrosSection.querySelector('h2');
    if (maestrosHeader && !maestrosHeader.querySelector('.btn-create')) {
        maestrosHeader.innerHTML += `

        `;
    }
}

// Action Functions para usuarios y maestros
async function deleteUsuario(id) {
    if (!confirm('¿Está seguro de que desea desactivar este usuario?')) {
        return;
    }
    
    try {
        await apiRequest(`/usuarios/${id}`, { method: 'DELETE' });
        showSuccess('Usuario desactivado correctamente');
        loadUsuarios(currentPage); // Recargar página actual
    } catch (error) {
        showError('Error al desactivar usuario');
    }
}

async function deleteMaestro(id) {
    if (!confirm('¿Está seguro de que desea desactivar este maestro?')) {
        return;
    }
    
    try {
        await apiRequest(`/usuarios/${id}`, { method: 'DELETE' });
        showSuccess('Maestro desactivado correctamente');
        cargarMaestros(currentPage); // Recargar página actual
    } catch (error) {
        showError('Error al desactivar maestro');
    }
}

// Función para reactivar usuario
async function reactivarUsuario(id) {
    if (!confirm('¿Está seguro de que desea reactivar este usuario?')) {
        return;
    }
    
    try {
        await apiRequest(`/usuarios/${id}/reactivar`, { 
            method: 'PATCH' 
        });
        
        showSuccess('Usuario reactivado correctamente');
        loadUsuarios(currentPage); // Recargar página actual
        
    } catch (error) {
        showError('Error al reactivar usuario: ' + error.message);
    }
}

// Función para reactivar maestro
async function reactivarMaestro(id) {
    if (!confirm('¿Está seguro de que desea reactivar este maestro?')) {
        return;
    }
    
    try {
        await apiRequest(`/usuarios/${id}/reactivar`, { 
            method: 'PATCH' 
        });
        
        showSuccess('Maestro reactivado correctamente');
        cargarMaestros(currentPage); // Recargar página actual
        
    } catch (error) {
        showError('Error al reactivar maestro: ' + error.message);
    }
}


// Funciones auxiliares
function getTipoUsuarioText(idTipo) {
    const tipos = {
        1: 'Administrador',
        2: 'Maestro',
        3: 'Alumno'
    };
    return tipos[idTipo] || 'Desconocido';
}

function getMaestroName(idMaestro) {
    const maestro = maestros.find(m => m.id === idMaestro);
    return maestro ? maestro.nombre : 'Sin asignar';
}

function getUserName(idUsuario) {
    const usuario = usuarios.find(u => u.id === idUsuario);
    return usuario ? usuario.nombre : 'Usuario no encontrado';
}

function getActividadName(idActividad) {
    const actividad = actividades.find(a => a.id === idActividad);
    return actividad ? actividad.nombre : 'Actividad no encontrada';
}

function getPeriodoName(idPeriodo) {
    const periodo = periodos.find(p => p.id === idPeriodo);
    return periodo ? periodo.nombre : 'Periodo no encontrado';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
}

function showError(message) {
    console.error(message);
    // Aquí puedes agregar notificaciones toast o modales
    alert('Error: ' + message);
}

function showSuccess(message) {
    console.log(message);
    // Aquí puedes agregar notificaciones toast o modales
    alert('Éxito: ' + message);
}


    </script>
</body>
</html>
