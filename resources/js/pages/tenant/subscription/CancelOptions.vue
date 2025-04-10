<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Textarea } from '@/components/ui/textarea';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { AlertCircle } from 'lucide-vue-next';

interface Subscription {
    id: number;
    plan_name: string;
    billing_period: string;
    ends_at: string | null;
}

interface Props {
    subscription: Subscription;
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Abonelik',
        href: '/app/subscription/plans',
    },
    {
        title: 'Abonelik İptali',
        href: `/app/subscription/${props.subscription.id}/cancel`,
    },
];

const form = useForm({
    cancel_type: 'end_of_period',
    reason: '',
});

const submitCancel = () => {
    form.post(route('app.subscription.cancel', { subscription: props.subscription.id }));
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Abonelik İptali" />

        <SubscriptionLayout>
            <div class="mx-auto max-w-3xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Abonelik İptal Seçenekleri</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-md border border-yellow-200 bg-yellow-50 p-4 dark:border-yellow-900 dark:bg-yellow-950">
                            <div class="flex items-start">
                                <AlertCircle class="mr-3 h-5 w-5 text-yellow-600 dark:text-yellow-500" />
                                <div>
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-400">Önemli Bilgi</h3>
                                    <div class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>Aboneliğinizi iptal ettikten sonra, dönem sonuna kadar tüm özelliklere erişiminiz devam edecektir. İptal işlemi geri alınamaz, tekrar abonelik oluşturmanız gerekecektir.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="mb-4 rounded-lg border p-4">
                                <div class="text-lg font-medium">{{ subscription.plan_name }}</div>
                                <div class="text-sm text-muted-foreground">
                                    {{ subscription.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }} abonelik
                                </div>
                                <div v-if="subscription.ends_at" class="mt-2 text-sm">
                                    <span class="font-medium">Dönem Sonu:</span>
                                    {{ new Date(subscription.ends_at).toLocaleDateString() }}
                                </div>
                            </div>

                            <Separator class="my-6" />

                            <form @submit.prevent="submitCancel">
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-base font-medium">İptal Zamanı</h3>
                                        <RadioGroup v-model="form.cancel_type" class="mt-4 space-y-4">
                                            <div class="flex items-start space-x-2 rounded-lg border p-4" :class="{ 'bg-muted': form.cancel_type === 'now' }">
                                                <div class="flex h-5 items-center">
                                                    <RadioGroupItem value="now" id="now" />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <Label for="now" class="font-medium">Hemen İptal Et</Label>
                                                    <p class="text-sm text-muted-foreground">
                                                        Aboneliğiniz hemen iptal edilir ve tüm erişim haklarını kaybedersiniz. Kalan süre için ödeme iadesi yapılmaz.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="flex items-start space-x-2 rounded-lg border p-4" :class="{ 'bg-muted': form.cancel_type === 'end_of_period' }">
                                                <div class="flex h-5 items-center">
                                                    <RadioGroupItem value="end_of_period" id="end_of_period" />
                                                </div>
                                                <div class="space-y-1.5">
                                                    <Label for="end_of_period" class="font-medium">Dönem Sonunda İptal Et</Label>
                                                    <p class="text-sm text-muted-foreground">
                                                        Aboneliğiniz dönem sonunda ({{ subscription.ends_at ? new Date(subscription.ends_at).toLocaleDateString() : 'Belirtilmemiş' }})
                                                        iptal edilir. Bu tarihe kadar tüm özelliklere erişiminiz devam eder.
                                                    </p>
                                                </div>
                                            </div>
                                        </RadioGroup>
                                    </div>

                                    <div>
                                        <h3 class="text-base font-medium">İptal Nedeni (İsteğe Bağlı)</h3>
                                        <p class="text-sm text-muted-foreground">Geribildiriminiz hizmetlerimizi geliştirmemize yardımcı olacaktır.</p>
                                        <Textarea v-model="form.reason" class="mt-2" placeholder="İptal nedeninizi paylaşın..." />
                                    </div>

                                    <div class="flex justify-end space-x-4">
                                        <Button variant="outline" :href="route('app.subscription.plans')">İptal</Button>
                                        <Button type="submit" variant="destructive">Aboneliği İptal Et</Button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
