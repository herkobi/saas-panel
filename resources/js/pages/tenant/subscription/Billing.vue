<script setup lang="ts">
import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { Download } from 'lucide-vue-next';

interface BillingHistory {
    id: number;
    date: string;
    amount: string;
    status: string;
    invoice_url: string | null;
}

interface Props {
    billingHistory: BillingHistory[];
}

const props = defineProps<Props>();

// Ödeme durumuna göre badge rengini belirle
const getStatusBadge = (status: string) => {
    switch (status) {
        case 'succeeded':
            return 'success';
        case 'pending':
            return 'warning';
        case 'failed':
            return 'destructive';
        default:
            return 'secondary';
    }
};

// Ödeme durumu metni
const getStatusText = (status: string) => {
    switch (status) {
        case 'succeeded':
            return 'Başarılı';
        case 'pending':
            return 'Beklemede';
        case 'failed':
            return 'Başarısız';
        default:
            return status;
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Ödeme Geçmişi" />

        <SubscriptionLayout>
            <div class="space-y-6">
                <HeadingSmall title="Ödeme Geçmişi" description="Abonelik ödemelerinizi ve faturalarınızı görüntüleyin" />

                <div v-if="billingHistory && billingHistory.length > 0">
                    <div class="w-full rounded-md border space-y-6">
                        <Table class="w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Tarih</TableHead>
                                    <TableHead>Tutar</TableHead>
                                    <TableHead>Durum</TableHead>
                                    <TableHead>Fatura</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="payment in billingHistory" :key="payment.id">
                                    <TableCell>{{ new Date(payment.date).toLocaleDateString() }}</TableCell>
                                    <TableCell>{{ payment.amount }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusBadge(payment.status)">
                                            {{ getStatusText(payment.status) }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <Button v-if="payment.invoice_url" size="sm" variant="outline" as="a" :href="payment.invoice_url">
                                            <Download class="mr-2 h-4 w-4" />
                                            İndir
                                        </Button>
                                        <span v-else class="text-sm text-muted-foreground">Mevcut değil</span>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                    <!-- Ödeme bilgilerini güncelleme butonu vb. eklenebilir -->
                    <div class="flex justify-end mt-3">
                        <Button variant="outline" as="a" :href="route('app.subscription.plans')">Planları Görüntüle</Button>
                    </div>
                </div>
                <div v-else class="rounded-lg border p-6 shadow-sm">
                    <div class="text-center">
                        <h3 class="text-lg font-medium">Ödeme Kaydı Bulunamadı</h3>
                        <p class="mt-2 text-sm text-muted-foreground">Henüz tamamlanmış bir ödeme kaydınız bulunmamaktadır..</p>
                        <Button as="a" class="mt-4" :href="route('app.subscription.plans')">Planları Görüntüle</Button>
                    </div>
                </div>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
