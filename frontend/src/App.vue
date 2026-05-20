<script setup>
import { ref } from 'vue'


</script>

<template>
<div class="app">
  <nav class="navbar">
        <div class="brand"> 
          <span class="icon">✨</span> Bakery Production
        </div>
        <div class="nav-links">
          <router-link to="/">Dashboard</router-link>
          <router-link to="/products">Products</router-link>
          <router-link to="/sales">Sales</router-link>
          <router-link to="/login">Login</router-link>
        </div>
  </nav>
  <!-- Page content renders here -->
  <main class="content">
    <router-view />
  </main>

</div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Outfit', sans-serif;
  background-color: #121214;
  color: #E2E8F0;
  min-height: 100vh;
  background-image: radial-gradient(circle at top right, rgba(37, 99, 235, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at bottom left, rgba(37, 99, 235, 0.1) 0%, transparent 40%);
  background-attachment: fixed;
}

.navbar {
  background: rgba(18, 18, 20, 0.6);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-bottom: 1px solid rgba(59, 130, 246, 0.15);
  color: white;
  padding: 1rem 3rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
}

.brand {
  font-size: 1.4rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(135deg, #60A5FA, #3B82F6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.nav-links {
  display: flex;
  gap: 2rem;
}

.nav-links a {
  color: #94A3B8;
  text-decoration: none;
  font-size: 1rem;
  font-weight: 500;
  transition: all 0.3s ease;
  padding: 0.5rem 1rem;
  border-radius: 8px;
}

.nav-links a:hover {
  color: #E2E8F0;
  background: rgba(255, 255, 255, 0.05);
}

.nav-links a.router-link-active {
  color: #60A5FA;
  background: rgba(59, 130, 246, 0.1);
  box-shadow: inset 0 0 0 1px rgba(59, 130, 246, 0.2);
}

.content {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 0 1rem;
}
</style>
