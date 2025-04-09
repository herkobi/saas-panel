<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { CheckCircle, Plus, Pencil } from 'lucide-vue-next';

interface Page {
    id: number;
    title: string;
    slug: string;
    summary: string | null;
    status: boolean;
    created_at: string;
}

const props = defineProps<{
    pages: Page[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Sayfalar',
        href: '/panel/pages',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Sayfalar" />

        <div class="container mx-auto p-4">
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-medium tracking-tight">Sayfalar</h1>
                        <p class="mt-0.5 text-sm text-muted-foreground">Sistemdeki tüm sayfaları yönetin</p>
                    </div>
                    <Button asChild size="sm" class="h-7 gap-1 px-2">
                        <Link :href="route('panel.pages.create')" class="flex items-center">
                            <Plus class="h-3.5 w-3.5" />
                            <span class="text-sm">Yeni Sayfa</span>
                        </Link>
                    </Button>
                </div>

                <div class="rounded-md border bg-card">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="py-2 text-xs">Durum</TableHead>
                                <TableHead class="py-2 text-xs">Başlık</TableHead>
                                <TableHead class="py-2 text-xs">Slug</TableHead>
                                <TableHead class="py-2 text-xs">Özet</TableHead>
                                <TableHead class="py-2 text-xs">Oluşturma Tarihi</TableHead>
                                <TableHead class="py-2 text-right text-xs">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="page in pages" :key="page.id">
                                <TableCell class="py-1.5">
                                    <Badge
                                        :variant="page.status ? 'default' : 'destructive'"
                                        class="flex w-14 items-center justify-center gap-1 text-[10px]"
                                    >
                                        <CheckCircle v-if="page.status" class="h-2.5 w-2.5" />
                                        <XCircle v-else class="h-2.5 w-2.5" />
                                        {{ page.status ? 'Aktif' : 'Pasif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm font-medium">{{ page.title }}</TableCell>
                                <TableCell class="py-1.5 text-sm">{{ page.slug }}</TableCell>
                                <TableCell class="py-1.5 text-sm">
                                    <span class="line-clamp-1">{{ page.summary || '-' }}</span>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm">{{ page.created_at }}</TableCell>
                                <TableCell class="py-1.5 text-right">
                                    <Button variant="outline" size="sm" asChild class="h-7 px-2 text-xs">
                                        <a :href="route('panel.pages.edit', page.id)" class="inline-flex items-center gap-1">
                                            <Pencil class="h-3.5 w-3.5" />
                                            <span>Düzenle</span>
                                        </a>
                                    </Button>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="pages.length === 0">
                                <TableCell colspan="6" class="h-16 text-center text-sm text-muted-foreground"> Henüz sayfa bulunmuyor. </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
