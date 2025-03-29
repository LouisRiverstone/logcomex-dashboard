import axios from "axios";
import { ApiInstance } from "@/api";

export const useApi = () => {
  const axiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_URL,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    }
  });

  axiosInstance.interceptors.request.use((request) => {
    return request;
  });

  axiosInstance.interceptors.response.use(async (response) => {
    return response;
  });

  return new ApiInstance(axiosInstance);
};



