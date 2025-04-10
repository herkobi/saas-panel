<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="space-y-0.5">
                <h2 class="text-xl font-semibold tracking-tight">{{ title }}</h2>
                <p v-if="description" class="text-sm text-muted-foreground">
                    {{ description }}
                </p>
            </div>

            <div class="flex shrink-0 space-x-2">
                <div class="inline-flex">
                    <Button class="h-7 px-2 text-xs">
                        <Link :href="route('app.campaign.create')" class="flex items-center gap-1.5">
                            <Plus class="h-3.5 w-3.5" />
                            <span>Yeni Kampanya</span>
                        </Link>
                    </Button>
                </div>
            </div>
        </div>
        <Separator class="my-6" />
    </div>
</template>
