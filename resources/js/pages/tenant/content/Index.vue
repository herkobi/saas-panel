<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Eye, Plus } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';

interface Space {
    id: number;
    name: string;
    color: string;
    links_count: number;
    created_at: string;
}

interface Pagination {
    current_page: number;
    last_page: number;
    total: number;
}

interface Props {
    spaces: Space[];
    pagination: Pagination;
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head title="Alanlar" />

        <LinksLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Alanlar" description="Linklerinizi alanlara ekleyerek takibini kolaylaştırın" />

                    <Button as-child class="h-7 px-2 text-xs">
                        <Link :href="route('app.space.create')" class="flex items-center gap-1.5">
                            <Plus class="h-3.5 w-3.5" />
                            <span>Yeni Alan</span>
                        </Link>
                    </Button>
                </div>

                <div class="w-full rounded-md border">
                    <Table class="w-full">
                        <TableCaption v-if="spaces.length === 0"> Henüz alan oluşturmadınız. </TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Renk</TableHead>
                                <TableHead>Alan Adı</TableHead>
                                <TableHead>Link Sayısı</TableHead>
                                <TableHead>Oluşturma Tarihi</TableHead>
                                <TableHead class="text-right">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="space in spaces" :key="space.id">
                                <TableCell>
                                    <div :style="{ backgroundColor: space.color }" class="h-6 w-6 rounded-full shadow-sm"></div>
                                </TableCell>
                                <TableCell class="font-medium">{{ space.name }}</TableCell>
                                <TableCell>{{ space.links_count }}</TableCell>
                                <TableCell>{{ space.created_at }}</TableCell>
                                <TableCell class="text-right">
                                    <Button variant="outline" size="sm" as-child class="h-7 px-2 text-xs">
                                        <Link :href="route('app.space.edit', space.id)" class="flex items-center">
                                            <Eye class="h-3.5 w-3.5" />
                                            <span>Detay</span>
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- Pagination -->
                <div v-if="pagination.last_page > 1" class="mt-4 flex w-full justify-center">
                    <div class="flex items-center gap-1">
                        <Button
                            v-for="page in pagination.last_page"
                            :key="page"
                            :variant="page === pagination.current_page ? 'default' : 'outline'"
                            size="sm"
                            :disabled="page === pagination.current_page"
                            asChild
                        >
                            <Link :href="`/app/spaces?page=${page}`">
                                {{ page }}
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </LinksLayout>
    </AppLayout>
</template>
