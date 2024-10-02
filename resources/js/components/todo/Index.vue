<template>
  <div id="admin-dashboard">
    <header class="header">
      <h1>Todo Management</h1>
      <button @click="logout" class="logout-button">Logout</button>
    </header>
    <div class="dashboard-container">
      <Sidebar @navigate="navigateTo" />
      <main class="main-content">
        <h3>Todo Management</h3>
        <button @click="openCreateModal" class="add-button">Add Todo</button>

        <!-- Search Bar -->
        <div class="search-bar">
          <input
            type="text"
            v-model="searchQuery"
            placeholder="Search Todos..."
          />
        </div>

        <div class="cards-container">
          <div class="todo-item" v-for="todo in filteredPaginatedTodos" :key="todo.id">
            <div class="card" v-if="currentPage === 'todos'">
              <div v-if="loadingTodos">Loading Todos...</div>
              <div class="todo-list" v-else>
                <h4>{{ todo.todo }}</h4>
                <div class="todo-details">
                    <p><strong>Description:</strong> {{ todo.description }}</p>
                    <p><strong>Status:</strong> {{ todo.status }}</p>
                    <p><strong>Assigned to:</strong> {{ getUserName(todo.user_id) }}</p>
                    <p><strong>Created Date</strong>{{ formatDate(todo.created_at) }}</p>
                    <p><strong>Updated Date</strong>{{ formatDate(todo.updated_at) }}</p>

                </div>
                <div class="todo-actions">
                  <button @click="editTodo(todo)" class="edit-button">Edit</button>
                  <button @click="deleteTodo(todo.id)" class="delete-button">Delete</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="pagination">
          <button @click="prevPage" class="edit-button" :disabled="currentPageIndex === 0">Previous</button>
          <span>Page {{ currentPageIndex + 1 }} of {{ totalPages }}</span>
          <button @click="nextPage" class="edit-button" :disabled="currentPageIndex >= totalPages - 1">Next</button>
        </div>

        <!-- Create Todo Modal -->
        <div v-if="isCreating" class="modal">
          <div class="modal-content">
            <h4>Create Todo</h4>
            <form @submit.prevent="createTodo">
              <label for="todo">Todo:</label>
              <input type="text" v-model="newTodoData.todo" required />
              <label for="description">Description:</label>
              <textarea v-model="newTodoData.description" required></textarea>
              <label for="status">Status:</label>
              <select v-model="newTodoData.status" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
              </select>
              <label for="user_id">Assign to User:</label>
              <select v-model="newTodoData.user_id" required>
                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.username }}</option>
              </select>
              <button type="submit" class="edit-button">Create</button>
              <button type="button" class="delete-button" @click="cancelCreate">Cancel</button>
            </form>
          </div>
        </div>

        <!-- Edit Todo Modal -->
        <div v-if="isEditing" class="modal">
          <div class="modal-content">
            <h4>Edit Todo</h4>
            <form @submit.prevent="updateTodo">
              <label for="todo">Todo:</label>
              <input type="text" v-model="editTodoData.todo" required />
              <label for="description">Description:</label>
              <textarea v-model="editTodoData.description" rows="4" cols="50" required></textarea>
              <label for="status">Status:</label>
              <select v-model="editTodoData.status" required>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
              </select>
              <label for="user_id">Assign to User:</label>
              <select v-model="editTodoData.user_id" required>
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
  name: 'todos',
  components: {
    Sidebar,
  },
  data() {
    return {
      currentPage: 'todos',
      todos: [],
      users: [],
      isEditing: false,
      isCreating: false,
      editTodoData: {
        id: null,
        todo: '',
        description: '',
        status: '',
        user_id: null
      },
      newTodoData: {
        todo: '',
        description: '',
        status: '',
        user_id: null
      },
      currentPageIndex: 0,
      todosPerPage: 3,
      searchQuery: '',
    };
  },
  mounted() {
    this.getTodos();
    this.getUsers();
    toastr.options = {
      "closeButton": true,
      "progressBar": true,
      "timeOut": "3000"
    };
  },
  computed: {
    filteredTodos() {
      return this.todos.filter(todo =>
        todo.todo.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        todo.description.toLowerCase().includes(this.searchQuery.toLowerCase())
      )
      .sort((a, b) => b.id - a.id); // desc
    },
    filteredPaginatedTodos() {
      const start = this.currentPageIndex * this.todosPerPage;
      return this.filteredTodos.slice(start, start + this.todosPerPage);
    },
    totalPages() {
      return Math.ceil(this.filteredTodos.length / this.todosPerPage);
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

    async getTodos() {
      try {
        let res = await axios.get("/api/todo/index");
        this.todos = res.data;
      } catch (error) {
        console.error("Error fetching todos:", error);
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
      this.newTodoData = { todo: '', description: '', status: '', user_id: null };
      this.isCreating = true;
    },

    async createTodo() {
      try {
        await axios.post(`/api/todo/store`, this.newTodoData);
        await this.getTodos();
        toastr.success('Todo created successfully!', 'Success');
        this.cancelCreate();
      } catch (error) {
        console.error("Error creating todo:", error);
        toastr.error('Failed to create todo.', 'Error');
      }
    },

    cancelCreate() {
      this.isCreating = false;
      this.newTodoData = { todo: '', description: '', status: '', user_id: null };
    },

    editTodo(todo) {
      this.editTodoData = { ...todo };
      this.isEditing = true;
    },

    async updateTodo() {
      try {
        await axios.put(`/api/todo/update/${this.editTodoData.id}`, this.editTodoData);
        await this.getTodos();
        toastr.success('Todo updated successfully!', 'Success');
        this.cancelEdit();
      } catch (error) {
        console.error("Error updating todo:", error);
        toastr.error('Failed to update todo.', 'Error');
      }
    },

    cancelEdit() {
      this.isEditing = false;
      this.editTodoData = { id: null, todo: '', description: '', status: '', user_id: null };
    },

    async deleteTodo(todoId) {
      const confirmDelete = confirm('Are you sure you want to delete this todo?');
      if (confirmDelete) {
        try {
          await axios.delete(`/api/todo/delete/${todoId}`);
          await this.getTodos();
          toastr.success('Todo deleted successfully!', 'Success');
        } catch (error) {
          console.error("Error deleting todo:", error);
          toastr.error('Failed to delete todo.', 'Error');
        }
      }
    },

    getUserName(userId) {
      const user = this.users.find(u => u.id === userId);
      return user ? user.username : 'Unknown';
    },

    navigateTo(page) {
      this.currentPage = page;
    },
    logout() {
      localStorage.removeItem('user');
      this.$router.push({ name: 'Login' });
    },

    // Pagination Methods
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

<style>
.cards-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.todo-item {
  border-radius: 8px;
  background-color: #f9f9f9;
}

.todo-details {
  margin: 8px 0;
}

.todo-actions {
  display: flex;
  gap: 8px;
}

textarea {
  width: 90%;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 8px;
  resize: vertical;
  font-family: inherit;
  font-size: 16px;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 16px;
  margin-top: 15px;
}

.search-bar {
  margin: 16px 0;
}

.search-bar input {
  padding: 8px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>
