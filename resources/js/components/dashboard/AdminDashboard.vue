<template>
  <div id="admin-dashboard">
      <header class="header">
        <h1>Dashboard</h1>
        <button @click="logout" class="logout-button">Logout</button>
    </header>

    <div class="dashboard-container">

    <Sidebar @navigate="navigateTo" />
      <main class="main-content">
        <h2>Welcome, Admin!</h2>
      </main>
    </div>
  </div>
</template>

<script>
import Sidebar from '../Sidebar.vue';
// import Header from '../Header.vue';

export default {
    components: {
        Sidebar,
    },
  data() {
    return {
      currentPage: 'users',
    };
  },
  methods: {
    navigateTo(page) {
      this.currentPage = page;
    },
    logout() {
      localStorage.removeItem('user');
      this.$router.push({ name: 'Login' });
    },
  },
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

body, html {
  margin: 0;
  padding: 0;
  font-family: 'Roboto', sans-serif;
  background-color: #f5f5f5;
}

#admin-dashboard {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.header {
  background-color: #2c3e50;
  color: white;
  padding: 1px 25px;
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

.dashboard-container {
  display: flex;
  flex: 1;
}
.main-content {
  flex: 1;
  padding: 40px;
  background-color: #ecf0f1;
}

h2 {
  font-size: 2em;
  font-weight: 500;
}

.cards-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
}

.card {
  background-color: white;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.card:hover {
  transform: translateY(-10px);
}

.card h3 {
  font-size: 1.5em;
  margin-bottom: 10px;
  color: #2c3e50;
}

.card p {
  font-size: 1em;
  color: #7f8c8d;
}



</style>
