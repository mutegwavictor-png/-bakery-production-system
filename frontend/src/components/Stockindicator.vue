<script setup>
import { computed } from 'vue'

const props = defineProps({
  name: {
    type: String,
    required: true
  },
  current: {
    type: Number,
    required: true
  },
  recorder: {
    type: [String, Number],
    required: true
  },
  unit: {
    type: String,
    required: true
  },
  reorder: {
    type: Number,
    required: false
  },
  costPerUnit: {
    type: Number,
    required: false,
    default: 0
  }
})

// Resolve the reorder level (support both 'reorder' and the typo 'recorder' props)
const reorderVal = computed(() => {
  const val = props.reorder !== undefined ? props.reorder : props.recorder;
  return typeof val === 'string' ? parseFloat(val) : val;
})

const status = computed(() => {
  const limit = reorderVal.value;
  if (props.current === 0) {
    return {
      label: 'Out of Stock',
      badgeClass: 'bg-red-500/10 text-red-400 border border-red-500/20',
      borderClass: 'border-red-500/30 hover:border-red-500/60 hover:shadow-red-500/5',
      icon: '🚨',
      iconClass: 'text-red-400',
      progressClass: 'bg-red-500'
    }
  } else if (props.current <= limit) {
    return {
      label: 'Low Stock',
      badgeClass: 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
      borderClass: 'border-amber-500/30 hover:border-amber-500/60 hover:shadow-amber-500/5',
      icon: '⚠️',
      iconClass: 'text-amber-400',
      progressClass: 'bg-amber-500'
    }
  } else {
    return {
      label: 'Healthy',
      badgeClass: 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
      borderClass: 'border-slate-800 hover:border-emerald-500/40 hover:shadow-emerald-500/5',
      icon: '✅',
      iconClass: 'text-emerald-400',
      progressClass: 'bg-emerald-500'
    }
  }
})


const progressWidth = computed(() => {
  const limit = reorderVal.value || 1;
  const percentage = (props.current / (limit * 2)) * 100; 
  return `${Math.min(Math.max(percentage, 2), 100)}%`;
})

// Stock value computation
const stockValue = computed(() => {
  return props.current * props.costPerUnit;
})
</script>

<template>
  <div 
    class="relative w-full max-w-xl overflow-hidden backdrop-blur-md bg-slate-900/40 p-6 sm:p-7 border transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
    :class="status.borderClass"
  >
    <div class="absolute -right-24 -top-24 w-48 h-48 rounded-full blur-3xl opacity-15 pointer-events-none" :class="status.progressClass"></div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
      <h3 class="text-xl font-extrabold text-slate-100 tracking-wide truncate max-w-[65%]" :title="name">
        {{ name }}
      </h3>
      <span class="w-fit px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase border whitespace-nowrap shadow-sm" :class="status.badgeClass">
        <span class="mr-1.5">{{ status.icon }}</span> {{ status.label }}
      </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end mb-5">
      <div>
        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Current Stock</span>
        <div class="flex items-baseline leading-none">
          <span class="text-4xl font-black text-white tracking-tight">
            {{ current }}
          </span>
          <span class="text-sm font-semibold text-slate-400 ml-1.5">
            {{ unit }}
          </span>
        </div>
      </div>

      <div class="sm:text-right">
        <span class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Total Asset Value</span>
        <span class="text-xl font-bold text-blue-400 block tracking-wide leading-none">
          KES {{ stockValue.toLocaleString() }}
        </span>
      </div>
    </div>

    <div class="w-full bg-slate-800/80 h-2.5 rounded-full overflow-hidden shadow-inner  mb-5">
      <div 
        class="h-full rounded-full transition-all duration-500 ease-out" 
        :class="status.progressClass"
        :style="{ width: progressWidth }"
      ></div>
    </div>

    <div class="grid grid-cols-2 gap-4 text-sm border-t border-slate-800/80 pt-4 pb-1">
      <div class="flex flex-col justify-end">
        <span class="text-slate-500 font-medium block text-[10px] uppercase tracking-wider mb-0.5">Reorder Level</span>
        <span class="text-slate-200 font-bold text-sm leading-tight">{{ reorderVal }} {{ unit }}</span>
      </div>
      <div class="flex flex-col justify-end text-right">
        <span class="text-slate-500 font-medium block text-[10px] uppercase tracking-wider mb-0.5">Cost Per Unit</span>
        <span class="text-slate-300 font-semibold text-sm leading-tight">KES {{ costPerUnit.toLocaleString() }}</span>
      </div>
    </div>
  </div>
</template>