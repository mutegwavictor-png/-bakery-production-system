<script setup>
import { ref, computed } from 'vue'
import BatchCard from '../components/BatchCard.vue'

// ─── Task 1: Batches data (original 2 + 4 new batches) ───
const batches = ref([
  {
    id: 1,
    product_name: 'Croissant',
    planned_quantity: 100,
    actual_quantity: 95,
    wastage: 5,
    status: 'done',
    start_time: '5:00 AM',
    end_time: '8:00 AM'
  },
  {
    id: 2,
    product_name: 'Brown Bread',
    planned_quantity: 60,
    actual_quantity: null,
    wastage: 0,
    status: 'cooling',
    start_time: '7:00 AM',
    end_time: null
  },
  // ── 4 NEW batches from Task 1 ──
  {
    id: 3,
    product_name: 'Chocolate Cake',
    planned_quantity: 20,
    actual_quantity: 18,
    wastage: 2,
    status: 'done',
    start_time: '6:30 AM',
    end_time: '9:00 AM'
  },
  {
    id: 4,
    product_name: 'Cinnamon Roll',
    planned_quantity: 30,
    actual_quantity: null,
    wastage: 0,
    status: 'planned',
    start_time: null,
    end_time: null
  },
  {
    id: 5,
    product_name: 'White Bread',
    planned_quantity: 50,
    actual_quantity: 35,
    wastage: 15,
    status: 'done',
    start_time: '5:00 AM',
    end_time: '7:00 AM'
  },
  {
    id: 6,
    product_name: 'Meat Pie',
    planned_quantity: 40,
    actual_quantity: null,
    wastage: 0,
    status: 'baking',
    start_time: '10:00 AM',
    end_time: null
  }
])

// ─── Task 2: Computed stats ───
const totalBatches = computed(() => batches.value.length)

const completedBatches = computed(() =>
  batches.value.filter(b => b.status === 'done').length
)

const inProgressCount = computed(() =>
  batches.value.filter(b =>
    ['mixing', 'baking', 'cooling'].includes(b.status)
  ).length
)

const lowYieldCount = computed(() =>
  batches.value.filter(b => {
    if (!b.actual_quantity || !b.planned_quantity) return false
    return (b.actual_quantity / b.planned_quantity) * 100 < 80
  }).length
)

const failedCount = computed(() =>
  batches.value.filter(b => b.status === 'failed').length
)

// ─── Task 3: Completion panel state ───
const completionBatch = ref(null)
const completionActual = ref(0)
const completionWastage = ref(0)

function openCompletionPanel(batch) {
  completionBatch.value = batch
  completionActual.value = batch.actual_quantity || 0
  completionWastage.value = batch.wastage || 0
}

function confirmCompletion() {
  if (completionBatch.value) {
    const batch = batches.value.find(b => b.id === completionBatch.value.id)
    if (batch) {
      batch.actual_quantity = completionActual.value
      batch.wastage = completionWastage.value
      batch.status = 'done'
      batch.end_time = new Date().toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      })
    }
    completionBatch.value = null
  }
}

// ─── Status advance handler ───
function advanceStatus(batch, nextStatus) {
  const target = batches.value.find(b => b.id === batch.id)
  if (target) {
    target.status = nextStatus
    if (!target.start_time && nextStatus !== 'planned') {
      target.start_time = new Date().toLocaleTimeString('en-US', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      })
    }
  }
}

// ─── Task 4: Mark failed handler ───
function markFailed(batch) {
  const target = batches.value.find(b => b.id === batch.id)
  if (target) {
    target.status = 'failed'
    target.end_time = new Date().toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true
    })
  }
}
</script>

<template>
  <div class="production-view">
    <!-- Header -->
    <header class="view-header">
      <div>
        <h1 class="view-title">Production Batches</h1>
        <p class="view-subtitle">Track batch progress from planning through completion.</p>
      </div>
    </header>

    <!-- Task 2: Stats Grid — 5 metrics in responsive row -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon stat-icon-total">📦</div>
        <div class="stat-info">
          <span class="stat-card-label">Total Batches</span>
          <span class="stat-card-value">{{ totalBatches }}</span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon stat-icon-done">✅</div>
        <div class="stat-info">
          <span class="stat-card-label">Completed</span>
          <span class="stat-card-value stat-value-done">{{ completedBatches }}</span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon stat-icon-progress">⏳</div>
        <div class="stat-info">
          <span class="stat-card-label">In Progress</span>
          <span class="stat-card-value stat-value-progress">{{ inProgressCount }}</span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon stat-icon-low-yield">📉</div>
        <div class="stat-info">
          <span class="stat-card-label">Low Yield</span>
          <span class="stat-card-value stat-value-low-yield">{{ lowYieldCount }}</span>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon stat-icon-failed">❌</div>
        <div class="stat-info">
          <span class="stat-card-label">Failed</span>
          <span class="stat-card-value stat-value-failed">{{ failedCount }}</span>
        </div>
      </div>
    </div>

    <!-- Section header -->
    <div class="section-header">
      <h2 class="section-title">Batch Grid</h2>
      <span class="section-badge">{{ totalBatches }} batches</span>
    </div>

    <!-- Batch Cards Grid -->
    <div class="batch-grid">
      <BatchCard
        v-for="batch in batches"
        :key="batch.id"
        :batch="batch"
        @advance-status="advanceStatus"
        @mark-done="openCompletionPanel"
        @mark-failed="markFailed"
      />
    </div>

    <!-- Task 3: Completion Panel (shown below batch grid with v-if) -->
    <div v-if="completionBatch" class="completion-panel">
      <div class="completion-inner">
        <h3 class="completion-title">
          Complete Batch: <span class="completion-product">{{ completionBatch.product_name }}</span>
        </h3>
        <p class="completion-subtitle">Enter the actual output and wastage to finalize this batch.</p>

        <div class="completion-fields">
          <div class="field-group">
            <label for="actual-qty" class="field-label">Actual Quantity</label>
            <input
              id="actual-qty"
              type="number"
              v-model.number="completionActual"
              min="0"
              class="field-input"
            />
          </div>
          <div class="field-group">
            <label for="wastage-qty" class="field-label">Wastage</label>
            <input
              id="wastage-qty"
              type="number"
              v-model.number="completionWastage"
              min="0"
              class="field-input"
            />
          </div>
        </div>

        <div class="completion-actions">
          <button class="btn-cancel-completion" @click="completionBatch = null">Cancel</button>
          <button class="btn-confirm-completion" @click="confirmCompletion">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.production-view {
  padding: 1rem 0;
}

.view-header {
  margin-bottom: 2rem;
}

.view-title {
  font-size: 2rem;
  font-weight: 800;
  color: #F8FAFC;
  letter-spacing: -0.5px;
  margin: 0;
}

.view-subtitle {
  font-size: 0.95rem;
  color: #94A3B8;
  margin-top: 0.35rem;
}

/* ─── Stats Grid (Task 2) ─── */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1rem;
  margin-bottom: 2rem;
}

@media (max-width: 900px) {
  .stats-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 600px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

.stat-card {
  position: relative;
  overflow: hidden;
  background: rgba(30, 41, 59, 0.45);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 14px;
  padding: 1.2rem;
  display: flex;
  align-items: center;
  gap: 0.85rem;
  transition: all 0.3s ease;
}

.stat-card:hover {
  border-color: rgba(59, 130, 246, 0.25);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.stat-icon {
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  font-size: 1.2rem;
  border: 1px solid;
  flex-shrink: 0;
}

.stat-icon-total { background: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.2); }
.stat-icon-done { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); }
.stat-icon-progress { background: rgba(245, 158, 11, 0.1); border-color: rgba(245, 158, 11, 0.2); }
.stat-icon-low-yield { background: rgba(220, 38, 38, 0.1); border-color: rgba(220, 38, 38, 0.2); }
.stat-icon-failed { background: rgba(239, 68, 68, 0.1); border-color: rgba(239, 68, 68, 0.2); }

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-card-label {
  font-size: 0.7rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  color: #64748B;
}

.stat-card-value {
  font-size: 1.5rem;
  font-weight: 800;
  color: #F8FAFC;
  line-height: 1.2;
}

.stat-value-done { color: #6EE7B7; }
.stat-value-progress { color: #FCD34D; }
.stat-value-low-yield { color: #FCA5A5; }
.stat-value-failed { color: #EF4444; }

/* ─── Section header ─── */
.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.06);
  padding-bottom: 0.75rem;
  margin-bottom: 1.5rem;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: #E2E8F0;
}

.section-badge {
  font-size: 0.75rem;
  color: #94A3B8;
  background: rgba(255, 255, 255, 0.05);
  padding: 4px 12px;
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.08);
}

/* ─── Batch Grid ─── */
.batch-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

/* ─── Task 3: Completion Panel ─── */
.completion-panel {
  margin-top: 1rem;
  animation: slideDown 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-12px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.completion-inner {
  background: rgba(18, 18, 20, 0.75);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border: 1px solid rgba(59, 130, 246, 0.25);
  border-radius: 16px;
  padding: 2rem;
  box-shadow: 0 16px 48px rgba(0, 0, 0, 0.3);
}

.completion-title {
  font-size: 1.35rem;
  font-weight: 700;
  color: #F8FAFC;
  margin: 0 0 0.35rem 0;
}

.completion-product {
  color: #60A5FA;
}

.completion-subtitle {
  font-size: 0.85rem;
  color: #94A3B8;
  margin: 0 0 1.5rem 0;
}

.completion-fields {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

@media (max-width: 500px) {
  .completion-fields {
    flex-direction: column;
  }
}

.field-group {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.field-label {
  font-size: 0.78rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #94A3B8;
}

.field-input {
  padding: 0.75rem 1rem;
  background: rgba(15, 23, 42, 0.6);
  border: 1px solid rgba(59, 130, 246, 0.2);
  border-radius: 10px;
  color: #F8FAFC;
  font-size: 1.1rem;
  font-family: inherit;
  transition: all 0.25s;
}

.field-input:focus {
  outline: none;
  border-color: #60A5FA;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  background: rgba(15, 23, 42, 0.8);
}

.completion-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
}

.btn-cancel-completion {
  padding: 10px 20px;
  background: rgba(255, 255, 255, 0.05);
  color: #E2E8F0;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.2s;
}

.btn-cancel-completion:hover {
  background: rgba(255, 255, 255, 0.1);
}

.btn-confirm-completion {
  padding: 10px 24px;
  background: linear-gradient(135deg, #10B981, #059669);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.9rem;
  transition: all 0.2s;
  box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);
}

.btn-confirm-completion:hover {
  background: linear-gradient(135deg, #34D399, #10B981);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(5, 150, 105, 0.4);
}
</style>
