<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Eye, Plus } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';

interface Space {
    id: string;
    name: string;
    color: string;
}

interface Campaign {
    id: number;
    title: string;
}

interface Link {
    id: number;
    url: string;
    alias: string;
    title: string | null;
    disabled: boolean;
    clicks: number;
    space: Space | null;
    campaign: Campaign | null;
    expiration_clicks: number | null;
    ends_at: string | null;
    is_expired: boolean;
    click_limit_reached: boolean;
    created_at: string;
    created_at_formatted: string;
}

interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    links: Link[];
    pagination: Pagination;
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Linkler',
        href: '/app/links',
    },
];

// Eğer gösterilecek favicon varsa, bu fonksiyon kullanılabilir
const getFavicon = (url: string) => {
    try {
        const domain = new URL(url).hostname;
        // Önce Google'ın servisini dene
        return `https://www.google.com/s2/favicons?domain=${domain}&sz=32`;
    } catch (e) {
        // Hata durumunda varsayılan simge
        return '/favicon.png';
    }
};

// Favicon yükleme hatası kontrolü
const handleFaviconError = (event: Event) => {
    const imgElement = event.target as HTMLImageElement;
    imgElement.src = '/favicon.png';
};

// URL'i daha okunabilir hale getirme
const formatUrl = (url: string) => {
    return url.replace(/^https?:\/\//i, '');
};

// Link'in aktif olup olmadığını kontrol eden fonksiyon
const isLinkActive = (link: Link) => {
    return !link.disabled && !link.is_expired && !link.click_limit_reached;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Linkler" />

        <LinksLayout>
            <div class="w-full space-y-6">
                <div class="flex w-full items-center justify-between">
                    <HeadingSmall title="Linkler" description="Kayıtlı kampanya kısa linkleriniz" />

                    <Button as-child class="h-7 px-2 text-xs">
                        <Link :href="route('app.link.create')" class="flex items-center gap-1.5">
                            <Plus class="h-3.5 w-3.5" />
                            <span>Yeni Link</span>
                        </Link>
                    </Button>
                </div>

                <div class="w-full rounded-md border">
                    <Table class="w-full">
                        <TableCaption v-if="links.length === 0"> Henüz link oluşturmadınız. </TableCaption>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Link</TableHead>
                                <TableHead>Kampanya</TableHead>
                                <TableHead>Alan</TableHead>
                                <TableHead>Ziyaret</TableHead>
                                <TableHead>Oluşturma Tarihi</TableHead>
                                <TableHead class="text-right">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="link in links" :key="link.id">
                                <TableCell>
                                    <div class="flex items-start">
                                        <img :src="getFavicon(link.url)" @error="handleFaviconError" class="mr-2 mt-1 h-4 w-4" alt="favicon" />
                                        <div>
                                            <div class="max-w-xs truncate">
                                                <Link :href="route('app.link.detail', link.id)" :class="isLinkActive(link) ? '' : 'text-destructive'">
                                                    {{ 'https://kampanya.test/' + link.alias }}
                                                </Link>
                                            </div>
                                            <div class="max-w-xs truncate text-xs text-muted-foreground" :title="link.url">
                                                {{ link.title || formatUrl(link.url) }}
                                            </div>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <template v-if="link.campaign">
                                        <Link :href="route('app.campaign.detail', link.campaign.id)">
                                            {{ link.campaign.title }}
                                        </Link>
                                    </template>
                                    <span v-else class="text-sm text-muted-foreground">Tanımlanmamış</span>
                                </TableCell>
                                <TableCell>
                                    <template v-if="link.space">
                                        <div
                                            class="inline-block max-w-[120px] truncate rounded-md px-2 py-1 text-center text-xs text-white"
                                            :style="{ backgroundColor: link.space.color }"
                                        >
                                            {{ link.space.name }}
                                        </div>
                                    </template>
                                    <span v-else class="text-sm text-muted-foreground">Tanımlanmamış</span>
                                </TableCell>
                                <TableCell>{{ link.clicks }} Ziyaret</TableCell>
                                <TableCell>{{ link.created_at }}</TableCell>
                                <TableCell class="text-right">
                                    <Button variant="outline" size="sm" as-child class="h-7 px-2 text-xs">
                                        <Link :href="route('app.link.detail', link.id)" class="flex items-center">
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
                            <Link :href="`/app/links?page=${page}`">
                                {{ page }}
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </LinksLayout>
    </AppLayout>
</template>
