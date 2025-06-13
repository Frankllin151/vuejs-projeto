<!-- components/ui/modal/Modal.vue -->
<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
  show: boolean
}>()
</script>

<template>
  <teleport to="body">
    <div
      v-if="props.show"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
    >
      <div
        data-slot="modal"
        :class="
          cn(
            'bg-white dark:bg-zinc-900 text-black dark:text-white p-6 rounded-xl shadow-lg w-full max-w-lg relative',
            props.class,
          )
        "
      >
        <slot />
      </div>
    </div>
  </teleport>
</template>
