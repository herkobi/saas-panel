<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Eye, Plus } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';

interface Pixel {
    id: number;
    name: string;
    type: string;
    type_label: string;
    value: string;
    links_count: number;
    created_at: string;
}

interface PixelType {
    value: string;
    label: string;
}

interface Pagination {
    current_page: number;
    last_page: number;
    total: number;
}

interface Props {
    pixels: Pixel[];
    pagination: Pagination;
    pixelTypes: PixelType[];
}

const props = defineProps<Props>();

// Piksel tipine göre badge rengi belirle
const getBadgeVariant = (type: string) => {
    // Platformların gerçek kurumsal renkleri
    const typeColorMap = {
        facebook: '#1877F2', // Facebook mavi
        'google-ads': '#4285F4', // Google mavi
        'google-analytics': '#E37400', // Google Analytics turuncu
        'google-tag-manager': '#246FDB', // Google Tag Manager mavi
        linkedin: '#0A66C2', // LinkedIn mavi
        pinterest: '#E60023', // Pinterest kırmızı
        x: '#000000', // X (Twitter) siyah
        adroll: '#FF4F00', // AdRoll turuncu
        bing: '#008373', // Bing yeşil/turkuaz
        quora: '#B92B27', // Quora kırmızı
    };

    return typeColorMap[type] || '#6B7280'; // Varsayılan olarak gri
};
</script>

<template>
    <AppLayout>
        <Head title="Pikseller" />

        <LinksLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Pikseller" description="Linklerinize takip pikselleri ekleyerek analiz yapın" />

                    <Button as-child class="h-7 px-2 text-xs">
                        <Link :href="route('app.pixel.create')" class="flex items-center gap-1.5">
                            <Plus class="h-3.5 w-3.5" />
                            <span>Yeni Pixel</span>
                        </Link>
                    </Button>
                </div>

                <div class="w-full rounded-md border">
                    <Table class="w-full">
                        <TableCaption v-if="pixels.length === 0"> Henüz piksel oluşturmadınız. </TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Piksel Adı</TableHead>
                                <TableHead>Tip</TableHead>
                                <TableHead>Link Sayısı</TableHead>
                                <TableHead>Oluşturma Tarihi</TableHead>
                                <TableHead class="text-right">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="pixel in pixels" :key="pixel.id">
                                <TableCell class="font-medium">{{ pixel.name }}</TableCell>
                                <TableCell>
                                    <Badge
                                        class="flex items-center justify-center gap-1 p-1 text-xs w-[9rem]"
                                        :style="{ backgroundColor: getBadgeVariant(pixel.type), color: '#FFFFFF' }"
                                    >
                                        {{ pixel.type_label }}
                                    </Badge>
                                </TableCell>
                                <TableCell>{{ pixel.links_count }}</TableCell>
                                <TableCell>{{ pixel.created_at }}</TableCell>
                                <TableCell class="text-right">
                                    <Button variant="outline" size="sm" as-child class="h-7 px-2 text-xs">
                                        <Link :href="route('app.pixel.edit', pixel.id)" class="flex items-center">
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
                            <Link :href="`/app/pixels?page=${page}`">
                                {{ page }}
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </LinksLayout>
    </AppLayout>
</template>
