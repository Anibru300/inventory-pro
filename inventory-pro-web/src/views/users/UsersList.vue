<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
      <div class="flex items-center gap-4">
        <button 
          @click="$router.push('/menu')"
          class="flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm"
          title="Volver al menú"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </button>
        <div>
          <h1 class="text-3xl font-bold text-slate-800 mb-1">Usuarios</h1>
          <p class="text-slate-500">Gestiona los usuarios de tu equipo</p>
        </div>
      </div>
      <button @click="showAddModal = true" 
        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nuevo Usuario
      </button>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">
      <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1 relative">
          <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Buscar usuarios..."
            class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all text-slate-800" />
        </div>
        <select v-model="filterRole" 
          class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 min-w-[180px]">
          <option value="">Todos los roles</option>
          <option value="admin">Administrador</option>
          <option value="manager">Gerente</option>
          <option value="operator">Operador</option>
          <option value="viewer">Visualizador</option>
        </select>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
      <div v-if="loading" class="p-12 text-center">
        <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto"></div>
        <p class="mt-4 text-slate-500">Cargando usuarios...</p>
      </div>

      <div v-else-if="filteredUsers.length === 0" class="p-12 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
        </div>
        <p class="text-slate-500 text-lg">No se encontraron usuarios</p>
        <button @click="showAddModal = true" class="mt-4 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
          Crear primer usuario
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <table v-else class="w-full">
        <thead>
          <tr class="border-b border-slate-100 bg-slate-50/50">
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Usuario</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Email</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Rol</th>
            <th class="text-left py-4 px-6 text-sm font-semibold text-slate-700">Estado</th>
            <th class="text-right py-4 px-6 text-sm font-semibold text-slate-700">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in filteredUsers" :key="user.id" class="border-b border-slate-50 hover:bg-slate-50/80 transition-colors">
            <td class="py-4 px-6">
              <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                  <span class="text-lg font-bold text-blue-600">{{ user.name.charAt(0).toUpperCase() }}</span>
                </div>
                <div>
                  <p class="font-semibold text-slate-800">{{ user.name }}</p>
                  <p class="text-xs text-slate-500">{{ user.username || user.email }}</p>
                </div>
              </div>
            </td>
            <td class="py-4 px-6 text-slate-600">{{ user.email }}</td>
            <td class="py-4 px-6">
              <span :class="['px-3 py-1 rounded-full text-xs font-medium', 
                user.role === 'admin' ? 'bg-purple-100 text-purple-700' :
                user.role === 'manager' ? 'bg-blue-100 text-blue-700' :
                user.role === 'operator' ? 'bg-emerald-100 text-emerald-700' :
                'bg-slate-100 text-slate-600']">
                {{ roleLabels[user.role] || user.role }}
              </span>
            </td>
            <td class="py-4 px-6">
              <span :class="['px-3 py-1 rounded-full text-xs font-medium', 
                user.status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700']">
                {{ user.status === 'active' ? 'Activo' : 'Inactivo' }}
              </span>
            </td>
            <td class="py-4 px-6 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="editUser(user)" 
                  class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Editar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteUser(user.id)" 
                  class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Eliminar">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || editingUser" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-auto">
        <div class="p-6 border-b border-slate-100">
          <h3 class="text-xl font-bold text-slate-800">{{ editingUser ? 'Editar' : 'Nuevo' }} Usuario</h3>
        </div>
        <form @submit.prevent="saveUser" class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Nombre *</label>
            <input v-model="userForm.name" type="text" required 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2 text-slate-700">Email *</label>
            <input v-model="userForm.email" type="email" required 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Rol *</label>
              <select v-model="userForm.role" required 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <option value="admin">Administrador</option>
                <option value="manager">Gerente</option>
                <option value="operator">Operador</option>
                <option value="viewer">Visualizador</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-medium mb-2 text-slate-700">Estado</label>
              <select v-model="userForm.status" 
                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <option value="active">Activo</option>
                <option value="inactive">Inactivo</option>
              </select>
            </div>
          </div>
          <div v-if="!editingUser">
            <label class="block text-sm font-medium mb-2 text-slate-700">Contraseña *</label>
            <input v-model="userForm.password" type="password" :required="!editingUser" 
              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" />
          </div>
          <div class="flex gap-3 pt-4">
            <button type="button" @click="closeModal" 
              class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-colors">
              Cancelar
            </button>
            <button type="submit" :disabled="saving" 
              class="flex-1 px-4 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 disabled:opacity-50 flex items-center justify-center gap-2">
              <svg v-if="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ saving ? 'Guardando...' : 'Guardar' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const loading = ref(false)
const saving = ref(false)
const searchQuery = ref('')
const filterRole = ref('')
const showAddModal = ref(false)
const editingUser = ref(null)

const userForm = ref({
  name: '',
  email: '',
  role: 'operator',
  status: 'active',
  password: '',
})

const roleLabels = {
  admin: 'Administrador',
  manager: 'Gerente',
  operator: 'Operador',
  viewer: 'Visualizador',
}

// Demo data - replace with API call
const users = ref([
  { id: 1, name: 'Administrador', email: 'admin@inventorypro.com', role: 'admin', status: 'active', username: 'admin' },
  { id: 2, name: 'Juan Pérez', email: 'juan@empresa.com', role: 'manager', status: 'active', username: 'jperez' },
  { id: 3, name: 'María García', email: 'maria@empresa.com', role: 'operator', status: 'active', username: 'mgarcia' },
  { id: 4, name: 'Carlos López', email: 'carlos@empresa.com', role: 'viewer', status: 'inactive', username: 'clopez' },
])

const filteredUsers = computed(() => {
  let result = users.value
  
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(u => 
      u.name.toLowerCase().includes(query) ||
      u.email.toLowerCase().includes(query) ||
      u.username?.toLowerCase().includes(query)
    )
  }
  
  if (filterRole.value) {
    result = result.filter(u => u.role === filterRole.value)
  }
  
  return result
})

function editUser(user) {
  editingUser.value = user
  userForm.value = { 
    name: user.name,
    email: user.email,
    role: user.role,
    status: user.status,
    password: '',
  }
}

function closeModal() {
  showAddModal.value = false
  editingUser.value = null
  userForm.value = {
    name: '',
    email: '',
    role: 'operator',
    status: 'active',
    password: '',
  }
}

async function saveUser() {
  saving.value = true
  // Simulate API call
  await new Promise(r => setTimeout(r, 500))
  
  if (editingUser.value) {
    const index = users.value.findIndex(u => u.id === editingUser.value.id)
    if (index !== -1) {
      users.value[index] = { ...users.value[index], ...userForm.value }
    }
  } else {
    const newId = Math.max(...users.value.map(u => u.id), 0) + 1
    users.value.push({ 
      id: newId, 
      ...userForm.value,
      username: userForm.value.email.split('@')[0],
    })
  }
  
  saving.value = false
  closeModal()
}

async function deleteUser(id) {
  if (!confirm('¿Estás seguro de eliminar este usuario?')) return
  users.value = users.value.filter(u => u.id !== id)
}

onMounted(() => {
  // Load users from API
})
</script>
