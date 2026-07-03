<script setup>
import { ref } from 'vue'
</script>

<template>
  
  <div class="min-h-screen font-sans bg-[#121214] text-[#E2E8F0] bg-[radial-gradient(circle_at_top_right,rgba(37,99,235,0.15)_0%,transparent_40%),radial-gradient(circle_at_bottom_left,rgba(37,99,235,0.1)_0%,transparent_40%)] bg-fixed">
    
    
    <nav class="sticky top-0 z-50 flex items-center justify-between px-12 py-4 bg-[darkblue]/75 backdrop-blur-md border-b border-blue-500/25 shadow-[0_10px_30px_-10px_rgba(2,12,27,0.7)]">
      
      
      <div class="flex items-center gap-2 text-2xl font-bold  from-[#60A5FA] to-[#3B82F6] bg-clip-text text-transparent"> 
        <span class="inline-flex items-center justify-center w-8 h-8 text-sm text-white bg-green-600 rounded-md">B</span> 
        Bakery Production
      </div>
      
      <!-- Navigation Links -->
      <div class="flex gap-8">
        <router-link to="/" class="px-4 py-2 text-base font-medium text-[#94A3B8] rounded-lg transition-all duration-300 hover:text-[#E2E8F0] hover:bg-white/5" active-class="!text-[#60A5FA] bg-blue-500/10 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.25)]">Dashboard</router-link>
        <router-link to="/products" class="px-4 py-2 text-base font-medium text-[#94A3B8] rounded-lg transition-all duration-300 hover:text-[#E2E8F0] hover:bg-white/5" active-class="!text-[#60A5FA] bg-blue-500/10 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.25)]">Products</router-link>
        <router-link to="/production" class="px-4 py-2 text-base font-medium text-[#94A3B8] rounded-lg transition-all duration-300 hover:text-[#E2E8F0] hover:bg-white/5" active-class="!text-[#60A5FA] bg-blue-500/10 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.25)]">Production</router-link>
        <router-link to="/sales" class="px-4 py-2 text-base font-medium text-[#94A3B8] rounded-lg transition-all duration-300 hover:text-[#E2E8F0] hover:bg-white/5" active-class="!text-[#60A5FA] bg-blue-500/10 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.25)]">Sales</router-link>
        <router-link to="/login" class="px-4 py-2 text-base font-medium text-[#94A3B8]] rounded-lg transition-all duration-300 hover:text-[#E2E8F0] hover:bg-white/5" active-class="!text-[#60A5FA] bg-blue-500/10 shadow-[inset_0_0_0_1px_rgba(59,130,246,0.25)]">Login</router-link>
      </div>
    </nav>
    
    
    <main class="max-w-300 mx-auto my-8 px-4">
      <router-view />
    </main>

  </div>
</template>
