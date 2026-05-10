<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { useDefinePage } from '@/composables/useDefinePage';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface User {
    id: number;
    name: string;
}

const props = defineProps<{
    users: User[];
}>();

useDefinePage({
    layout: {
        breadcrumbs: [
            { title: 'Dashboard', href: '/dashboard' },
            { title: 'Todos', href: '/todos' },
            { title: 'Create', href: '/todos/create' },
        ],
    },
});

const form = useForm({
    title: '',
    description: '',
    user_id: '',
});

const submit = () => {
    form.post('/todos', {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Create Todo" />

    <div class="flex flex-1 flex-col gap-4 p-4 lg:max-w-2xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold tracking-tight">Create New Todo</h1>
        </div>

        <form @submit.prevent="submit" class="space-y-6 rounded-xl border p-6 bg-background shadow-sm">
            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input
                    id="title"
                    v-model="form.title"
                    :disabled="form.processing"
                />
                <div v-if="form.errors.title" class="text-sm text-destructive">{{ form.errors.title }}</div>
            </div>

            <div class="grid gap-2">
                <Label for="description">Description (Optional)</Label>
                <Input
                    id="description"
                    v-model="form.description"
                    :disabled="form.processing"
                />
                <div v-if="form.errors.description" class="text-sm text-destructive">
                    {{ form.errors.description }}
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="user_id">Assign to User</Label>
                <select
                    id="user_id"
                    v-model="form.user_id"
                    :disabled="form.processing"
                    class="block w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm ring-offset-background focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <option value="" hidden>Select a user</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">
                        {{ user.name }}
                    </option>
                </select>
                <div v-if="form.errors.user_id" class="text-sm text-destructive">
                    {{ form.errors.user_id }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button type="submit" :disabled="form.processing">
                    {{ form.processing ? 'Saving...' : 'Create Todo' }}
                </Button>

                <Link href="/todos" class="text-sm text-muted-foreground hover:underline">
                    Cancel
                </Link>
            </div>
        </form>
    </div>
</template>
