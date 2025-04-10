<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/users/Layout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Edit, ToggleLeft, ToggleRight, UserCheck, UserPlus } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    email: string;
    status: boolean;
    type: string;
    type_label: string;
    is_owner: boolean;
    is_staff: boolean;
    email_verified_at: string | null;
    created_at: string;
}

interface Props {
    users: User[];
}

const props = defineProps<Props>();

// Kullanıcıyı aktifleştir
const activateUser = (userId: number) => {
    if (confirm('Bu kullanıcıyı aktifleştirmek istediğinize emin misiniz?')) {
        router.put(route('app.subscription.users.activate', { user: userId }));
    }
};

// Kullanıcıyı devre dışı bırak
const deactivateUser = (userId: number) => {
    if (confirm('Bu kullanıcıyı devre dışı bırakmak istediğinize emin misiniz?')) {
        router.put(route('app.subscription.users.deactivate', { user: userId }));
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Kullanıcılar" />

        <SubscriptionLayout>
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <HeadingSmall title="Kullanıcı Yönetimi" description="Tenant kullanıcılarını yönetin" />
                    <div class="flex space-x-2">
                        <Button as="a" :href="route('app.subscription.users.invite')" class="h-7 px-2 text-xs" size="sm">
                            <UserPlus class="h-3.5 w-3.5" />
                            Davet Et
                        </Button>
                        <Button as="a" :href="route('app.subscription.users.create')" class="h-7 px-2 text-xs" size="sm">
                            <UserCheck class="h-3.5 w-3.5" />
                            Kullanıcı Ekle
                        </Button>
                    </div>
                </div>

                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>İsim</TableHead>
                            <TableHead>E-posta</TableHead>
                            <TableHead>Rol</TableHead>
                            <TableHead>Durum</TableHead>
                            <TableHead>Kayıt Tarihi</TableHead>
                            <TableHead class="text-right">İşlemler</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="user in users" :key="user.id">
                            <TableCell>{{ user.name }}</TableCell>
                            <TableCell>{{ user.email }}</TableCell>
                            <TableCell>
                                <Badge :variant="user.is_owner ? 'default' : 'outline'">
                                    {{ user.type_label }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <Badge :variant="user.status ? 'success' : 'destructive'">
                                    {{ user.status ? 'Aktif' : 'Pasif' }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ user.created_at }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end space-x-2">
                                    <Button
                                        v-if="!user.is_owner"
                                        size="sm"
                                        variant="outline"
                                        as="a"
                                        :href="route('app.subscription.users.edit', { user: user.id })"
                                    >
                                        <Edit class="h-3.5 w-3.5" />
                                    </Button>

                                    <Button
                                        v-if="!user.is_owner && !user.status"
                                        size="sm"
                                        variant="outline"
                                        class="text-green-500"
                                        @click="activateUser(user.id)"
                                    >
                                        <ToggleRight class="h-3.5 w-3.5" />
                                    </Button>

                                    <Button
                                        v-if="!user.is_owner && user.status"
                                        size="sm"
                                        variant="outline"
                                        class="text-red-500"
                                        @click="deactivateUser(user.id)"
                                    >
                                        <ToggleLeft class="h-3.5 w-3.5" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
