<script setup lang="ts">
import AppLogo from '@/components/admin/AppLogo.vue';
import NavAccount from '@/components/admin/NavAccount.vue';
import NavMain from '@/components/admin/NavMain.vue';
import NavPlan from '@/components/admin/NavPlan.vue';
import NavSettings from '@/components/admin/NavSettings.vue';
import NavUser from '@/components/admin/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { Album, ClipboardPenLine, CreditCard, Eraser, LayoutGrid, PencilRuler, UserRoundCheck, NotebookText } from 'lucide-vue-next';

const mainNavItems: NavItem[] = [
    {
        title: 'Başlangıç',
        href: '/panel',
        icon: LayoutGrid,
    },
];

const accountNavItems: NavItem[] = [
    {
        title: 'Hesaplar',
        href: '/panel/tenants',
        icon: UserRoundCheck,
    },
    {
        title: 'Ödemeler',
        href: '/panel/orders',
        icon: CreditCard,
    },
];

const planNavItems: NavItem[] = [
    {
        title: 'Planlar',
        href: '/panel/plans',
        icon: Album,
    },
    {
        title: 'Özellikler',
        href: '/panel/features',
        icon: PencilRuler,
    },
];

const settingsNavItems: NavItem[] = [
    {
        title: 'Önbellek Yönetimi',
        href: '/panel/cache',
        icon: Eraser,
    },
    {
        title: 'Sayfalar',
        href: '/panel/pages',
        icon: NotebookText,
    },
    {
        title: 'Sözleşmeler',
        href: '/panel/contracts',
        icon: ClipboardPenLine,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('panel.dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
            <NavAccount :items="accountNavItems" />
            <NavPlan :items="planNavItems" />
            <NavSettings :items="settingsNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
