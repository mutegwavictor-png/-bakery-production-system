<script setup>
import { computed } from 'vue'

const props = defineProps({
  batch: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['advance-status', 'mark-done', 'mark-failed'])

// Status pipeline: planned → mixing → baking → cooling → done
const statusConfig = computed(() => {
  const s = props.batch.status
  const configs = {
    planned: {
      label: 'Planned',
      icon: '📋',
      badgeClass: 'badge-planned',
      borderClass: 'border-planned',
      nextAction: 'Start Mixing',
      nextStatus: 'mixing',
      canAdvance: true
    },
    mixing: {
      label: 'Mixing',
      icon: '🥣',
      badgeClass: 'badge-mixing',
      borderClass: 'border-mixing',
      nextAction: 'Move to Baking',
      nextStatus: 'baking',
      canAdvance: true
    },
    baking: {
      label: 'Baking',
      icon: '🔥',
      badgeClass: 'badge-baking',
      borderClass: 'border-baking',
      nextAction: 'Move to Cooling',
      nextStatus: 'cooling',
      canAdvance: true
    },
    cooling: {
      label: 'Cooling',
      icon: '❄️',
      badgeClass: 'badge-cooling',
      borderClass: 'border-cooling',
      nextAction: 'Mark Done',
      nextStatus: 'done',
      canAdvance: true
    },
    done: {
      label: 'Done',
      icon: '✅',
      badgeClass: 'badge-done',
      borderClass: 'border-done',
      nextAction: null,
      nextStatus: null,
      canAdvance: false
    },
    failed: {
      label: 'Failed',
      icon: '❌',
      badgeClass: 'badge-failed',
      borderClass: 'border-failed',
      nextAction: null,
      nextStatus: null,
      canAdvance: false
    }
  }
  return configs[s] || configs.planned
})

// Yield calculation
const yieldPercent = computed(() => {
  if (!props.batch.actual_quantity || !props.batch.planned_quantity) return null
  return Math.round((props.batch.actual_quantity / props.batch.planned_quantity) * 100)
})

const isLowYield = computed(() => {
  return yieldPercent.value !== null && yieldPercent.value < 80
})

// Can this batch be marked as failed? Only mixing or baking
const canMarkFailed = computed(() => {
  return props.batch.status === 'mixing' || props.batch.status === 'baking'
})

function handleAdvance() {
  if (props.batch.status === 'cooling') {
    // For cooling → done, emit mark-done so the parent shows the completion panel
    emit('mark-done', props.batch)
  } else {
    emit('advance-status', props.batch, statusConfig.value.nextStatus)
  }
}

function handleMarkFailed() {
  emit('mark-failed', props.batch)
}
</script>

<template>
  <div class="batch-card" :class="[statusConfig.borderClass, { 'batch-failed-card': batch.status === 'failed' }]">
    <!-- Glow accent -->
    <div class="batch-glow" :class="statusConfig.badgeClass"></div>

    <!-- Header: name + status -->
    <div class="batch-header">
      <h3 class="batch-name">{{ batch.product_name }}</h3>
      <span class="batch-badge" :class="statusConfig.badgeClass">
        <span class="badge-icon">{{ statusConfig.icon }}</span> {{ statusConfig.label }}
      </span>
    </div>

    <!-- Failed banner (Task 4) — uses red banner style from the component -->
    <div v-if="batch.status === 'failed'" class="failed-banner">
      ❌ Batch failed — production halted
    </div>

    <!-- Stats grid -->
    <div class="batch-stats">
      <div class="stat-item">
        <span class="stat-label">Planned</span>
        <span class="stat-value">{{ batch.planned_quantity }}</span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Actual</span>
        <span class="stat-value" :class="{ 'text-muted': batch.actual_quantity === null }">
          {{ batch.actual_quantity !== null ? batch.actual_quantity : '—' }}
        </span>
      </div>
      <div class="stat-item">
        <span class="stat-label">Waste</span>
        <span class="stat-value">{{ batch.wastage }}</span>
      </div>
      <div class="stat-item" v-if="yieldPercent !== null">
        <span class="stat-label">Yield</span>
        <span class="stat-value" :class="{ 'yield-low': isLowYield, 'yield-good': !isLowYield }">
          {{ yieldPercent }}%
        </span>
      </div>
    </div>

    <!-- Low yield warning -->
    <div v-if="isLowYield" class="low-yield-banner">
      ⚠️ LOW YIELD — {{ yieldPercent }}% (target: ≥80%)
    </div>

    <!-- Time info -->
    <div class="batch-time" v-if="batch.start_time">
      <span class="time-label">🕐 {{ batch.start_time }}</span>
      <span v-if="batch.end_time" class="time-label">→ {{ batch.end_time }}</span>
    </div>

    <!-- Actions -->
    <div class="batch-actions" v-if="batch.status !== 'failed'">
      <button
        v-if="statusConfig.canAdvance"
        class="btn-advance"
        @click="handleAdvance"
      >
        {{ statusConfig.nextAction }}
      </button>
    </div>

    <!-- Mark Failed button (Task 4) — small text button for mixing/baking -->
    <button
      v-if="canMarkFailed"
      class="btn-mark-failed"
      @click="handleMarkFailed"
    >
      ❌ Mark Failed
    </button>
  </div>
</template>

<style scoped>
.batch-card {
  position: relative;
  background: rgba(30, 41, 59, 0.4);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 16px;
  padding: 1.5rem;
  box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
  color: #E2E8F0;
  overflow: hidden;
}

.batch-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
}

.batch-glow {
  position: absolute;
  top: -40px;
  right: -40px;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  filter: blur(40px);
  opacity: 0.15;
  pointer-events: none;
}

/* Border classes by status */
.border-planned { border-color: rgba(148, 163, 184, 0.3); }
.border-planned:hover { border-color: rgba(148, 163, 184, 0.5); }
.border-mixing { border-color: rgba(139, 92, 246, 0.3); }
.border-mixing:hover { border-color: rgba(139, 92, 246, 0.5); }
.border-baking { border-color: rgba(245, 158, 11, 0.3); }
.border-baking:hover { border-color: rgba(245, 158, 11, 0.5); }
.border-cooling { border-color: rgba(6, 182, 212, 0.3); }
.border-cooling:hover { border-color: rgba(6, 182, 212, 0.5); }
.border-done { border-color: rgba(16, 185, 129, 0.3); }
.border-done:hover { border-color: rgba(16, 185, 129, 0.5); }
.border-failed { border-color: rgba(220, 38, 38, 0.4); }
.border-failed:hover { border-color: rgba(220, 38, 38, 0.6); }

/* Badge classes */
.badge-planned { background: rgba(148, 163, 184, 0.1); color: #94A3B8; border-color: rgba(148, 163, 184, 0.2); }
.badge-mixing { background: rgba(139, 92, 246, 0.15); color: #C4B5FD; border-color: rgba(139, 92, 246, 0.3); }
.badge-baking { background: rgba(245, 158, 11, 0.15); color: #FCD34D; border-color: rgba(245, 158, 11, 0.3); }
.badge-cooling { background: rgba(6, 182, 212, 0.15); color: #67E8F9; border-color: rgba(6, 182, 212, 0.3); }
.badge-done { background: rgba(16, 185, 129, 0.15); color: #6EE7B7; border-color: rgba(16, 185, 129, 0.3); }
.badge-failed { background: rgba(220, 38, 38, 0.15); color: #FCA5A5; border-color: rgba(220, 38, 38, 0.3); }

.batch-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 0.75rem;
}

.batch-name {
  font-size: 1.25rem;
  font-weight: 700;
  color: #F8FAFC;
  margin: 0;
  letter-spacing: 0.3px;
}

.batch-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  border: 1px solid;
  white-space: nowrap;
}

.badge-icon {
  font-size: 0.85rem;
}

.batch-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(70px, 1fr));
  gap: 0.75rem;
  background: rgba(15, 23, 42, 0.4);
  padding: 0.85rem;
  border-radius: 10px;
  border: 1px solid rgba(255, 255, 255, 0.05);
}

.stat-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.2rem;
}

.stat-label {
  font-size: 0.65rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: #64748B;
}

.stat-value {
  font-size: 1.25rem;
  font-weight: 700;
  color: #F1F5F9;
}

.text-muted {
  color: #475569;
}

.yield-low {
  color: #FCA5A5 !important;
  text-shadow: 0 0 12px rgba(220, 38, 38, 0.4);
}

.yield-good {
  color: #6EE7B7 !important;
}

.low-yield-banner {
  background: rgba(220, 38, 38, 0.12);
  color: #FCA5A5;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 600;
  border: 1px solid rgba(220, 38, 38, 0.25);
  text-align: center;
  animation: pulse-glow 2s ease-in-out infinite;
}

@keyframes pulse-glow {
  0%, 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
  50% { box-shadow: 0 0 12px 2px rgba(220, 38, 38, 0.15); }
}

.failed-banner {
  background: rgba(220, 38, 38, 0.15);
  color: #FCA5A5;
  padding: 8px 12px;
  border-radius: 8px;
  font-size: 0.85rem;
  font-weight: 600;
  border: 1px solid rgba(220, 38, 38, 0.3);
  text-align: center;
}

.batch-failed-card {
  opacity: 0.7;
  filter: grayscale(30%);
}

.batch-time {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  color: #94A3B8;
}

.time-label {
  background: rgba(255, 255, 255, 0.04);
  padding: 3px 8px;
  border-radius: 6px;
}

.batch-actions {
  margin-top: auto;
}

.btn-advance {
  width: 100%;
  padding: 10px 16px;
  background: linear-gradient(135deg, #3B82F6, #2563EB);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 600;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-advance:hover {
  background: linear-gradient(135deg, #60A5FA, #3B82F6);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
}

.btn-mark-failed {
  background: none;
  border: none;
  color: #EF4444;
  font-size: 0.78rem;
  font-weight: 500;
  cursor: pointer;
  padding: 4px 0;
  opacity: 0.7;
  transition: opacity 0.2s;
  text-align: left;
}

.btn-mark-failed:hover {
  opacity: 1;
  text-decoration: underline;
}
</style>
