<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AdminLayout.vue';
import FeatureForm from '@/pages/admin/features/FeatureForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';

interface Feature {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    status: boolean;
}

const props = defineProps<{
    feature: Feature;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Özellikler',
        href: '/panel/features',
    },
    {
        title: `${props.feature.name} Düzenle`,
        href: `/panel/features/${props.feature.id}/edit`,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="`${feature.name} Düzenle`" />

        <div class="container mx-auto p-4">
            <div class="mx-auto mb-8 max-w-2xl">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-semibold tracking-tight">Özelliği Düzenle</h1>
                        <p class="mt-0.5 text-sm text-muted-foreground">Özellik bilgilerini güncelleyin</p>
                    </div>
                    <Button variant="outline" size="sm" asChild class="h-8 gap-1.5">
                        <a :href="route('panel.features.index')">
                            <ArrowLeftIcon class="h-3.5 w-3.5" />
                            <span class="text-sm">Listeye Dön</span>
                        </a>
                    </Button>
                </div>

                <Card>
                    <CardHeader class="pb-3 pt-5">
                        <CardTitle class="text-base">{{ feature.name }} Düzenle</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <FeatureForm mode="edit" :feature="feature" />
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
