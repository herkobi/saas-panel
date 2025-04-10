<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/users/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Mail, User } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
});

const submit = () => {
    form.post(route('app.subscription.invite.user'));
};
</script>

<template>
    <AppLayout>
        <Head title="Kullanıcı Davet Et" />

        <SubscriptionLayout>
            <div class="space-y-6">
                <Card class="mx-auto max-w-2xl">
                    <form @submit.prevent="submit">
                        <CardHeader>
                            <CardTitle class="text-xl">Kullanıcı Davet Et</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="name">İsim</Label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                            <User class="h-4 w-4" />
                                        </div>
                                        <Input id="name" v-model="form.name" class="pl-10" required />
                                    </div>
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="email">E-posta</Label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                            <Mail class="h-4 w-4" />
                                        </div>
                                        <Input id="email" type="email" v-model="form.email" class="pl-10" required />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>

                                <p class="text-sm text-muted-foreground">
                                    Davet edilen kullanıcıya otomatik bir şifre oluşturulup e-posta ile gönderilecektir. Kullanıcı ilk girişinden
                                    sonra şifresini değiştirebilir.
                                </p>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-between">
                            <Button as="a" variant="outline" :href="route('app.subscription.users.index')"> İptal </Button>
                            <Button type="submit" :disabled="form.processing"> Davet Gönder </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
