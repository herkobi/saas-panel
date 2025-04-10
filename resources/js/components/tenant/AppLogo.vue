<script setup lang="ts">
import AppLogoIcon from '@/components/tenant/AppLogoIcon.vue';

interface Props {
    class?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="flex h-10">
        <img src="/logo.png" alt="Herkobi" />
    </div>
</template>
