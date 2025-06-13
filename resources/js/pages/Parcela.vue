<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { usePage } from '@inertiajs/vue3'
import ParcelaDados from '@/components/parcelaDados.vue';
import ParcelaAdicionar from '@/components/ParcelaAdicionar.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
    <ParcelaAdicionar/>
      <ParcelaDados/>
        </div>
    </AppLayout>
</template>
