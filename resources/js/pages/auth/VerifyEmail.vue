<script setup lang="ts">
import TextLink from '@/components/admin/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <AuthLayout title="E-posta Onayı" description="Gönderdiğim onay e-postasındaki linke tıklayarak e-posta adresinizi onaylayın">
        <Head title="E-posta Onayı" />

        <div v-if="status === 'verification-link-sent'" class="mb-4 text-center text-sm font-medium text-green-600">
            Kayıt esnasında girdiğiniz e-posta adresine yeni bir doğrulama bağlantısı gönderildi.
        </div>

        <form @submit.prevent="submit" class="space-y-6 text-center">
            <Button :disabled="form.processing" variant="secondary">
                <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                Oney e-postasını tekrar gönderin
            </Button>

            <TextLink :href="route('logout')" method="post" as="button" class="mx-auto block text-sm"> Oturumu Kapat </TextLink>
        </form>
    </AuthLayout>
</template>
