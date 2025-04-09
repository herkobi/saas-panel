<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AdminLayout.vue';
import FeatureForm from '@/pages/admin/features/FeatureForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { CheckCircle, Pencil, XCircle } from 'lucide-vue-next';

interface Feature {
    id: number;
    name: string;
    slug: string;
    status: boolean;
    created_at: string;
    plan_count?: number;
}

const props = defineProps<{
    features: Feature[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Özellikler',
        href: '/panel/features',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Özellikler" />

        <div class="container mx-auto p-4">
            <div class="mb-8">
                <div class="mb-6">
                    <h1 class="text-xl font-semibold tracking-tight">Özellikler</h1>
                    <p class="mt-1 text-sm text-muted-foreground">Plan özellikleri yönetimi</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-5">
                    <!-- Sol Taraf - Ekleme Formu (2/5) -->
                    <Card class="lg:col-span-2">
                        <CardHeader class="pb-3 pt-5">
                            <CardTitle class="text-base">Yeni Özellik Ekle</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <FeatureForm mode="create" />
                        </CardContent>
                    </Card>

                    <!-- Sağ Taraf - Özellikler Listesi (3/5) -->
                    <Card class="lg:col-span-3">
                        <CardHeader class="pb-3 pt-5">
                            <CardTitle class="text-base">Mevcut Özellikler</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="rounded-md">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead class="py-2 text-xs">Durum</TableHead>
                                            <TableHead class="py-2 text-xs">Özellik Adı</TableHead>
                                            <TableHead class="py-2 text-xs">Kod</TableHead>
                                            <TableHead class="py-2 text-right text-xs">İşlemler</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow v-for="feature in features" :key="feature.id">
                                            <TableCell>
                                                <Badge
                                                    :variant="feature.status ? 'default' : 'destructive'"
                                                    class="flex w-16 items-center justify-center gap-1"
                                                >
                                                    <CheckCircle v-if="feature.status" class="h-3 w-3" />
                                                    <XCircle v-else class="h-3 w-3" />
                                                    {{ feature.status ? 'Aktif' : 'Pasif' }}
                                                </Badge>
                                            </TableCell>
                                            <TableCell class="font-medium">{{ feature.name }}</TableCell>
                                            <TableCell>{{ feature.slug }}</TableCell>
                                            <TableCell class="text-right">
                                                <Button variant="outline" size="sm" asChild class="h-7 px-2 text-xs">
                                                    <a :href="route('panel.features.edit', feature.id)" class="inline-flex items-center gap-1">
                                                        <Pencil class="h-3.5 w-3.5" />
                                                        <span>Düzenle</span>
                                                    </a>
                                                </Button>
                                            </TableCell>
                                        </TableRow>
                                        <TableRow v-if="features.length === 0">
                                            <TableCell colspan="3" class="h-20 text-center"> Henüz özellik bulunmuyor. </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
