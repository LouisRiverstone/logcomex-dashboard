<template>
  <DashboardLayout pageTitle="Dashboard">
    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <StatCard
        v-for="(stat, index) in statistics"
        :key="index"
        :title="stat.title"
        :value="stat.value"
        :period="stat.period"
        :trend="stat.trend"
        :sparkline-data="stat.sparklineData"
        :color="stat.color || 'purple'"
      />
    </div>

    <!-- Charts Row 1 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- Sales by Category -->
      <ChartCard
        title="Vendas por Categoria"
        :loading="loadingStates.salesByCategory"
        :error="errors.salesByCategory"
        :filter-options="{
          showPeriod: true,
          showDateRange: true,
          defaultPeriod: '30d',
        }"
        @filter-change="handleSalesByCategoryFilterChange"
        @refresh="fetchSalesByCategoryData"
      >
        <PieChart
          v-if="
            salesByCategoryData && salesByCategoryData.datasets && salesByCategoryData.datasets.data
          "
          :labels="salesByCategoryData.labels"
          :data="salesByCategoryData.datasets.data"
          :background-color="salesByCategoryData.datasets.backgroundColor || []"
        />
      </ChartCard>

      <!-- Traffic Sources -->
      <ChartCard
        title="Fontes de Tráfego"
        :loading="loadingStates.trafficSources"
        :error="errors.trafficSources"
        :filter-options="{
          showPeriod: true,
          showDateRange: true,
          defaultPeriod: '30d',
        }"
        @filter-change="handleTrafficSourcesFilterChange"
        @refresh="fetchTrafficSourcesData"
      >
        <PieChart
          v-if="trafficSourcesData && trafficSourcesData.labels && trafficSourcesData.datasets"
          :labels="Object.values(trafficSourcesData.labels)"
          :data="trafficSourcesData.datasets.visitors.data"
          :background-color="trafficSourcesData.datasets.visitors.backgroundColor || []"
        />
      </ChartCard>

      <!-- User Distribution -->
      <ChartCard
        title="Distribuição de Usuários"
        :loading="loadingStates.userDistribution"
        :error="errors.userDistribution"
        :filter-options="{
          showPeriod: true,
          showDateRange: true,
          defaultPeriod: '30d',
        }"
        @filter-change="handleUserDistributionFilterChange"
        @refresh="fetchUserDistributionData"
      >

        <PolarAreaChart
          v-if="!loadingStates.userDistribution && userDistributionData && userDistributionData.datasets"
          :labels="Object.values(userDistributionData.labels)"
          :data="userDistributionData.datasets.users.data"
          :background-color="userDistributionData.datasets.users.backgroundColor || []"
        />
      </ChartCard>
    </div>

    <!-- Sales Dashboard And Sales Table -->
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
      <SalesTable
        title="Vendas"
        :sales="sales"
        :loading="loadingStates.sales"
        :error="errors.sales"
        :total-items="salesMeta.total"
        :current-page="salesMeta.currentPage"
        :per-page="salesMeta.perPage"
        :default-status="filters.sales.status"
        :default-start-date="filters.sales.startDate"
        :default-end-date="filters.sales.endDate"
        @filter-change="handleSalesFilterChange"
        @refresh="fetchSalesData"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
          <!-- loading -->
          <div v-if="loadingStates.sales">
            <Spinner />
          </div>

          <!-- sales by product -->
          <BarChart
            v-if="
              !loadingStates.sales &&
              salesDashboard &&
              salesDashboard.salesByProduct &&
              salesDashboard.salesByProduct.labels &&
              salesDashboard.salesByProduct.datasets
            "
            :labels="salesDashboard.salesByProduct.labels"
            :datasets="salesDashboard.salesByProduct.datasets"
          />

          <!-- sales by region -->
          <RadarChart
            v-if="
              !loadingStates.sales &&
              salesDashboard &&
              salesDashboard.salesByMonth &&
              salesDashboard.salesByMonth.labels &&
              salesDashboard.salesByMonth.datasets
            "
            :labels="salesDashboard.salesByRegion.labels"
            :datasets="salesDashboard.salesByRegion.datasets"
          />

          <!-- sales by status -->
          <BarChart
            v-if="
              !loadingStates.sales &&
              salesDashboard &&
              salesDashboard.salesByStatus &&
              salesDashboard.salesByStatus.labels &&
              salesDashboard.salesByStatus.datasets
            "
            :labels="salesDashboard.salesByStatus.labels"
            :datasets="salesDashboard.salesByStatus.datasets"
          />

          <!-- sales by user -->
          <BarChart
            v-if="
              !loadingStates.sales &&
              salesDashboard &&
              salesDashboard.salesByUser &&
              salesDashboard.salesByUser.labels &&
              salesDashboard.salesByUser.datasets
            "
            :labels="salesDashboard.salesByUser.labels"
            :datasets="salesDashboard.salesByUser.datasets"
          />

          <!-- error -->
          <div v-if="errors.sales">
            {{ errors.sales }}
          </div>
        </div>
      </SalesTable>
    </div>

    <!-- Charts Row 3 -->
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
      <!-- Recent Transactions -->
      <TransactionTable
        title="Transações Recentes"
        :transactions="transactions"
        :loading="loadingStates.transactions"
        :error="errors.transactions"
        :total-items="transactionsMeta.total"
        :current-page="transactionsMeta.currentPage"
        :default-start-date="filters.transactions.startDate"
        :default-end-date="filters.transactions.endDate"
        :per-page="transactionsMeta.perPage"
        @filter-change="handleTransactionsFilterChange"
        @refresh="fetchTransactionsData"
      >
      </TransactionTable>
    </div>
  </DashboardLayout>
</template>

<script lang="ts" setup>
import { ref, onMounted, computed, reactive } from 'vue'
import { useApi } from '@/composables/api'

import {
  VisitorsData,
  RevenueData,
  SalesByCategoryData,
  TrafficSourcesData,
  UserDistributionData,
  ProductRatingData,
  Transaction,
  StatisticItem,
  SalesDashboardData,
  SaleItem,
} from '@/api/dashboard/types'

import DashboardLayout from '@/components/templates/DashboardLayout.vue'
import Spinner from '@/components/atoms/Spinner.vue'
import StatCard from '@/components/molecules/StatCard.vue'
import RadarChart from '@/components/molecules/RadarChart.vue'
import BarChart from '@/components/molecules/BarChart.vue'
import DoughnutChart from '@/components/molecules/DoughnutChart.vue'
import PieChart from '@/components/molecules/PieChart.vue'
import PolarAreaChart from '@/components/molecules/PolarAreaChart.vue'
import ChartCard from '@/components/organisms/ChartCard.vue'
import TransactionTable from '@/components/organisms/TransactionTable.vue'
import SalesTable from '@/components/organisms/SalesTable.vue'

// API Instance
const api = useApi()
const dashboardApi = api.dashboard

// State for different components
const salesDashboard = ref<SalesDashboardData | null>(null)
const sales = ref<SaleItem[]>([])
const visitorsData = ref<VisitorsData | null>(null)
const revenueData = ref<RevenueData | null>(null)
const salesByCategoryData = ref<SalesByCategoryData | null>(null)
const trafficSourcesData = ref<TrafficSourcesData | null>(null)
const userDistributionData = ref<UserDistributionData | null>(null)
const productRatingData = ref<ProductRatingData | null>(null)
const statistics = ref<StatisticItem[]>([])
const transactions = ref<Transaction[]>([])

// Loading states
const loadingStates = reactive({
  visitors: true,
  revenue: true,
  salesByCategory: true,
  trafficSources: true,
  userDistribution: true,
  productRating: true,
  transactions: true,
  statistics: true,
  sales: true,
})

// Error states
const errors = reactive({
  visitors: '',
  revenue: '',
  salesByCategory: '',
  trafficSources: '',
  userDistribution: '',
  productRating: '',
  transactions: '',
  statistics: '',
  sales: '',
})

// Filters for each component
const filters = reactive({
  visitors: {
    startDate: new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0],
    category: '',
    source: '',
    region: '',
    searchTerm: '',
    sortBy: 'created_at',
    sortDirection: 'asc',
    page: 1,
    perPage: 10,
    groupBy: '',
    sources: [],
    regions: [],
    devices: [],
    browsers: [],
  },
  sales: {
    startDate: new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0],
    userName: null,
    productName: null,
    categoryId: null,
    region: null,
    status: null,
    orderBy: 'created_at',
    orderDirection: 'desc',
  },
  revenue: {
    period: '30d',
    comparison: false,
    year: new Date().getFullYear(),
    startDate: '',
    endDate: '',
    types: [],
  },
  salesByCategory: {
    period: '30d',
    startDate: '',
    endDate: '',
  },
  trafficSources: {
    period: '30d',
    startDate: '',
    endDate: '',
  },
  userDistribution: {
    period: '30d',
    startDate: '',
    endDate: '',
  },
  productRating: {
    period: '30d',
    productId: undefined,
    competitorId: undefined,
    startDate: '',
    endDate: '',
  },
  transactions: {
    status: '',
    search: '',
    type: '',
    startDate: new Date(new Date().setMonth(new Date().getMonth() - 1)).toISOString().split('T')[0],
    endDate: new Date().toISOString().split('T')[0],
    minAmount: '',
    maxAmount: '',
    sortBy: 'created_at',
    sortDirection: 'desc',
    customer: '',
  },
  statistics: {
    period: '30d',
    startDate: '',
    endDate: '',
  },
})

// Adicionando metadados para a paginação de transações
const transactionsMeta = reactive({
  total: 0,
  currentPage: 1,
  perPage: 15,
  lastPage: 1,
})

const salesMeta = reactive({
  total: 0,
  currentPage: 1,
  perPage: 15,
  lastPage: 1,
})

// Filter handling for each component
async function handleSalesFilterChange(newFilters: any) {
  filters.sales = { ...filters.sales, ...newFilters }

  if (newFilters.page) {
    salesMeta.currentPage = newFilters.page
  }

  if (newFilters.perPage) {
    salesMeta.perPage = newFilters.perPage
  }

  await Promise.all([fetchSalesData(), fetchSalesDashboard()])

  loadingStates.sales = false
}

function handleVisitorsFilterChange(newFilters: any) {
  filters.visitors = { ...filters.visitors, ...newFilters }
  fetchVisitorsData()
}

function handleRevenueFilterChange(newFilters: any) {
  filters.revenue = { ...filters.revenue, ...newFilters }
  fetchRevenueData()
}

function handleSalesByCategoryFilterChange(newFilters: any) {
  filters.salesByCategory = { ...filters.salesByCategory, ...newFilters }
  fetchSalesByCategoryData()
}

function handleTrafficSourcesFilterChange(newFilters: any) {
  filters.trafficSources = { ...filters.trafficSources, ...newFilters }
  fetchTrafficSourcesData()
}

function handleUserDistributionFilterChange(newFilters: any) {
  filters.userDistribution = { ...filters.userDistribution, ...newFilters }
  fetchUserDistributionData()
}

function handleProductRatingFilterChange(newFilters: any) {
  filters.productRating = { ...filters.productRating, ...newFilters }
  fetchProductRatingData()
}

function handleTransactionsFilterChange(newFilters: any) {
  filters.transactions = { ...filters.transactions, ...newFilters }

  if (newFilters.page) {
    transactionsMeta.currentPage = newFilters.page
  }

  if (newFilters.perPage) {
    transactionsMeta.perPage = newFilters.perPage
  }

  fetchTransactionsData()
}

async function fetchSalesData() {
  loadingStates.sales = true
  errors.sales = ''

  try {
    const params = {
      start_date: filters.sales.startDate || undefined,
      end_date: filters.sales.endDate || undefined,
      user_name: filters.sales.userName || undefined,
      product_name: filters.sales.productName || undefined,
      category_id: filters.sales.categoryId || undefined,
      region: filters.sales.region || undefined,
      status: filters.sales.status || undefined,
      page: filters.sales.page || undefined,
      per_page: filters.sales.perPage || undefined,
      order_by: filters.sales.orderBy || undefined,
      order_direction: filters.sales.orderDirection || undefined,
    }

    const response = await dashboardApi.getSales(params)

    if (!response.data || !response.data.data) {
      throw new Error('Failed to load sales data')
    }

    sales.value = response.data.data
    salesMeta.total = response.data.meta.total
    salesMeta.currentPage = response.data.meta.current_page
    salesMeta.perPage = response.data.meta.per_page
    salesMeta.lastPage = response.data.meta.last_page
  } catch (error) {
    console.error('Error fetching sales data:', error)
    errors.sales = 'Failed to load sales data'
    sales.value = []
    salesMeta.total = 0
    salesMeta.lastPage = 1
  } finally {
    loadingStates.sales = false
  }
}

// Data fetching methods
async function fetchSalesDashboard() {
  loadingStates.sales = true
  errors.sales = ''

  try {
    const params = {
      start_date: filters.sales.startDate || undefined,
      end_date: filters.sales.endDate || undefined,
      user_name: filters.sales.userName || undefined,
      product_name: filters.sales.productName || undefined,
      category_id: filters.sales.categoryId || undefined,
      region: filters.sales.region || undefined,
      status: filters.sales.status || undefined,
    }

    const response = await dashboardApi.getSalesDashboard(params)
    salesDashboard.value = response.data || null
  } catch (error) {
    console.error('Error fetching sales dashboard data:', error)
    errors.sales = 'Failed to load sales dashboard data'
  } finally {
    loadingStates.sales = false
  }
}

async function fetchVisitorsData() {
  loadingStates.visitors = true
  errors.visitors = ''

  try {
    const params = {
      period: filters.visitors.period,
      start_date: filters.visitors.startDate || undefined,
      end_date: filters.visitors.endDate || undefined,
      groupBy: filters.visitors.groupBy || filters.visitors.category || undefined,
      source: filters.visitors.source || undefined,
      region: filters.visitors.region || undefined,
      searchTerm: filters.visitors.searchTerm || undefined,
      sortBy: filters.visitors.sortBy || undefined,
      sortDirection: filters.visitors.sortDirection || undefined,
      page: filters.visitors.page || undefined,
      perPage: filters.visitors.perPage || undefined,
      sources: filters.visitors.sources.length ? filters.visitors.sources : undefined,
      regions: filters.visitors.regions.length ? filters.visitors.regions : undefined,
      devices: filters.visitors.devices.length ? filters.visitors.devices : undefined,
      browsers: filters.visitors.browsers.length ? filters.visitors.browsers : undefined,
    }

    const response = await dashboardApi.getVisitorsData(params)
    visitorsData.value = response.data || null
  } catch (error) {
    console.error('Error fetching visitors data:', error)
    errors.visitors = 'Failed to load visitors data'
    visitorsData.value = null
  } finally {
    loadingStates.visitors = false
  }
}

async function fetchRevenueData() {
  loadingStates.revenue = true
  errors.revenue = ''

  try {
    const params = {
      period: filters.revenue.period,
      start_date: filters.revenue.startDate || undefined,
      end_date: filters.revenue.endDate || undefined,
      comparison: filters.revenue.comparison,
      year: filters.revenue.year || undefined,
      types: filters.revenue.types.length ? filters.revenue.types : undefined,
    }

    const response = await dashboardApi.getRevenueData(params)
    revenueData.value = response.data || null
  } catch (error) {
    console.error('Error fetching revenue data:', error)
    errors.revenue = 'Failed to load revenue data'
    revenueData.value = null
  } finally {
    loadingStates.revenue = false
  }
}

async function fetchSalesByCategoryData() {
  loadingStates.salesByCategory = true
  errors.salesByCategory = ''

  try {
    const params = {
      period: filters.salesByCategory.period,
      start_date: filters.salesByCategory.startDate || undefined,
      end_date: filters.salesByCategory.endDate || undefined,
    }

    const response = await dashboardApi.getSalesByCategoryData(params)
    salesByCategoryData.value = response.data || null
  } catch (error) {
    console.error('Error fetching sales by category data:', error)
    errors.salesByCategory = 'Failed to load sales data'
    salesByCategoryData.value = null
  } finally {
    loadingStates.salesByCategory = false
  }
}

async function fetchTrafficSourcesData() {
  loadingStates.trafficSources = true
  errors.trafficSources = ''

  try {
    const params = {
      period: filters.trafficSources.period,
      start_date: filters.trafficSources.startDate || undefined,
      end_date: filters.trafficSources.endDate || undefined,
    }

    const response = await dashboardApi.getTrafficSourcesData(params)
    trafficSourcesData.value = response.data || null
  } catch (error) {
    console.error('Error fetching traffic sources data:', error)
    errors.trafficSources = 'Failed to load traffic data'
    trafficSourcesData.value = null
  } finally {
    loadingStates.trafficSources = false
  }
}

async function fetchUserDistributionData() {
  loadingStates.userDistribution = true
  errors.userDistribution = ''

  try {
    const params = {
      period: filters.userDistribution.period,
      start_date: filters.userDistribution.startDate || undefined,
      end_date: filters.userDistribution.endDate || undefined,
    }

    const response = await dashboardApi.getUserDistributionData(params)
    userDistributionData.value = response.data || null
  } catch (error) {
    console.error('Error fetching user distribution data:', error)
    errors.userDistribution = 'Failed to load user data'
    userDistributionData.value = null
  } finally {
    loadingStates.userDistribution = false
  }
}

async function fetchProductRatingData() {
  loadingStates.productRating = true
  errors.productRating = ''

  try {
    const params = {
      period: filters.productRating.period,
      start_date: filters.productRating.startDate || undefined,
      end_date: filters.productRating.endDate || undefined,
      productId: filters.productRating.productId,
      competitorId: filters.productRating.competitorId,
    }

    const response = await dashboardApi.getProductRatingData(params)
    productRatingData.value = response.data || null
  } catch (error) {
    console.error('Error fetching product rating data:', error)
    errors.productRating = 'Failed to load product data'
    productRatingData.value = null
  } finally {
    loadingStates.productRating = false
  }
}

async function fetchTransactionsData() {
  loadingStates.transactions = true
  errors.transactions = ''

  try {
    const params = {
      customer: filters.transactions.customer || undefined,
      email: filters.transactions.email || undefined,
      description: filters.transactions.description || undefined,
      sort_by: filters.transactions.sortBy || undefined,
      sort_direction: filters.transactions.sortDirection || undefined,
      start_date: filters.transactions.startDate || undefined,
      end_date: filters.transactions.endDate || undefined,
      min_amount: filters.transactions.minAmount || undefined,
      max_amount: filters.transactions.maxAmount || undefined,
      page: transactionsMeta.currentPage,
      per_page: transactionsMeta.perPage,
    }

    const response = await dashboardApi.getTransactions(params)

    if (!response.data || !response.data.data) {
      throw new Error('Failed to load transactions data')
    }

    transactions.value = response.data.data
    transactionsMeta.total = response.data.meta.total
    transactionsMeta.currentPage = response.data.meta.current_page
    transactionsMeta.perPage = response.data.meta.per_page
    transactionsMeta.lastPage = response.data.meta.last_page
  } catch (error) {
    console.error('Error fetching transactions data:', error)
    errors.transactions = 'Failed to load transactions data'
    transactions.value = []
    transactionsMeta.total = 0
    transactionsMeta.lastPage = 1
  } finally {
    loadingStates.transactions = false
  }
}

async function fetchStatisticsData() {
  loadingStates.statistics = true
  errors.statistics = ''

  try {
    const params = {
      period: filters.statistics.period,
      start_date: filters.statistics.startDate || undefined,
      end_date: filters.statistics.endDate || undefined,
    }

    const response = await dashboardApi.getStatistics(params)
    statistics.value = Array.isArray(response.data) ? response.data : []
  } catch (error) {
    console.error('Error fetching statistics data:', error)
    errors.statistics = 'Failed to load statistics'
    statistics.value = []
  } finally {
    loadingStates.statistics = false
  }
}

// Other methods
function viewAllTransactions() {
  // This function would navigate to a full transactions page
  console.log('View all transactions')
}

// Lifecycle
onMounted(() => {
  // Fetch all data on mount
  fetchSalesData()
  fetchSalesDashboard()
  fetchVisitorsData()
  // fetchRevenueData()
  fetchSalesByCategoryData()
  fetchTrafficSourcesData()
  fetchUserDistributionData()
  fetchProductRatingData()
  fetchTransactionsData()
  fetchStatisticsData()
})
</script>
