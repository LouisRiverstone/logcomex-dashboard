import { AxiosInstance } from "axios";
import { DashboardApi } from "./dashboard";

export class ApiInstance {
  protected axios: AxiosInstance;
  public dashboard: DashboardApi;

  constructor(axios: AxiosInstance) {
    this.axios = axios;
    this.dashboard = new DashboardApi(this.axios);
  }
}
