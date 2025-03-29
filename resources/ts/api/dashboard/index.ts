import { AxiosResponse } from "axios";
import { Api } from "../api";
import {
  DashboardData,
  VisitorsData,
  RevenueData,
  SalesByCategoryData,
  UserDistributionData,
  TrafficSourcesData,
  ProductRatingData,
  Task,
  Transaction,
  DashboardFilters,
  SalesDashboardData,
  SalesData,
  SaleDetails
} from "./types";

export interface SalesParams {
  start_date: string;
  end_date: string;
  user_name?: string;
  product_name?: string;
  category_id?: number;
  region?: string;
  status?: string;
  page?: number;
  per_page?: number;
  order_by?: string;
  order_direction?: string;
}

// Interface base para parâmetros de período
export interface PeriodParams {
  period?: "7d" | "30d" | "90d" | "1y" | "all";
  start_date?: string;
  end_date?: string;
}

// Parâmetros para o endpoint de visitantes
export interface VisitorsParams extends PeriodParams {
  source?: string;
  region?: string;
  startDate?: string;
  endDate?: string;
  searchTerm?: string;
  sortBy?: string;
  sortDirection?: "asc" | "desc";
  page?: number;
  perPage?: number;
  groupBy?: "source" | "region" | "device" | "browser" | "date";
  sources?: string[];
  regions?: string[];
  devices?: string[];
  browsers?: string[];
}

// Parâmetros para o endpoint de receita
export interface RevenueParams extends PeriodParams {
  year?: number;
  startDate?: string;
  endDate?: string;
  comparison?: boolean;
  types?: ("sales" | "subscriptions" | "refunds")[];
}

// Parâmetros para o endpoint de avaliação de produto
export interface ProductRatingParams extends PeriodParams {
  productId?: number;
  competitorId?: number;
}

// Parâmetros para o endpoint de tarefas
export interface TasksParams {
  priority?: "low" | "medium" | "high";
  completed?: boolean;
  dueDate?: string;
  dueDateStart?: string;
  dueDateEnd?: string;
  searchTerm?: string;
  sortBy?: string;
  sortDirection?: "asc" | "desc";
  page?: number;
  perPage?: number;
  groupBy?: "priority" | "assignee" | "category" | "date";
  priorities?: ("low" | "medium" | "high")[];
  assignees?: number[];
  categories?: string[];
  createdBy?: number[];
}

// Parâmetros para o endpoint de transações
export interface TransactionsParams {
  type?: "sale" | "refund" | "payment";
  status?: "success" | "warning" | "danger";
  startDate?: string;
  endDate?: string;
  minAmount?: number;
  maxAmount?: number;
  customer?: string;
  email?: string;
  description?: string;
  sortBy?: string;
  sortDirection?: "asc" | "desc";
  page?: number;
  perPage?: number;
}

export class DashboardApi extends Api {
  private path: string = "/dashboard";

  public async getSales(params?: SalesParams): Promise<AxiosResponse<SalesData>> {
    return this.axios.get(`${this.path}/sales`, { params });
  }

  public async getSalesDashboard(params?: SalesParams): Promise<AxiosResponse<SalesDashboardData>> {
    return this.axios.get(`${this.path}/sales-dashboard`, { params });
  }

  public async getVisitorsData(params?: VisitorsParams): Promise<AxiosResponse<VisitorsData>> {
    return this.axios.get(`${this.path}/visitors`, { params });
  }

  public async getRevenueData(params?: RevenueParams): Promise<AxiosResponse<RevenueData>> {
    return this.axios.get(`${this.path}/revenue`, { params });
  }

  public async getSalesByCategoryData(params?: PeriodParams): Promise<AxiosResponse<SalesByCategoryData>> {
    return this.axios.get(`${this.path}/sales-by-category`, { params });
  }

  public async getUserDistributionData(params?: PeriodParams): Promise<AxiosResponse<UserDistributionData>> {
    return this.axios.get(`${this.path}/user-distribution`, { params });
  }

  public async getTrafficSourcesData(params?: PeriodParams): Promise<AxiosResponse<TrafficSourcesData>> {
    return this.axios.get(`${this.path}/traffic-sources`, { params });
  }

  public async getProductRatingData(params?: ProductRatingParams): Promise<AxiosResponse<ProductRatingData>> {
    return this.axios.get(`${this.path}/product-rating`, { params });
  }

  public async getTasks(params?: TasksParams): Promise<AxiosResponse<Task[]>> {
    return this.axios.get(`${this.path}/tasks`, { params });
  }

  public async getTransactions(params?: TransactionsParams): Promise<AxiosResponse<Transaction[]>> {
    return this.axios.get(`${this.path}/transactions`, { params });
  }

  public async getStatistics(params?: PeriodParams): Promise<AxiosResponse<any>> {
    return this.axios.get(`${this.path}/statistics`, { params });
  }

  public async getSaleDetails(id: number): Promise<AxiosResponse<SaleDetails>> {
    return this.axios.get(`${this.path}/sale-details/${id}`);
  }

  public async updateTaskStatus(taskId: number, completed: boolean): Promise<AxiosResponse<Task>> {
    return this.axios.patch(`${this.path}/tasks/${taskId}`, {
      completed
    });
  }

  public async getDashboardData(filters?: DashboardFilters): Promise<AxiosResponse<DashboardData>> {
    return this.axios.get(`${this.path}`, { params: filters });
  }
}
