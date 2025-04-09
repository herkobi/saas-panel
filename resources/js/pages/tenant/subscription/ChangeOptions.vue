<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';

interface CurrentSubscription {
    id: number;
    plan_name: string;
    billing_period: string;
    ends_at: string | null;
}

interface NewPlan {
    id: number;
    name: string;
    billing_period: string;
    price: number;
    formatted_price: string;
}

interface ProratedAmounts {
    prorated_amount: string;
    tax_amount: string;
    total_amount: string;
    days_left: number;
}

interface Props {
    currentSubscription: CurrentSubscription;
    newPlan: NewPlan;
    proratedAmounts: ProratedAmounts | null;
    isUpgrade: boolean;
    billingPeriod: string;
}

const props = defineProps<Props>();

const form = useForm({
    change_type: props.isUpgrade ? 'now' : 'end_of_period',
    billing_period: props.billingPeriod,
});

const submitChange = () => {
    form.post(route('app.subscription.change.process', { plan: props.newPlan.id }));
};
</script>

<template>
    <AppLayout>
        <Head title="Plan Değiştirme Seçenekleri" />

        <SubscriptionLayout>
            <div class="mx-auto max-w-3xl">
                <Card>
                    <CardHeader>
                        <CardTitle>Plan Değiştirme Detayları</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <!-- Mevcut ve Yeni Plan Bilgileri -->
                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <h3 class="font-medium">Mevcut Plan</h3>
                                    <div class="rounded-lg border p-4">
                                        <div class="mb-2 text-lg font-medium">{{ currentSubscription.plan_name }}</div>
                                        <div class="text-sm text-muted-foreground">
                                            {{ currentSubscription.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }} abonelik
                                        </div>
                                        <div v-if="currentSubscription.ends_at" class="mt-2 text-sm">
                                            <span class="font-medium">Dönem Sonu:</span>
                                            {{ new Date(currentSubscription.ends_at).toLocaleDateString() }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <h3 class="font-medium">Yeni Plan</h3>
                                    <div class="rounded-lg border p-4">
                                        <div class="mb-2 text-lg font-medium">{{ newPlan.name }}</div>
                                        <div class="text-sm text-muted-foreground">
                                            {{ newPlan.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }} abonelik
                                        </div>
                                        <div class="mt-2 text-lg font-bold">{{ newPlan.formatted_price }}</div>
                                    </div>
                                </div>
                            </div>

                            <Separator />

                            <!-- Değiştirme Seçenekleri -->
                            <div class="space-y-4">
                                <h3 class="font-medium">Değiştirme Zamanı</h3>

                                <form @submit.prevent="submitChange">
                                    <RadioGroup v-model="form.change_type" class="space-y-4">
                                        <!-- Hemen değiştir -->
                                        <div
                                            class="flex items-start space-x-2 rounded-lg border p-4"
                                            :class="{ 'bg-muted': form.change_type === 'now' }"
                                        >
                                            <div class="flex h-5 items-center">
                                                <RadioGroupItem value="now" id="now" :disabled="!isUpgrade" />
                                            </div>
                                            <div class="space-y-1.5">
                                                <Label for="now" class="font-medium" :class="{ 'opacity-50': !isUpgrade }"> Hemen Değiştir </Label>
                                                <p class="text-sm text-muted-foreground" :class="{ 'opacity-50': !isUpgrade }">
                                                    Plan değişikliği hemen uygulanır ve kalan süre için orantılı ücretlendirme yapılır.
                                                </p>

                                                <div v-if="proratedAmounts && isUpgrade" class="mt-3 rounded-md bg-muted p-3">
                                                    <div class="text-sm">
                                                        <div class="mb-2">
                                                            <span class="font-medium">Kalan Gün Sayısı:</span> {{ proratedAmounts.days_left }} gün
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span>Ek Ücret:</span>
                                                            <span>{{ proratedAmounts.prorated_amount }}</span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span>Vergi:</span>
                                                            <span>{{ proratedAmounts.tax_amount }}</span>
                                                        </div>
                                                        <Separator class="my-2" />
                                                        <div class="flex justify-between font-medium">
                                                            <span>Toplam Ödenecek:</span>
                                                            <span>{{ proratedAmounts.total_amount }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dönem sonunda değiştir -->
                                        <div
                                            class="flex items-start space-x-2 rounded-lg border p-4"
                                            :class="{ 'bg-muted': form.change_type === 'end_of_period' }"
                                        >
                                            <div class="flex h-5 items-center">
                                                <RadioGroupItem value="end_of_period" id="end_of_period" />
                                            </div>
                                            <div class="space-y-1.5">
                                                <Label for="end_of_period" class="font-medium"> Dönem Sonunda Değiştir </Label>
                                                <p class="text-sm text-muted-foreground">
                                                    Mevcut dönem bitiminde ({{
                                                        currentSubscription.ends_at
                                                            ? new Date(currentSubscription.ends_at).toLocaleDateString()
                                                            : 'Belirtilmemiş'
                                                    }}) plan değişikliği otomatik olarak uygulanır. Bu süreye kadar mevcut planınız geçerli olacaktır.
                                                </p>
                                            </div>
                                        </div>
                                    </RadioGroup>

                                    <div class="mt-6 flex justify-end space-x-4">
                                        <Button variant="outline" :href="route('app.subscription.change')"> Geri Dön </Button>
                                        <Button type="submit"> Değişikliği Onayla </Button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
