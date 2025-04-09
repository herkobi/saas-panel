<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import DeleteConfirmDialog from '@/components/DeleteConfirmDialog.vue';
import AppLayout from '@/layouts/AdminLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { CheckCircle, PenSquare, Plus, Trash2, XCircle } from 'lucide-vue-next';
import { ref } from 'vue';

interface Plan {
    id: number;
    name: string;
    monthly_price: number | null;
    yearly_price: number | null;
    is_featured: boolean;
    is_free: boolean;
    status: boolean;
    sort_order: number | null;
    created_at: string;
}

const props = defineProps<{
    plans: Plan[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Planlar',
        href: '/panel/plans',
    },
];

// Modal durumu için state
const deleteDialogOpen = ref(false);
const planToDelete = ref<number | null>(null);

// Silme işlemi için onay modalını göster
const confirmDelete = (id: number) => {
    planToDelete.value = id;
    deleteDialogOpen.value = true;
};

// Silme işlemi onaylandığında
const handleDeleteConfirmed = () => {
    if (planToDelete.value) {
        router.delete(route('panel.plans.destroy', planToDelete.value));
    }
};

const formatPrice = (price: number | null) => {
    if (price === null) return '';
    return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(price);
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Planlar" />

        <div class="container mx-auto p-4">
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-lg font-medium tracking-tight">Planlar</h1>
                        <p class="mt-0.5 text-sm text-muted-foreground">Sistemdeki tüm planları yönetin</p>
                    </div>
                    <Button asChild size="sm" class="h-7 px-2 gap-1">
                        <Link :href="route('panel.plans.create')" class="flex items-center">
                            <Plus class="h-3.5 w-3.5" />
                            <span class="text-sm">Yeni Plan Ekle</span>
                        </Link>
                    </Button>
                </div>

                <div class="rounded-md border bg-card">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="py-2 text-xs">Durum</TableHead>
                                <TableHead class="py-2 text-xs">Plan Adı</TableHead>
                                <TableHead class="py-2 text-xs">Aylık Fiyat</TableHead>
                                <TableHead class="py-2 text-xs">Yıllık Fiyat</TableHead>
                                <TableHead class="py-2 text-xs">Sıra</TableHead>
                                <TableHead class="py-2 text-xs text-right">İşlemler</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="plan in plans" :key="plan.id">
                                <TableCell class="py-1.5">
                                    <Badge :variant="plan.status ? 'default' : 'destructive'" class="flex w-14 items-center justify-center gap-1 text-[10px]">
                                        <CheckCircle v-if="plan.status" class="h-2.5 w-2.5" />
                                        <XCircle v-else class="h-2.5 w-2.5" />
                                        {{ plan.status ? 'Aktif' : 'Pasif' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm font-medium">
                                    {{ plan.name }}
                                    <span v-if="plan.is_featured" class="ml-1 text-[10px] text-yellow-500">(Featured)</span>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm">
                                    <span v-if="plan.is_free">Ücretsiz</span>
                                    <span v-else>{{ formatPrice(plan.monthly_price) }}</span>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm">
                                    <span v-if="plan.is_free">Ücretsiz</span>
                                    <span v-else>{{ formatPrice(plan.yearly_price) }}</span>
                                </TableCell>
                                <TableCell class="py-1.5 text-sm">
                                    {{ plan.sort_order || '-' }}
                                </TableCell>
                                <TableCell class="py-1.5 text-right">
                                    <div class="flex justify-end gap-1">
                                        <Button variant="ghost" size="sm" class="h-6 w-6 p-0" asChild>
                                            <Link :href="route('panel.plans.edit', plan.id)" class="inline-flex items-center justify-center">
                                                <PenSquare class="h-3.5 w-3.5" />
                                                <span class="sr-only">Düzenle</span>
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="h-6 w-6 p-0 text-destructive"
                                            @click="confirmDelete(plan.id)"
                                        >
                                            <Trash2 class="h-3.5 w-3.5" />
                                            <span class="sr-only">Sil</span>
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="plans.length === 0">
                                <TableCell colspan="7" class="h-16 text-center text-sm text-muted-foreground">
                                    Henüz plan bulunmuyor.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>

        <!-- Silme onay modalı -->
        <DeleteConfirmDialog
            v-model:isOpen="deleteDialogOpen"
            title="Planı Sil"
            description="Bu planı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz."
            @confirm="handleDeleteConfirmed"
        />
    </AppLayout>
</template>
