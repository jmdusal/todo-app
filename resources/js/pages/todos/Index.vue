<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useDefinePage } from '@/composables/useDefinePage';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';

interface User {
    id: number;
    name: string;
}

interface Todo {
    id: number;
    title: string;
    description: string | null;
    user_id: number;
    user?: User;
}

const props = defineProps<{
    todos: Todo[];
}>();

useDefinePage({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Todos', href: '/todos' },
        ],
    },
});

// defineOptions({
//     layout: {
//         breadcrumbs: [
//             { title: 'Dashboard', href: '/dashboard' },
//             { title: 'Todos', href: '/todos' },
//         ],
//     },
// });

const deleteTodo = (id: number) => {
    if (confirm('Are you sure you want to delete this todo?')) {
        router.delete(`/todos/${id}`);
    }
};

const confirmDelete = () => {
    return window.confirm('Are you sure you want to delete this todo?');
};

</script>

<template>
    <Head title="My Todos" />

    <div class="flex flex-1 flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Todo List</h1>
            <Link
                href="/todos/create"
                class="inline-flex items-center justify-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground shadow hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring"
            >
                Add Todo
            </Link>
        </div>

        <div class="rounded-md border border-sidebar-border/70 bg-background">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead class="w-[100px]">ID</TableHead>
                        <TableHead>Title</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead>User</TableHead>
                        <TableHead class="text-right">Actions</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="todo in todos" :key="todo.id">
                        <TableCell class="font-medium">#{{ todo.id }}</TableCell>
                        <TableCell>
                            {{ todo.title }}
                        </TableCell>
                        <TableCell>
                            {{ todo.description }}
                        </TableCell>
                        <TableCell>
                            {{ todo.user?.name }}
                        </TableCell>
                        <TableCell class="text-right">
                            <Link
                                :href="`/todos/${todo.id}/edit`"
                                class="text-sm font-medium text-primary hover:underline"
                            >
                                Edit
                            </Link>

                            <Link
                                :href="`/todos/${todo.id}`"
                                method="delete"
                                as="button"
                                type="button"
                                :onBefore="confirmDelete"
                                class="text-sm font-medium text-destructive hover:underline"
                            >
                                Delete
                            </Link>
                            <!-- <Button
                                @click="deleteTodo(todo.id)"
                                class="text-sm font-medium text-destructive hover:underline"
                            >
                                Delete
                            </Button> -->
                        </TableCell>
                    </TableRow>

                    <TableRow v-if="todos.length === 0">
                        <TableCell colspan="5" class="h-24 text-center text-muted-foreground">
                            No todo found.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
    </div>
</template>
