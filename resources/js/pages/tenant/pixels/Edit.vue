<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/tenant/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';

import AppLayout from '@/layouts/TenantLayout.vue';
import LinksLayout from '@/layouts/tenant/links/Layout.vue';
import PixelForm from './PixelForm.vue';

interface Pixel {
  id: number;
  name: string;
  type: string;
  value: string;
  created_at: string;
}

interface PixelType {
  value: string;
  label: string;
}

interface Props {
  pixel: Pixel;
  pixelTypes: PixelType[];
}

const props = defineProps<Props>();
</script>

<template>
  <AppLayout>
    <Head :title="`${pixel.name} Düzenle`" />

    <LinksLayout>
      <div class="space-y-6">
        <div class="flex items-center gap-2">
          <Button variant="outline" size="icon" as-child>
            <a :href="route('app.pixels')">
              <ArrowLeft class="h-4 w-4" />
            </a>
          </Button>
          <HeadingSmall
            title="Piksel Düzenle"
            description="Piksel bilgilerini bu form yardımıyla düzenleyebilirsiniz"
          />
        </div>

        <PixelForm
          :pixel="pixel"
          :pixel-types="pixelTypes"
          :is-editing="true"
        />
      </div>
    </LinksLayout>
  </AppLayout>
</template>
