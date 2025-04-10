<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';
import LinkCreateModal from '@/pages/tenant/links/LinkCreateModal.vue';
import { Link } from '@inertiajs/vue3';
import { ChevronDown, Plus } from 'lucide-vue-next';
import { ref } from 'vue';

interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();

const isCreateModalOpen = ref(false);
const currentPath = window.location.pathname;
// Buton görünürlüğü kontrolü - /app/link/create sayfasında gösterilmeyecek
const shouldShowButton = !currentPath.includes('/app/link/create');
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

            <div v-if="shouldShowButton" class="flex shrink-0 space-x-2">
                <div class="inline-flex">
                    <Button @click="isCreateModalOpen = true" class="h-7 rounded-r-none px-2 text-xs" size="sm">
                        <Plus class="mr-2 h-3.5 w-3.5" />
                        <span>Hızlı Link Oluştur</span>
                    </Button>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button variant="default" class="rounded-l-none border-l-0 h-7 px-2 text-xs" size="sm">
                                <ChevronDown class="h-3.5 w-3.5" />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuItem as-child>
                                <Link :href="route('app.link.create')"> Detaylı Link Oluştur </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>
            </div>
        </div>
        <Separator class="my-6" />

        <!-- Hızlı Link Oluşturma Modalı -->
        <LinkCreateModal v-model:open="isCreateModalOpen" />
    </div>
</template>
