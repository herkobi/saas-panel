<script setup lang="ts">
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Bilgilerim',
        href: '/app/settings/profile',
    },
    {
        title: 'Şifre Yenileme',
        href: '/app/settings/password',
    },
    {
        title: 'Görünüm',
        href: '/app/settings/appearance',
    },
];

const currentPath = window.location.pathname;
</script>

<template>
    <div class="p-4">
        <Heading title="Hesap Bilgileri" description="Hesap ve profil bilgilerinizi yönetin" />

        <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-y-0">
            <aside class="w-full max-w-xl pr-6 lg:w-48">
                <nav class="flex flex-col space-x-0 space-y-1">
                    <Button
                        v-for="item in sidebarNavItems"
                        :key="item.href"
                        variant="ghost"
                        :class="['w-full justify-start', { 'bg-muted': currentPath === item.href }]"
                        as-child
                    >
                        <Link :href="item.href">
                            {{ item.title }}
                        </Link>
                    </Button>
                </nav>
            </aside>

            <div class="hidden border-r border-border lg:block"></div>

            <Separator class="my-6 md:hidden" />

            <div class="flex-1 pl-6">
                <section class="space-y-12">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
