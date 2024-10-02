<template>
  <header class="header">
    <h1>Dashboard</h1>
    <button @click="logout" class="logout-button">Logout</button>
  </header>
</template>

<script>
export default {
  methods: {
    logout() {
      localStorage.removeItem('user');
      this.$router.push({ name: 'Login' });
    },
  },
};
</script>

<style>
.header {
  background-color: #2c3e50; /* Sidebar color */
  color: white;
  padding: 10px 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.logout-button {
  background: linear-gradient(135deg, #f85032 0%, #e73827 100%);
  color: white;
  border: none;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 5px;
  font-weight: bold;
  transition: background 0.3s ease;
}

.logout-button:hover {
  background: linear-gradient(135deg, #e73827 0%, #d32f2f 100%);
}
</style>
