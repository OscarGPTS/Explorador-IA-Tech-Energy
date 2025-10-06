@extends('layouts.app')

@push('styles')
<style>
/* Estilos para el acordeón */
.accordion-header {
    transition: all 0.3s ease;
}

.accordion-header:hover {
    background-color: #f9fafb;
}

.accordion-content {
    transition: all 0.3s ease;
    max-height: 0;
    overflow: hidden;
}

.accordion-content.show {
    max-height: 2000px;
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Animación suave para la flecha */
.accordion-arrow {
    transition: transform 0.3s ease;
}
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🛠️ Gestión de Soporte Técnico</h1>
                <p class="text-gray-600">Administra categorías y casos de soporte técnico</p>
            </div>
            <div class="space-x-2">
                <button onclick="showCategoryModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Nueva Categoría
                </button>
                <button onclick="showProblemModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>Nuevo Caso
                </button>
            </div>
        </div>
    </div>

    <!-- Controles del acordeón -->
    <div class="mb-4 flex justify-end space-x-2">
        <button onclick="expandAll()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm inline-flex items-center">
            <i class="fas fa-expand-alt mr-2"></i>Expandir Todo
        </button>
        <button onclick="collapseAll()" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm inline-flex items-center">
            <i class="fas fa-compress-alt mr-2"></i>Contraer Todo
        </button>
    </div>

    <!-- Lista de Categorías y Problemas con Acordeón -->
    <div class="space-y-4">
        @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <!-- Header de Categoría (Clickeable para expandir/contraer) -->
                <div class="accordion-header bg-gray-50 px-6 py-4 border-b border-gray-200 cursor-pointer hover:bg-gray-100 transition-colors" 
                     onclick="toggleCategory({{ $category->id }})">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl">{{ $category->icon }}</span>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $category->display_name }}</h3>
                                <p class="text-sm text-gray-600">{{ $category->description }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'Activa' : 'Inactiva' }}
                            </span>
                            <span class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                {{ $category->allProblems->count() }} casos
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Indicador de acordeón -->
                            <i id="arrow-{{ $category->id }}" class="fas fa-chevron-down text-gray-400 accordion-arrow"></i>
                            
                            <!-- Botones de acción (con stop propagation) -->
                            <button onclick="event.stopPropagation(); editCategory({{ $category->id }})" 
                                    class="text-blue-600 hover:text-blue-800 p-1" title="Editar categoría">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="event.stopPropagation(); toggleActive('category', {{ $category->id }}, {{ $category->is_active ? 'false' : 'true' }})" 
                                    class="text-gray-600 hover:text-gray-800 p-1" 
                                    title="{{ $category->is_active ? 'Desactivar' : 'Activar' }}">
                                <i class="fas fa-{{ $category->is_active ? 'eye-slash' : 'eye' }}"></i>
                            </button>
                            @if($category->allProblems->count() == 0)
                                <button onclick="event.stopPropagation(); deleteCategory({{ $category->id }})" 
                                        class="text-red-600 hover:text-red-800 p-1" title="Eliminar categoría">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
                            <button onclick="event.stopPropagation(); showProblemModal({{ $category->id }})" 
                                    class="text-green-600 hover:text-green-800 p-1" title="Agregar caso">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contenido desplegable de la categoría -->
                <div id="content-{{ $category->id }}" class="hidden">
                    <div class="divide-y divide-gray-200">
                        @forelse($category->allProblems as $problem)
                            <div class="px-6 py-4 hover:bg-gray-50">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="font-medium text-gray-900">{{ $problem->title }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full bg-{{ $problem->priority === 'high' ? 'red' : ($problem->priority === 'medium' ? 'yellow' : 'gray') }}-100 text-{{ $problem->priority === 'high' ? 'red' : ($problem->priority === 'medium' ? 'yellow' : 'gray') }}-800">
                                                {{ ucfirst($problem->priority) }}
                                            </span>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $problem->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $problem->is_active ? 'Activo' : 'Inactivo' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-1">{{ $problem->description }}</p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span><strong>Clave:</strong> {{ $problem->problem_key }}</span>
                                            @if($problem->estimated_time)
                                                <span><strong>Tiempo:</strong> {{ $problem->estimated_time }}</span>
                                            @endif
                                            <span><strong>Orden:</strong> {{ $problem->sort_order }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="editProblem({{ $problem->id }})" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleActive('problem', {{ $problem->id }}, {{ $problem->is_active ? 'false' : 'true' }})" 
                                                class="text-gray-600 hover:text-gray-800">
                                            <i class="fas fa-{{ $problem->is_active ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                        <button onclick="deleteProblem({{ $problem->id }})" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>No hay casos de soporte en esta categoría</p>
                                <button onclick="showProblemModal({{ $category->id }})" class="mt-2 text-blue-600 hover:text-blue-800">
                                    Agregar el primer caso
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal para Categorías -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="categoryModalTitle">Nueva Categoría</h3>
            <form id="categoryForm">
                <input type="hidden" id="categoryId" name="id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre de la categoría</label>
                    <input type="text" id="categoryName" name="name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre para mostrar</label>
                    <input type="text" id="categoryDisplayName" name="display_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ícono (emoji)</label>
                    <input type="text" id="categoryIcon" name="icon" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" maxlength="10">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="categoryDescription" name="description" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                    <input type="number" id="categorySortOrder" name="sort_order" value="0" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideCategoryModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Problemas -->
<div id="problemModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4" id="problemModalTitle">Nuevo Caso de Soporte</h3>
            <form id="problemForm">
                <input type="hidden" id="problemId" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                        <select id="problemCategory" name="tech_support_category_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Seleccionar categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Clave del problema</label>
                        <input type="text" id="problemKey" name="problem_key" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <p class="text-xs text-gray-500 mt-1">Debe ser única (ej: computadora_lenta)</p>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título del problema</label>
                    <input type="text" id="problemTitle" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción breve</label>
                    <textarea id="problemDescription" name="description" rows="2" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título de la solución</label>
                    <input type="text" id="solutionTitle" name="solution_title" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contenido de la solución (HTML)</label>
                    <textarea id="solutionContent" name="solution_content" rows="8" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Puedes usar HTML con clases de Tailwind CSS</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad</label>
                        <select id="problemPriority" name="priority" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="low">Baja</option>
                            <option value="medium" selected>Media</option>
                            <option value="high">Alta</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tiempo estimado</label>
                        <input type="text" id="estimatedTime" name="estimated_time" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="ej: 5-10 minutos">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                        <input type="number" id="problemSortOrder" name="sort_order" value="0" min="0" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Palabras clave (una por línea)</label>
                    <textarea id="problemKeywords" name="keywords" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="lenta&#10;despacio&#10;demora"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideProblemModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales
let isEditingCategory = false;
let isEditingProblem = false;

// Función para toggle del acordeón con animaciones
function toggleCategory(categoryId) {
    const content = document.getElementById(`content-${categoryId}`);
    const arrow = document.getElementById(`arrow-${categoryId}`);
    
    if (content.classList.contains('hidden')) {
        // Expandir con animación
        content.classList.remove('hidden');
        // Forzar un reflow para aplicar la transición
        content.offsetHeight;
        content.classList.add('show');
        arrow.classList.add('rotate-180');
    } else {
        // Contraer con animación
        content.classList.remove('show');
        arrow.classList.remove('rotate-180');
        // Esperar a que termine la animación antes de ocultar
        setTimeout(() => {
            if (!content.classList.contains('show')) {
                content.classList.add('hidden');
            }
        }, 300);
    }
}

// Función para expandir todas las categorías
function expandAll() {
    const allContents = document.querySelectorAll('[id^="content-"]');
    const allArrows = document.querySelectorAll('[id^="arrow-"]');
    
    allContents.forEach(content => {
        content.classList.remove('hidden');
        content.offsetHeight; // Forzar reflow
        content.classList.add('show');
    });
    allArrows.forEach(arrow => arrow.classList.add('rotate-180'));
}

// Función para contraer todas las categorías
function collapseAll() {
    const allContents = document.querySelectorAll('[id^="content-"]');
    const allArrows = document.querySelectorAll('[id^="arrow-"]');
    
    allContents.forEach(content => {
        content.classList.remove('show');
        setTimeout(() => {
            if (!content.classList.contains('show')) {
                content.classList.add('hidden');
            }
        }, 300);
    });
    allArrows.forEach(arrow => arrow.classList.remove('rotate-180'));
}

// Funciones para modal de categorías
function showCategoryModal(categoryData) {
    categoryData = categoryData || null;
    if (categoryData) {
        isEditingCategory = true;
        document.getElementById('categoryModalTitle').textContent = 'Editar Categoría';
        document.getElementById('categoryId').value = categoryData.id;
        document.getElementById('categoryName').value = categoryData.name;
        document.getElementById('categoryDisplayName').value = categoryData.display_name;
        document.getElementById('categoryIcon').value = categoryData.icon || '';
        document.getElementById('categoryDescription').value = categoryData.description || '';
        document.getElementById('categorySortOrder').value = categoryData.sort_order;
    } else {
        isEditingCategory = false;
        document.getElementById('categoryModalTitle').textContent = 'Nueva Categoría';
        document.getElementById('categoryForm').reset();
    }
    document.getElementById('categoryModal').classList.remove('hidden');
}

function hideCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
    document.getElementById('categoryForm').reset();
    isEditingCategory = false;
}

// Funciones para modal de problemas
function showProblemModal(categoryId, problemData) {
    categoryId = categoryId || null;
    problemData = problemData || null;
    if (problemData) {
        isEditingProblem = true;
        document.getElementById('problemModalTitle').textContent = 'Editar Caso';
        document.getElementById('problemId').value = problemData.id;
        document.getElementById('problemCategory').value = problemData.tech_support_category_id;
        document.getElementById('problemKey').value = problemData.problem_key;
        document.getElementById('problemTitle').value = problemData.title;
        document.getElementById('problemDescription').value = problemData.description || '';
        document.getElementById('solutionTitle').value = problemData.solution_title;
        document.getElementById('solutionContent').value = problemData.solution_content;
        document.getElementById('problemPriority').value = problemData.priority;
        document.getElementById('estimatedTime').value = problemData.estimated_time || '';
        document.getElementById('problemSortOrder').value = problemData.sort_order;
        document.getElementById('problemKeywords').value = problemData.keywords ? problemData.keywords.join('\n') : '';
    } else {
        isEditingProblem = false;
        document.getElementById('problemModalTitle').textContent = 'Nuevo Caso de Soporte';
        document.getElementById('problemForm').reset();
        if (categoryId) {
            document.getElementById('problemCategory').value = categoryId;
        }
    }
    document.getElementById('problemModal').classList.remove('hidden');
}

function hideProblemModal() {
    document.getElementById('problemModal').classList.add('hidden');
    document.getElementById('problemForm').reset();
    isEditingProblem = false;
}

// Manejar envío de formularios
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    const url = isEditingCategory 
        ? `{{ url('admin/tech-support/categories') }}/${data.id}`
        : '{{ route("admin.tech-support.categories.store") }}';
    
    const method = isEditingCategory ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideCategoryModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar la categoría');
    });
});

document.getElementById('problemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Procesar keywords
    if (data.keywords) {
        data.keywords = data.keywords.split('\n').filter(k => k.trim()).map(k => k.trim());
    }
    
    const url = isEditingProblem 
        ? `{{ url('admin/tech-support/problems') }}/${data.id}`
        : '{{ route("admin.tech-support.problems.store") }}';
    
    const method = isEditingProblem ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            hideProblemModal();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el caso');
    });
});

// Funciones adicionales
function editCategory(categoryId) {
    const categories = @json($categories);
    const category = categories.find(c => c.id === categoryId);
    if (category) {
        showCategoryModal(category);
    }
}

function editProblem(problemId) {
    const categories = @json($categories);
    let problem = null;
    
    for (const category of categories) {
        problem = category.all_problems.find(p => p.id === problemId);
        if (problem) break;
    }
    
    if (problem) {
        showProblemModal(null, problem);
    }
}

function toggleActive(type, id, isActive) {
    fetch('{{ route("admin.tech-support.toggle-active") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            type: type,
            id: id,
            is_active: isActive
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cambiar el estado');
    });
}

function deleteCategory(categoryId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta categoría?')) {
        fetch(`{{ url('admin/tech-support/categories') }}/${categoryId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la categoría');
        });
    }
}

function deleteProblem(problemId) {
    if (confirm('¿Estás seguro de que quieres eliminar este caso?')) {
        fetch(`{{ url('admin/tech-support/problems') }}/${problemId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el caso');
        });
    }
}
</script>
@endsection