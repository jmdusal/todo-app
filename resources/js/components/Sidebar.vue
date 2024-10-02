<template>
  <aside class="sidebar">
    <nav>
      <ul>
        <li>
          <router-link to="/admin" class="sidebar-link">
            <i class="fas fa-users"></i> Dashboard
          </router-link>
        </li>
        <li>
          <router-link to="/user/index" class="sidebar-link">
            <i class="fas fa-users"></i> Users
          </router-link>
        </li>
        <li>
          <router-link to="/userdetail/index" class="sidebar-link">
            <i class="fas fa-users"></i> User Details
          </router-link>
        </li>
        <li>
          <router-link to="/todo/index" class="sidebar-link">
            <i class="fas fa-file-alt"></i> Todo
          </router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script>
export default {
  methods: {
    navigateTo(page) {
      this.$emit('navigate', page);
    },
  },
};
</script>

<style>
.sidebar {
  width: 160px;
  background-color: #2c3e50; /* Sidebar color */
  color: white;
  padding: 20px;
}

.sidebar nav ul {
  list-style-type: none;
  padding: 0;
}

.sidebar nav ul li {
  padding: 15px;
  font-size: 1.1em;
  display: flex;
  align-items: center;
}

.sidebar-link {
  color: white;
  text-decoration: none;
  display: flex;
  align-items: center;
  width: 100%;
  height: 100%; /* Make sure it fills the height */
  padding: 0; /* Remove padding from the link */
}

.sidebar nav ul li:hover .sidebar-link {
  background-color: #34495e;
  color: #4a90e2;
}

.sidebar nav ul li i {
  margin-right: 10px;
}
</style>
