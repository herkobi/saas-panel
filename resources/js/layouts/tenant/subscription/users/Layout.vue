<script setup lang="ts">
import Heading from '@/components/tenant/Heading.vue';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Kullanıcılar',
        href: '/app/subscription/users',
    },
    {
        title: 'Kullanıcı Ekle',
        href: '/app/subscription/users/create',
    },
    {
        title: 'Davet Et',
        href: '/app/subscription/users/invite',
    },
];

const currentPath = window.location.pathname;
</script>

<template>
    <div class="p-4">
        <Heading title="Kullanıcı Yönetimi" description="Hesabınıza ait ek kullanıcıları yönetin" />

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
                <section class="space-y-6">
                    <slot />
                </section>
            </div>
        </div>
    </div>
</template>
