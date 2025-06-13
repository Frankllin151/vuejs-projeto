<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import ProdutoAll from '@/components/ProdutoAll.vue';
import {type Produto} from "@/types"
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Produto',
        href: '/dashboard/produto',
    },
];
const data= usePage().props.data


</script>

<template>
    <Head title="Produto" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <ProdutoAll :produtos="data" />
     
        </div>
    </AppLayout>
</template>
