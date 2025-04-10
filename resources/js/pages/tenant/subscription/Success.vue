<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/Layout.vue';
import { Head } from '@inertiajs/vue3';
import { CheckCircle } from 'lucide-vue-next';
</script>

<template>
    <AppLayout>
        <Head title="Abonelik Başarılı" />

        <SubscriptionLayout>
            <Card class="mx-auto max-w-lg">
                <CardHeader class="text-center">
                    <CheckCircle class="mx-auto mb-4 h-16 w-16 text-green-500" />
                    <h2 class="text-xl font-bold">Aboneliğiniz Başarıyla Oluşturuldu!</h2>
                </CardHeader>
                <CardContent>
                    <p class="text-center text-muted-foreground">
                        Aboneliğiniz başarıyla oluşturuldu. Artık tüm özelliklere erişebilirsiniz. Abonelik detaylarınızı abonelik sayfanızdan
                        görüntüleyebilirsiniz.
                    </p>
                </CardContent>
                <CardFooter class="flex justify-center">
                    <Button as="a" :href="route('app.dashboard')">Başlangıca Git</Button>
                </CardFooter>
            </Card>
        </SubscriptionLayout>
    </AppLayout>
</template>
