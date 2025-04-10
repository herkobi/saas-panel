<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, Pencil, Plus, XCircle } from 'lucide-vue-next';

interface Contract {
    id: number;
    title: string;
    slug: string;
    type: string; // Tür eklendi
    date: string;
    status: boolean;
}

const props = defineProps<{
    contracts: Contract[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sözleşmeler',
        href: '/panel/contracts',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Sözleşmeler" />

        <div class="container mx-auto p-4">
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-medium tracking-tight">Sözleşmeler</h1>
                        <p class="mt-0.5 text-sm text-muted-foreground">Sistemdeki tüm sözleşmeleri yönetin</p>
                    </div>
                    <Button asChild size="sm" class="h-7 gap-1 px-2">
                        <Link :href="route('panel.contracts.create')" class="flex items-center">
                            <Plus class="h-3.5 w-3.5" />
                            <span class="text-sm">Yeni Sözleşme</span>
                        </Link>
                    </Button>
                </div>

                <div class="rounded-md border bg-card">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="py-2 text-xs">Durum</TableHead>
                                <TableHead class="py-2 text-xs">Sözleşme Adı</TableHead>
                                <TableHead class="py-2 text-xs">Tür</TableHead>
                                <TableHead class="py-2 text-xs">Slug</TableHead>
                                <TableHead class="py-2 text-xs">Son Güncelleme Tarihi</TableHead>
                                <TableHead class="py-2 text-right text-xs">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="contract in contracts" :key="contract.id">
                                <TableCell class="py-1.5">
                                    <Badge
                                        :variant="contract.status ? 'default' : 'destructive'"
                                        class="flex w-14 items-center justify-center gap-1 text-[10px]"
                                    >
                                        <CheckCircle v-if="contract.status" class="h-2.5 w-2.5" />
                                        <XCircle v-else class="h-2.5 w-2.5" />
                                        {{ contract.status ? 'Aktif' : 'Pasif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm font-medium">{{ contract.title }}</TableCell>
                                <TableCell class="py-1.5 text-sm">{{ contract.type }}</TableCell>
                                <TableCell class="py-1.5 text-sm">{{ contract.slug }}</TableCell>
                                <TableCell class="py-1.5 text-sm">{{ contract.date }}</TableCell>
                                <TableCell class="py-1.5 text-right">
                                    <Button variant="outline" size="sm" asChild class="h-7 px-2 text-xs">
                                        <a :href="route('panel.contracts.edit', contract.id)" class="inline-flex items-center gap-1">
                                            <Pencil class="h-3.5 w-3.5" />
                                            <span>Düzenle</span>
                                        </a>
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="contracts.length === 0">
                                <TableCell colspan="6" class="h-16 text-center text-sm text-muted-foreground"> Henüz sözleşme bulunmuyor. </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
