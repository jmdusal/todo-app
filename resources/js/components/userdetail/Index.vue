<template>
  <div id="admin-dashboard">
    <header class="header">
      <h1>User Details Management</h1>
      <button @click="logout" class="logout-button">Logout</button>
    </header>
    <div class="dashboard-container">
      <Sidebar @navigate="navigateTo" />
      <main class="main-content">
        <div v-if="currentPage === 'userDetails'" class="datatable-container">
          <h3>User Details</h3>
          <button @click="openCreateModal" class="add-button">Add User Detail</button>
          <div class="search-bar">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search User Details..."
            />
          </div>
          <table class="datatable">
            <thead>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>User</th>
                <th>Created Date</th>
                <th>Updated Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="detail in paginatedUserDetails" :key="detail.id">
                <td>{{ detail.firstname }}</td>
                <td>{{ detail.lastname }}</td>
                <td>{{ detail.email }}</td>
                <td>{{ detail.user.username }}</td>
                <td>{{ formatDate(detail.created_at) }}</td>
                <td>{{ formatDate(detail.updated_at) }}</td>
                <td>
                  <button @click="editDetail(detail)" class="edit-button">Edit</button>
                  <button @click="deleteDetail(detail.id)" class="delete-button">Delete</button>
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

        <!-- Create UserDetail Modal -->
        <div v-if="isCreating" class="modal">
          <div class="modal-content">
            <h4>Create User Detail</h4>
            <form @submit.prevent="createDetail">
              <label for="firstname">First Name:</label>
              <input type="text" v-model="newDetailData.firstname" required />
              <label for="lastname">Last Name:</label>
              <input type="text" v-model="newDetailData.lastname" required />
              <label for="email">Email:</label>
              <input type="email" v-model="newDetailData.email" required />
              <label for="password">Password:</label>
              <input type="password" v-model="newDetailData.password" required />
              <label for="user_id">User:</label>
              <select v-model="newDetailData.user_id" required>
                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.username }}</option>
              </select>
              <button type="submit" class="edit-button">Create</button>
              <button type="button" class="delete-button" @click="cancelCreate">Cancel</button>
            </form>
          </div>
        </div>

        <!-- Edit UserDetail Modal -->
        <div v-if="isEditing" class="modal">
          <div class="modal-content">
            <h4>Edit User Detail</h4>
            <form @submit.prevent="updateDetail">
              <label for="firstname">First Name:</label>
              <input type="text" v-model="editDetailData.firstname" required />
              <label for="lastname">Last Name:</label>
              <input type="text" v-model="editDetailData.lastname" required />
              <label for="email">Email:</label>
              <input type="email" v-model="editDetailData.email" required />
              <label for="user_id">User:</label>
              <select v-model="editDetailData.user_id" required>
                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.username }}</option>
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

export default {
  name: 'UserDetailIndex',
  components: {
    Sidebar,
  },
  data() {
    return {
      currentPage: 'userDetails',
      userDetails: [],
      users: [],
      searchQuery: '',
      currentPageIndex: 0,
      userDetailsPerPage: 5,
      isEditing: false,
      isCreating: false,
      editDetailData: {
        id: null,
        firstname: '',
        lastname: '',
        email: '',
        user_id: null
      },
      newDetailData: {
        firstname: '',
        lastname: '',
        email: '',
        password: '',
        user_id: null
      }
    };
  },
  mounted() {
    this.getUserDetails();
    this.getUsers(); // Fetch users for the select dropdown
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "timeOut": "3000"
    };
  },
  computed: {
    filteredUserDetails() {
      return this.userDetails.filter(detail =>
        detail.firstname.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        detail.lastname.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        detail.email.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    paginatedUserDetails() {
      const start = this.currentPageIndex * this.userDetailsPerPage;
      return this.filteredUserDetails.slice(start, start + this.userDetailsPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredUserDetails.length / this.userDetailsPerPage);
    }
  },
  methods: {
    formatDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        });
    },

    async getUserDetails() {
      try {
        let res = await axios.get("/api/userdetail/index");
        this.userDetails = res.data;
      } catch (error) {
        console.error("Error fetching user details:", error);
      }
    },
    async getUsers() {
      try {
        let res = await axios.get("/api/user/index");
        this.users = res.data;
      } catch (error) {
        console.error("Error fetching users:", error);
      }
    },
    openCreateModal() {
      this.newDetailData = { firstname: '', lastname: '', email: '', user_id: null };
      this.isCreating = true;
    },
    async createDetail() {
      try {
        await axios.post(`/api/userdetail/store`, this.newDetailData);
        await this.getUserDetails();
        toastr.success('User detail created successfully!', 'Success');
        this.cancelCreate();
      } catch (error) {
        console.error("Error creating user detail:", error);
        toastr.error('Failed to create user detail.', 'Error');
      }
    },
    cancelCreate() {
      this.isCreating = false;
      this.newDetailData = { firstname: '', lastname: '', email: '', user_id: null };
    },
    editDetail(detail) {
      this.editDetailData = { ...detail };
      this.isEditing = true;
    },
    async updateDetail() {
      try {
        await axios.put(`/api/userdetail/update/${this.editDetailData.id}`, this.editDetailData);
        await this.getUserDetails();
        toastr.success('User detail updated successfully!', 'Success');
        this.cancelEdit();
      } catch (error) {
        console.error("Error updating user detail:", error);
        toastr.error('Failed to update user detail.', 'Error');
      }
    },
    cancelEdit() {
      this.isEditing = false;
      this.editDetailData = { id: null, firstname: '', lastname: '', email: '', user_id: null };
    },
    async deleteDetail(detailId) {
      const confirmDelete = confirm('Are you sure you want to delete this user detail?');
      if (confirmDelete) {
        try {
          await axios.delete(`/api/userdetail/delete/${detailId}`);
          await this.getUserDetails();
          toastr.success('User detail deleted successfully!', 'Success');
        } catch (error) {
          console.error("Error deleting user detail:", error);
          toastr.error('Failed to delete user detail.', 'Error');
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
