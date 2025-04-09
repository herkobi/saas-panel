<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';
import ContentForm from './ContentForm.vue';
</script>

<template>
    <AppLayout>
        <Head title="Yeni Alan" />

        <LinksLayout>
            <div class="space-y-6">
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="icon" as-child>
                        <a :href="route('app.spaces')">
                            <ArrowLeft class="h-4 w-4" />
                        </a>
                    </Button>
                    <HeadingSmall title="Yeni İçerik" description="İçerik bilgilerini bu form yardımıyla oluşturabilirsiniz" />
                </div>

                <ContentForm />
            </div>
        </LinksLayout>
    </AppLayout>
</template>
