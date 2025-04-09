<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, ExternalLink, XCircle } from 'lucide-vue-next';

interface Tenant {
    id: number;
    name: string;
    owner: {
        id: number;
        name: string;
    } | null;
    created_at: string;
    status: boolean;
}

const props = defineProps<{
    tenants: Tenant[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Hesaplar',
        href: '/panel/tenants',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Hesaplar" />

        <div class="container mx-auto p-4">
            <div class="mb-4">
                <div class="mb-3 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-medium tracking-tight">Hesaplar</h1>
                        <p class="mt-0.5 text-sm text-muted-foreground">Sistemdeki tüm hesaplar</p>
                    </div>
                    <Button size="sm" class="h-7 px-2 gap-1">
                        <span class="text-sm">Yeni Hesap Ekle</span>
                    </Button>
                </div>

                <div class="rounded-md border bg-card">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="py-2 text-xs">Durum</TableHead>
                                <TableHead class="py-2 text-xs">Hesap Adı</TableHead>
                                <TableHead class="py-2 text-xs">Hesap Sahibi</TableHead>
                                <TableHead class="py-2 text-xs">Oluşturma Tarihi</TableHead>
                                <TableHead class="py-2 text-xs text-right">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="tenant in tenants" :key="tenant.id">
                                <TableCell class="py-1.5">
                                    <Badge :variant="tenant.status ? 'default' : 'destructive'" class="flex w-14 items-center justify-center gap-1 text-[10px]">
                                        <CheckCircle v-if="tenant.status" class="h-2.5 w-2.5" />
                                        <XCircle v-else class="h-2.5 w-2.5" />
                                        {{ tenant.status ? 'Aktif' : 'Pasif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm font-medium">{{ tenant.name }}</TableCell>
                                <TableCell class="py-1.5 text-sm">{{ tenant.owner?.name || '-' }}</TableCell>
                                <TableCell class="py-1.5 text-sm">{{ tenant.created_at }}</TableCell>
                                <TableCell class="py-1.5 text-right">
                                    <Button variant="ghost" size="sm" class="h-6 px-1.5" asChild>
                                        <Link :href="route('panel.tenants.show', tenant.id)" class="inline-flex items-center gap-1">
                                            <span class="text-[10px]">Detay</span>
                                            <ExternalLink class="h-3 w-3" />
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="tenants.length === 0">
                                <TableCell colspan="5" class="h-16 text-center text-sm text-muted-foreground">
                                    Henüz hesap bulunmuyor.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
