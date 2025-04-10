<script setup lang="ts">
import InputError from '@/components/tenant/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/TenantLayout.vue';
import SubscriptionLayout from '@/layouts/tenant/subscription/users/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { User, Mail, Lock } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    email: string;
    status: boolean;
}

interface Props {
    user: User | null;
}

const props = defineProps<Props>();

const isEditMode = computed(() => !!props.user);
const pageTitle = computed(() => isEditMode.value ? 'Kullanıcı Düzenle' : 'Yeni Kullanıcı Ekle');

const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: '',
    password_confirmation: '',
    status: props.user?.status ?? true,
    send_invitation: !isEditMode.value,
});

const submit = () => {
    if (isEditMode.value) {
        form.put(route('app.subscription.users.update', { user: props.user!.id }));
    } else {
        form.post(route('app.subscription.users.store'));
    }
};
</script>

<template>
    <AppLayout>
        <Head :title="pageTitle" />

        <SubscriptionLayout>
            <div class="space-y-6">

                <Card class="max-w-2xl mx-auto">
                    <form @submit.prevent="submit">
                        <CardHeader>
                            <CardTitle class="text-xl">{{ pageTitle }}</CardTitle>
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

                                <div class="space-y-2">
                                    <Label for="password">Şifre</Label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                            <Lock class="h-4 w-4" />
                                        </div>
                                        <Input id="password" type="password" v-model="form.password" class="pl-10"
                                            :required="!isEditMode" />
                                    </div>
                                    <InputError :message="form.errors.password" />
                                    <p v-if="isEditMode" class="text-xs text-muted-foreground">
                                        Boş bırakırsanız şifre değişmeyecektir.
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label for="password_confirmation">Şifre Tekrar</Label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                                            <Lock class="h-4 w-4" />
                                        </div>
                                        <Input id="password_confirmation" type="password" class="pl-10"
                                            v-model="form.password_confirmation" :required="!isEditMode" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="status">Durum</Label>
                                        <Switch id="status" v-model="form.status" />
                                    </div>
                                    <p class="text-xs text-muted-foreground">
                                        {{ form.status ? 'Aktif' : 'Pasif' }} durumda
                                    </p>
                                </div>

                                <div v-if="!isEditMode" class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <Label for="send_invitation">Davet Gönder</Label>
                                        <Switch id="send_invitation" v-model="form.send_invitation" />
                                    </div>
                                    <p class="text-xs text-muted-foreground">
                                        {{ form.send_invitation ? 'Kullanıcıya e-posta ile davet gönderilecek' : 'Davet gönderilmeyecek' }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex justify-between">
                            <Button as="a" variant="outline" :href="route('app.subscription.users.index')">
                                İptal
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                {{ isEditMode ? 'Güncelle' : 'Kaydet' }}
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </SubscriptionLayout>
    </AppLayout>
</template>
