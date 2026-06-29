<script setup>
import { ref } from 'vue'

// Reactive state
const selectedRole = ref('cashier') // Default dropdown value
const email = ref('')
const password = ref('')
const rememberMe = ref(false)

// Handle login submissions
const handleLogin = () => {
  if (!email.value || !password.value) {
    alert('Please fill out all fields.')
    return
  }
  
  // Custom action per selected dropdown role
  alert(`Logging in as ${selectedRole.value.toUpperCase()} with email: ${email.value}`)
}
</script>

<template>
  <div class="flex items-center justify-center min-h-[80vh] px-4">
    <!-- Main Frosted Glass Login Panel Card -->
    <div class="w-full max-w-md bg-[#16161a]/80 border border-white/5 rounded-2xl p-8 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.5)]">
      
      <!-- Brand Headers -->
      <div class="text-center mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-[#E2E8F0]">Welcome Back</h2>
        <p class="text-sm text-[#94A3B8] mt-1">Please log in to your account below.</p>
      </div>

      <!-- Login Input Fields Form -->
      <form @submit.prevent="handleLogin" class="space-y-5">
        
        <!-- Role Selection Dropdown Menu -->
        <div>
          <label class="block text-xs font-medium text-[#94A3B8] uppercase tracking-wider">Account Type</label>
          <div class="relative mt-1.5">
            <select 
              v-model="selectedRole"
              class="w-full px-4 py-3 bg-[#121214]/80 border border-white/10 rounded-lg text-sm text-[#E2E8F0] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/15 appearance-none cursor-pointer"
            >
              <option value="cashier" class="bg-[#16161a] text-[#E2E8F0]">Cashier</option>
              <option value="admin" class="bg-[#16161a] text-[#E2E8F0]"> Admin</option>
              <option value="per" class="bg-[#16161a] text-[#E2E8F0]">user</option> 
            </select>
            <!-- Custom Dropdown Arrow Icon Indicator -->
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#94A3B8]">
             
            </div>
          </div>
        </div>

        <!-- Context Notification for Dropdown Selection -->
        <div class="px-4 py-2.5 bg-[#121214]/40 border border-white/5 rounded-lg text-xs text-center text-[#94A3B8]">
          Accessing <span class="capitalize font-semibold text-white">{{ selectedRole }}</span> clearance systems.
        </div>

        <!-- Email Entry -->
        <div>
          <label class="block text-xs font-medium text-[#94A3B8] uppercase tracking-wider">Email Address</label>
          <input 
            v-model="email"
            type="email" 
            required
            placeholder="name@bakery.com" 
            class="w-full mt-1.5 px-4 py-3 bg-[#121214]/80 border border-white/10 rounded-lg text-sm text-[#E2E8F0] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/15"
          />
        </div>

        <!-- Password Entry -->
        <div>
          <div class="flex justify-between items-center">
            <label class="block text-xs font-medium text-[#94A3B8] uppercase tracking-wider">Password</label>
            <a href="#" class="text-xs text-[#60A5FA] hover:underline">Forgot password?</a>
          </div>
          <input 
            v-model="password"
            type="password" 
            required
            placeholder="password" 
            class="w-full mt-1.5 px-4 py-3 bg-[#121214]/80 border border-white/10 rounded-lg text-sm text-[#E2E8F0] outline-none transition-all duration-200 focus:border-[#3B82F6] focus:ring-2 focus:ring-blue-500/15"
          />
        </div>

        <!-- Utility Settings (Remember me) -->
        <div class="flex items-center justify-between pt-1">
          <label class="flex items-center gap-2 cursor-pointer select-none">
            <input 
              v-model="rememberMe"
              type="checkbox" 
              class="w-4 h-4 rounded border-white/10 bg-[#121214] text-[#2563EB] focus:ring-0 focus:ring-offset-0 transition"
            />
            <span class="text-xs text-[#94A3B8]">Remember device</span>
          </label>
        </div>

        <!-- Dynamic Colors Submission Button -->
        <button 
          type="submit"
          :class="selectedRole === 'user' ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500/20' : 'bg-[#2563EB] hover:bg-[#1D4ED8] focus:ring-blue-500/15'"
          class="w-full mt-2 py-3 text-white font-semibold rounded-lg cursor-pointer transition-all duration-200 text-center shadow-lg text-sm capitalize"
        >
          Sign In as {{ selectedRole }}
        </button>
      </form>

     
      <div v-if="selectedRole === 'user'" class="mt-6 text-center text-xs text-[#94A3B8]">
        New user? <a href="#" class="text-green-400 hover:underline font-medium">Create an account</a>
      </div>
    </div>
  </div>
</template>
