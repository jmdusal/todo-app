<template>
  <div id="admin-dashboard">
    <header class="header">
      <h1>User Management</h1>
      <button @click="logout" class="logout-button">Logout</button>
    </header>
    <div class="dashboard-container">
      <Sidebar @navigate="navigateTo" />
      <main class="main-content">
        <div v-if="currentPage === 'users'" class="datatable-container">
          <h3>Users</h3>
          <button @click="openCreateModal" class="add-button">Add User</button>
          <div class="search-bar">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search Users..."
            />
          </div>
          <table class="datatable">
            <thead>
              <tr>
                <th>Username</th>
                <th>User Type</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="user in paginatedUsers" :key="user.id">
                <td>{{ user.username }}</td>
                <td>{{ user.usertype }}</td>
                <td>{{ formatDate(user.created_at) }}</td>
                <td>{{ formatDate(user.updated_at) }}</td>

                <td>
                  <button @click="editUser(user)" class="edit-button">Edit</button>
                  <button @click="deleteUser(user.id)" class="delete-button">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="pagination">
            <button @click="prevPage" class="edit-button" :disabled="currentPageIndex === 0">Previous</button>
            <span>Page {{ currentPageIndex + 1 }} of {{ totalPages }}</span>
            <button @click="nextPage" class="edit-button" :disabled="currentPageIndex >= totalPages - 1">Next</button>
          </div>
        </div>

        <!-- Create User Modal -->
        <div v-if="isCreating" class="modal">
          <div class="modal-content">
            <h4>Create User</h4>
            <form @submit.prevent="createUser">
              <label for="username">Username:</label>
              <input type="text" v-model="newUserData.username" required />
              <label for="password">Password:</label>
              <input type="password" v-model="newUserData.password" required />
              <label for="usertype">User Type:</label>
              <select v-model="newUserData.usertype" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
              <button type="submit" class="edit-button">Create</button>
              <button type="button" class="delete-button" @click="cancelCreate">Cancel</button>
            </form>
          </div>
        </div>

        <!-- Edit User Modal -->
        <div v-if="isEditing" class="modal">
          <div class="modal-content">
            <h4>Edit User</h4>
            <form @submit.prevent="updateUser">
              <label for="username">Username:</label>
              <input type="text" v-model="editUserData.username" required />
              <label for="usertype">User Type:</label>
              <select v-model="editUserData.usertype" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
              </select>
              <button type="submit" class="edit-button">Update</button>
              <button type="button" class="delete-button" @click="cancelEdit">Cancel</button>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import axios from "../config/axios.js";
import Sidebar from '../Sidebar.vue';
import toastr from 'toastr';
import './css/UserManagement.css';
import './css/Modal.css';

export default {
  name: 'users',
  components: {
    Sidebar,
  },
  data() {
    return {
      currentPage: 'users',
      users: [],
      isEditing: false,
      isCreating: false,
      searchQuery: '',
      currentPageIndex: 0,
      usersPerPage: 5,
      editUserData: {
        id: null,
        username: '',
        usertype: ''
      },
      newUserData: {
        username: '',
        password: '',
        usertype: ''
      }
    };
  },
  mounted() {
    this.getUsers();
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "timeOut": "3000"
    };
  },
  computed: {
    filteredUsers() {
      return this.users.filter(user =>
        user.username.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    paginatedUsers() {
      const start = this.currentPageIndex * this.usersPerPage;
      return this.filteredUsers.slice(start, start + this.usersPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredUsers.length / this.usersPerPage);
    }
  },
  methods: {
    async getUsers() {
      try {
        let res = await axios.get("/api/user/index");
        this.users = res.data;
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },

    formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        });
    },

    openCreateModal() {
      this.newUserData = { username: '', usertype: '', password: '' };
      this.isCreating = true;
    },
    async createUser() {
      try {
        await axios.post(`/api/user/store`, this.newUserData);
        await this.getUsers();
        toastr.success('User created successfully!', 'Success');
        this.cancelCreate();
      } catch (error) {
        console.error("Error creating user:", error);
        toastr.error('Failed to create user.', 'Error');
      }
    },
    cancelCreate() {
      this.isCreating = false;
      this.newUserData = { username: '', usertype: '', password: '' };
    },
    editUser(user) {
      this.editUserData = { ...user };
      this.isEditing = true;
    },
    async updateUser() {
      try {
        await axios.put(`/api/user/update/${this.editUserData.id}`, this.editUserData);
        await this.getUsers();
        toastr.success('User updated successfully!', 'Success');
        this.cancelEdit();
      } catch (error) {
        toastr.error('Failed to update user.', 'Error');
      }
    },
    cancelEdit() {
      this.isEditing = false;
      this.editUserData = { id: null, username: '', usertype: '' };
    },
    async deleteUser(userId) {
      const confirmDelete = confirm('Are you sure you want to delete this user?');
      if (confirmDelete) {
        try {
          await axios.delete(`/api/user/delete/${userId}`);
          await this.getUsers();
          toastr.success('User deleted successfully!', 'Success');
        } catch (error) {
          console.error("Error deleting user:", error);
          toastr.error('Failed to delete user.', 'Error');
        }
      }
    },
    navigateTo(page) {
      this.currentPage = page;
    },
    logout() {
      localStorage.removeItem('user');
      this.$router.push({ name: 'Login' });
    },
    nextPage() {
      if (this.currentPageIndex < this.totalPages - 1) {
        this.currentPageIndex++;
      }
    },
    prevPage() {
      if (this.currentPageIndex > 0) {
        this.currentPageIndex--;
      }
    },
  },
};
</script>
