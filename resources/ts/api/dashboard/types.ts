export interface StatisticItem {
  id: string;
  title: string;
  value: string | number;
  description?: string;
  trend?: {
    value: number;
    direction: 'up' | 'down';
    label: string;
  };
  color?: string;
  icon?: string;
  sparklineData?: number[];
}

export interface VisitorsData {
  labels: string[];
  datasets: {
    label: string;
    data: number[];
    borderColor: string;
    backgroundColor: string;
    tension: number;
    fill?: boolean;
  }[];
}

export interface RevenueData {
  labels: string[];
  datasets: {
    label: string;
    data: number[];
    borderColor: string;
    backgroundColor: string;
    tension: number;
    fill: boolean;
  }[];
}

export interface Task {
  id: number;
  title: string;
  description?: string;
  completed: boolean;
  dueDate?: string;
  priority?: 'high' | 'medium' | 'low';
}

export interface Transaction {
  id: number;
  title: string;
  description?: string;
  username: string;
  email: string;
  date: string;
  amount: string;
  status: 'completed' | 'pending' | 'failed' | 'refunded';
  icon: string;
  color: string;
}

export interface DashboardFilters {
  period: string;
  segments: number[];
  dateRange?: {
    start: string;
    end: string;
  };
  // Visitor filters
  sources?: string[];
  regions?: string[];
  devices?: string[];
  browsers?: string[];
  searchTerm?: string;
  // Revenue filters
  year?: number;
  types?: string[];
  comparison?: boolean;
  // Task filters
  priorities?: string[];
  assignees?: number[];
  categories?: string[];
  createdBy?: number;
  completed?: boolean;
  dueDate?: string;
  dueDateStart?: string;
  dueDateEnd?: string;
  // Transaction filters
  userIds?: number[];
  statuses?: string[];
  type?: string;
  status?: string;
  minAmount?: number;
  maxAmount?: number;
  userId?: number;
  // Pagination and sorting
  sortBy?: string;
  sortDirection?: 'asc' | 'desc';
  page?: number;
  perPage?: number;
  groupBy?: string;
}

export interface DashboardData {
  statistics: StatisticItem[];
  visitorsData: VisitorsData;
  revenueData: RevenueData;
  tasks: Task[];
  transactions: {
    data: Transaction[];
    meta: {
      current_page: number;
      total: number;
      per_page: number;
      last_page: number;
    };
  };
  revenueSegments: {
    id: number;
    name: string;
    color: string;
  }[];
  periods: string[];
}

export interface SalesByCategoryData {
  labels: string[];
  datasets: {
    data: number[];
    backgroundColor: string[];
  }[];
}

export interface UserDistributionData {
  labels: string[];
  datasets: {
    data: number[];
    backgroundColor: string[];
  }[];
}

export interface TrafficSourcesData {
  labels: string[];
  datasets: {
    data: number[];
    backgroundColor: string[];
  }[];
}

export interface ProductRatingData {
  products: {
    name: string;
    rating: number;
    reviews: number;
  }[];
}

export interface SalesDashboardData {
  salesByRegion: SalesByRegion[]
  salesByUser: SalesByUser[]
  salesByStatus: SalesByStatu[]
  salesByProduct: SalesByProduct[]
  salesByMonth: SalesByMonth[]
}

export interface SalesData {
  data: SaleItem[]
  meta: Pagination
}

export interface SaleItem {
  id: number
  amount: string
  status: string
  user_name: string
  product_name: string
  region: string
  quantity: number
  created_at: string
}

export interface Pagination {
  total: number
  perPage: number
  currentPage: number
  lastPage: number
}

export interface SalesByRegion {
  labels: string[]
  datasets: {
    data: number[]
    backgroundColor: string[]
    borderColor: string[]
    borderWidth: number
  }[]
}

export interface SalesByUser {
  labels: string[]
  datasets: {
    data: number[]
    backgroundColor: string[]
    borderColor: string[]
    borderWidth: number
  }[]
}

export interface SalesByStatu {
  labels: string[]
  datasets: {
    data: number[]
    backgroundColor: string[]
    borderColor: string[]
    borderWidth: number
  }[]
}

export interface SalesByProduct {
  labels: string[]
  datasets: {
    data: number[]
    backgroundColor: string[]
    borderColor: string[]
    borderWidth: number
  }[]
}

export interface SalesByMonth {
  labels: string[]
  datasets: {
    data: number[]
    backgroundColor: string[]
    borderColor: string[]
    borderWidth: number
  }[]
}

export interface SaleDetails {
  id: number
  user_id: number
  amount: string
  status: string
  created_at: string
  updated_at: string
  items: Item[]
  user: User
}

export interface Item {
  id: number
  sale_id: number
  product_id: number
  quantity: number
  price: string
  created_at: string
  updated_at: string
  product: Product
}

export interface Product {
  id: number
  category_id: number
  name: string
  price: string
  description: string
  created_at: string
  updated_at: string
}

export interface User {
  id: number
  name: string
  email: string
  region: string
  email_verified_at: string
  last_active_at: string
  created_at: string
  updated_at: string
}
