<script setup lang="ts">
import InputError from '@/components/admin/InputError.vue';
import TextLink from '@/components/admin/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';

interface Contract {
    id: number;
    title: string;
    slug: string;
}

interface Props {
    membershipContracts?: Contract[];
}

const props = defineProps<Props>();

// Her sözleşme için ayrı bir boolean değer tutacağız
const contractChecked = ref<Record<number, boolean>>({});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    contracts: [] as number[],
});

// Sözleşmelerin varsayılan olarak seçili gelmesi için
onMounted(() => {
    if (props.membershipContracts && props.membershipContracts.length > 0) {
        props.membershipContracts.forEach((contract) => {
            contractChecked.value[contract.id] = true; // Varsayılan olarak işaretli
        });
        updateContracts();
    }
});

// Checkbox değiştiğinde form.contracts dizisini güncelle
const updateContracts = () => {
    form.contracts = Object.entries(contractChecked.value)
        .filter(([_, checked]) => checked)
        .map(([id, _]) => parseInt(id));
};

const toggleContract = (id: number, checked: boolean) => {
    contractChecked.value[id] = checked;
    updateContracts();
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase title="Hesap Oluştur" description="Bilgilerinizi girerek lütfen hesap oluşturunuz">
        <Head title="Üye Ol" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Ad Soyad</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Ad Soyad" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">E-posta Adresi</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@domain.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Şifre</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Şifre"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Şifre Tekrar</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Şifre Tekrar"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <!-- Üyelik Sözleşmeleri -->
                <div v-if="membershipContracts && membershipContracts.length > 0" class="grid gap-4">
                    <div v-for="contract in membershipContracts" :key="contract.id" class="flex items-start space-x-2">
                        <Checkbox
                            :id="'contract-' + contract.id"
                            :checked="contractChecked[contract.id] || false"
                            @update:checked="(checked) => toggleContract(contract.id, checked as boolean)"
                            required
                        />
                        <Label :for="'contract-' + contract.id" class="text-sm">
                            <TextLink :href="'/sozlesme/' + contract.slug" target="_blank" :title="contract.title">
                                {{ contract.title }}
                            </TextLink>
                            'ı okudum ve kabul ediyorum.
                        </Label>
                    </div>
                    <InputError :message="form.errors.contracts" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Üye Ol
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Hesabınız var mı?
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Giriş Yapın</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
