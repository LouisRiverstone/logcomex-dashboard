// Pagination meta information
export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

// Dashboard data structure
export interface Dashboard {
  revenue: {
    total: number;
    trend: number;
    sparkline: number[];
  };
  users: {
    total: number;
    trend: number;
    sparkline: number[];
  };
  transactions: {
    total: number;
    trend: number;
    sparkline: number[];
    meta: PaginationMeta;
    data: Transaction[];
  };
  conversionRate: {
    value: number;
    trend: number;
    sparkline: number[];
  };
  tasks: {
    completed: number;
    pending: number;
    overdue: number;
    progress: number;
    meta: PaginationMeta;
    data: Task[];
  };
}

// Transaction model
export interface Transaction {
  id: number;
  customer: string;
  email: string;
  amount: number;
  status: string;
  description: string;
  created_at: string;
}

// Task model
export interface Task {
  id: number;
  title: string;
  description?: string;
  status: string;
  priority: string;
  dueDate: string;
  assignee: string;
  assigneeAvatar?: string;
  progress?: number;
  tags?: string[];
}

// Visitors data structure
export interface VisitorsData {
  labels: string[];
  values: number[];
  previousValues: number[];
  availableSources: string[];
  availableDevices: string[];
  total: number;
  unique: number;
  avgSessionTime: number;
  sourcesBreakdown: Array<{
    source: string;
    value: number;
    percentage: number;
  }>;
}

// Revenue data structure
export interface RevenueData {
  labels: string[];
  values: number[];
  previousValues: number[];
  availableSegments: string[];
  availableYears: number[];
  total: number;
  segmentsBreakdown: Array<{
    segment: string;
    value: number;
    percentage: number;
  }>;
}

// Sales data structure
export interface SalesData {
  categories: string[];
  values: number[];
  total: number;
  categoryBreakdown: Array<{
    category: string;
    value: number;
    percentage: number;
    trend: number;
  }>;
  yearOverYearGrowth: number;
}

// Traffic data structure
export interface TrafficData {
  sources: string[];
  values: number[];
  topSource: string;
  conversionRate: number;
  totalTraffic: number;
  previousPeriodComparison: Array<{
    source: string;
    current: number;
    previous: number;
    change: number;
  }>;
}

// Product rating data structure
export interface ProductRatingData {
  products: Array<{
    id: number;
    name: string;
  }>;
  competitors: Array<{
    id: number;
    name: string;
  }>;
  categories: string[];
  ratings: {
    [productId: number]: {
      [competitorId: number]: Array<{
        name: string;
        productRating: number;
        competitorRating: number;
        difference: number;
      }>;
    };
  };
}

// User distribution data structure
export interface UserDistributionData {
  regions: Array<{
    id: number;
    name: string;
    code: string;
    users: number;
    coordinates: [number, number];
  }>;
  totalUsers: number;
  monthlyGrowth: number;
  topRegions: Array<{
    name: string;
    users: number;
    growth: number;
  }>;
}

// Statistic item structure
export interface Statistic {
  title: string;
  value: number | string;
  description: string;
  trend: number;
  color: string;
  icon: string;
  sparklineData: number[];
}

// Trend direction
export enum TrendDirection {
  UP = 'up',
  DOWN = 'down',
  NEUTRAL = 'neutral'
}

// Trend interface
export interface Trend {
  value: number;
  direction: TrendDirection;
}

// Card component props
export interface CardProps {
  title: string;
  subtitle?: string;
  icon?: string;
  color?: string;
  loading?: boolean;
}

// Button variants
export type ButtonVariant = 'solid' | 'outline' | 'ghost' | 'link';

// Button sizes
export type ButtonSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';

// Button props
export interface ButtonProps {
  variant?: ButtonVariant;
  color?: string;
  size?: ButtonSize;
  disabled?: boolean;
  loading?: boolean;
  fullWidth?: boolean;
  pill?: boolean;
  leftIcon?: string;
  rightIcon?: string;
}

// Chart option interface
export interface ChartOptions {
  responsive?: boolean;
  maintainAspectRatio?: boolean;
  plugins?: {
    legend?: {
      display?: boolean;
      position?: 'top' | 'bottom' | 'left' | 'right';
      labels?: {
        color?: string;
        font?: {
          size?: number;
        };
      };
    };
    tooltip?: {
      enabled?: boolean;
      mode?: string;
      intersect?: boolean;
    };
  };
  scales?: {
    x?: {
      grid?: {
        display?: boolean;
        drawBorder?: boolean;
        color?: string;
      };
      ticks?: {
        color?: string;
        font?: {
          size?: number;
        };
      };
    };
    y?: {
      grid?: {
        display?: boolean;
        drawBorder?: boolean;
        color?: string;
      };
      ticks?: {
        color?: string;
        font?: {
          size?: number;
        };
      };
      beginAtZero?: boolean;
    };
  };
  elements?: {
    line?: {
      tension?: number;
    };
    point?: {
      radius?: number;
      hoverRadius?: number;
    };
  };
} 