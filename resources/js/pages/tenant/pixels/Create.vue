<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';
import PixelForm from './PixelForm.vue';

interface PixelType {
    value: string;
    label: string;
}

interface Props {
    pixelTypes: PixelType[];
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout>
        <Head title="Yeni Piksel" />

        <LinksLayout>
            <div class="space-y-6">
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="icon" as-child>
                        <a :href="route('app.pixels')">
                            <ArrowLeft class="h-4 w-4" />
                        </a>
                    </Button>
                    <HeadingSmall title="Yeni Piksel" description="Piksel bilgilerini bu form yardımıyla oluşturabilirsiniz" />
                </div>

                <PixelForm :pixel-types="pixelTypes" />
            </div>
        </LinksLayout>
    </AppLayout>
</template>
