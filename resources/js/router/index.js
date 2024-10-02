import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import AdminDashboard from '../components/dashboard/AdminDashboard.vue';
import UserIndex from '../components/user/Index.vue';
import UserDetailIndex from '../components/userdetail/Index.vue';
import TodoIndex from '../components/todo/Index.vue';

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: { title: 'Login' },
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { title: 'Dashboard' },
  },
  {
    path: '/user/index',
    name: 'UserIndex',
    component: UserIndex,
    meta: { title: 'Users' },
  },
  {
    path: '/userdetail/index',
    name: 'UserDetailIndex',
    component: UserDetailIndex,
    meta: { title: 'User Details' },
  },
  {
    path: '/todo/index',
    name: 'TodoIndex',
    component: TodoIndex,
    meta: { title: 'Todo List' },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  document.title = to.meta.title || 'Default Title';
});

export default router;
