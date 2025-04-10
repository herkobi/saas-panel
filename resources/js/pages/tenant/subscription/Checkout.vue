<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Building, Building2, Hash, Mail, MapPin, MapPinned, Phone, Receipt, User } from 'lucide-vue-next';
import { computed } from 'vue';

interface Contract {
    id: number;
    title: string;
    slug: string;
}

interface Plan {
    id: number;
    name: string;
    description: string;
    is_free: boolean;
    billing_period: string;
    price: number;
    tax_rate: number;
    tax_amount: number;
    total_amount: number;
    formatted_price: string;
    formatted_tax_amount: string;
    formatted_total_amount: string;
    trial_days: number;
}

interface BillingInfo {
    name?: string;
    address?: string;
    city?: string;
    district?: string;
    postal_code?: string;
    tax_office?: string;
    tax_number?: string;
    email?: string;
    contact_name?: string;
    phone?: string;
}

interface Props {
    plan: Plan;
    paymentContracts: Contract[];
    billingInfo?: BillingInfo;
}

const props = defineProps<Props>();

const form = useForm({
    billing_period: props.plan.billing_period,
    billing_name: props.billingInfo?.name || '',
    billing_address: props.billingInfo?.address || '',
    billing_city: props.billingInfo?.city || '',
    billing_district: props.billingInfo?.district || '',
    billing_postal_code: props.billingInfo?.postal_code || '',
    billing_tax_office: props.billingInfo?.tax_office || '',
    billing_tax_number: props.billingInfo?.tax_number || '',
    billing_email: props.billingInfo?.email || '',
    billing_contact_name: props.billingInfo?.contact_name || '',
    billing_phone: props.billingInfo?.phone || '',
    contracts: [] as number[],
});

const isFormValid = computed(() => {
    const requiredFields = [
        'billing_name',
        'billing_address',
        'billing_city',
        'billing_district',
        'billing_postal_code',
        'billing_email',
        'billing_contact_name',
        'billing_phone',
    ];

    const allFieldsFilled = requiredFields.every((field) => !!form[field as keyof typeof form]);

    const contractsExist = props.paymentContracts?.length > 0;
    const allContractsAccepted = !contractsExist || form.contracts.length === props.paymentContracts.length;

    return allFieldsFilled && allContractsAccepted;
});

const submitSubscription = () => {
    if (!isFormValid.value) return;

    form.post(route('app.subscription.process', { plan: props.plan.id }), {
        onSuccess: () => {
            // Başarılı olduğunda yapılacaklar
        },
        onError: (errors) => {
            // Hata durumunda yapılacaklar
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Plan Satın Al" />

        <SubscriptionLayout>
            <HeadingSmall title="Ödeme" description="Planınızı satın almak için fatura bilgilerinizi girin" />

            <form @submit.prevent="submitSubscription">
                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Sol taraf - 2/3 genişlik -->
                    <div class="space-y-6 md:col-span-2">
                        <!-- Fatura Bilgileri -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="text-xl">Fatura Bilgileri</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-5">
                                    <div class="grid gap-2">
                                        <Label for="billing_name">Ticari Ünvan</Label>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                                <Building2 class="h-4 w-4" />
                                            </div>
                                            <Input id="billing_name" v-model="form.billing_name" class="pl-10" required />
                                        </div>
                                        <InputError :message="form.errors.billing_name" />
                                    </div>

                                    <div class="grid gap-2">
                                        <Label for="billing_address">Adres</Label>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                                <MapPin class="h-4 w-4" />
                                            </div>
                                            <Input id="billing_address" v-model="form.billing_address" class="pl-10" required />
                                        </div>
                                        <InputError :message="form.errors.billing_address" />
                                    </div>

                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-4 grid gap-2">
                                            <Label for="billing_city">İl</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <MapPinned class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_city" v-model="form.billing_city" class="pl-10" required />
                                            </div>
                                            <InputError :message="form.errors.billing_city" />
                                        </div>

                                        <div class="col-span-4 grid gap-2">
                                            <Label for="billing_district">İlçe</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <MapPinned class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_district" v-model="form.billing_district" class="pl-10" required />
                                            </div>
                                            <InputError :message="form.errors.billing_district" />
                                        </div>

                                        <div class="col-span-4 grid gap-2">
                                            <Label for="billing_postal_code">Posta Kodu</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <Hash class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_postal_code" v-model="form.billing_postal_code" class="pl-10" required />
                                            </div>
                                            <InputError :message="form.errors.billing_postal_code" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="grid gap-2">
                                            <Label for="billing_tax_office">Vergi Dairesi</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <Building class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_tax_office" v-model="form.billing_tax_office" class="pl-10" />
                                            </div>
                                            <InputError :message="form.errors.billing_tax_office" />
                                        </div>

                                        <div class="grid gap-2">
                                            <Label for="billing_tax_number">Vergi Numarası</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <Receipt class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_tax_number" v-model="form.billing_tax_number" class="pl-10" />
                                            </div>
                                            <InputError :message="form.errors.billing_tax_number" />
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Yetkili Bilgileri -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="text-xl">Yetkili Bilgileri</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-5">
                                    <div class="grid gap-2">
                                        <Label for="billing_contact_name">Yetkili Ad Soyad</Label>
                                        <div class="relative">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                                <User class="h-4 w-4" />
                                            </div>
                                            <Input id="billing_contact_name" v-model="form.billing_contact_name" class="pl-10" required />
                                        </div>
                                        <InputError :message="form.errors.billing_contact_name" />
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="grid gap-2">
                                            <Label for="billing_email">E-posta Adresi</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <Mail class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_email" type="email" v-model="form.billing_email" class="pl-10" required />
                                            </div>
                                            <InputError :message="form.errors.billing_email" />
                                        </div>

                                        <div class="grid gap-2">
                                            <Label for="billing_phone">Telefon Numarası</Label>
                                            <div class="relative">
                                                <div
                                                    class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground"
                                                >
                                                    <Phone class="h-4 w-4" />
                                                </div>
                                                <Input id="billing_phone" v-model="form.billing_phone" class="pl-10" required />
                                            </div>
                                            <InputError :message="form.errors.billing_phone" />
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Sağ taraf - 1/3 genişlik -->
                    <div class="md:col-span-1">
                        <!-- Sipariş Özeti ve Ödeme -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="text-xl">Sipariş Özeti</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-6">
                                    <div class="space-y-4">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Plan Adı:</span>
                                            <span>{{ plan.name }}</span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="font-medium">Dönemi:</span>
                                            <span>{{ plan.billing_period === 'monthly' ? 'Aylık' : 'Yıllık' }}</span>
                                        </div>

                                        <Separator />

                                        <div class="flex justify-between">
                                            <span class="font-medium">Tutar:</span>
                                            <span>{{ plan.formatted_price }}</span>
                                        </div>

                                        <div class="flex justify-between">
                                            <span class="font-medium">KDV ({{ plan.tax_rate }}%):</span>
                                            <span>{{ plan.formatted_tax_amount }}</span>
                                        </div>

                                        <Separator />

                                        <div class="flex justify-between font-bold">
                                            <span>Toplam:</span>
                                            <span>{{ plan.formatted_total_amount }}</span>
                                        </div>

                                        <div v-if="plan.trial_days > 0" class="text-center text-sm">
                                            <span class="text-muted-foreground"> {{ plan.trial_days }} gün ücretsiz deneme süresi dahildir. </span>
                                        </div>
                                    </div>

                                    <Separator />

                                    <!-- Sözleşmeler Bölümü -->
                                    <div v-if="paymentContracts && paymentContracts.length > 0" class="space-y-3">
                                        <h3 class="text-sm font-medium">Sözleşmeler</h3>
                                        <div class="space-y-2">
                                            <div v-for="contract in paymentContracts" :key="contract.id" class="flex items-start space-x-2">
                                                <input
                                                    type="checkbox"
                                                    :id="`contract-${contract.id}`"
                                                    :value="contract.id"
                                                    v-model="form.contracts"
                                                    class="mt-1"
                                                />
                                                <div>
                                                    <Label :for="`contract-${contract.id}`" class="font-medium">
                                                        {{ contract.title }}
                                                    </Label>
                                                    <p class="text-sm text-muted-foreground">
                                                        <a :href="`/contracts/${contract.slug}`" target="_blank" class="underline hover:no-underline">
                                                            Sözleşmeyi Görüntüle
                                                        </a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <InputError :message="form.errors.contracts" />
                                    </div>
                                </div>
                            </CardContent>
                            <CardFooter>
                                <Button type="submit" class="w-full" :disabled="!isFormValid">
                                    {{ plan.is_free ? 'Ücretsiz Planı Başlat' : 'Ödemeyi Tamamla' }}
                                </Button>
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            </form>
        </SubscriptionLayout>
    </AppLayout>
</template>
