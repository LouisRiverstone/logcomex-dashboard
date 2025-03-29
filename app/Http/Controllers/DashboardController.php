<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dashboard\StatisticsRequest;
use App\Http\Requests\Dashboard\VisitorsRequest;
use App\Http\Requests\Dashboard\RevenueRequest;
use App\Http\Requests\Dashboard\SalesByCategoryRequest;
use App\Http\Requests\Dashboard\UserDistributionRequest;
use App\Http\Requests\Dashboard\TrafficSourcesRequest;
use App\Http\Requests\Dashboard\ProductRatingRequest;
use App\Http\Requests\Dashboard\TasksRequest;
use App\Http\Requests\Dashboard\TransactionsRequest;
use App\UseCases\Dashboard\GetStatisticsUseCase;
use App\UseCases\Dashboard\GetVisitorsDataUseCase;
use App\UseCases\Dashboard\GetRevenueDataUseCase;
use App\UseCases\Dashboard\GetSalesByCategoryDataUseCase;
use App\UseCases\Dashboard\GetUserDistributionDataUseCase;
use App\UseCases\Dashboard\GetTrafficSourcesDataUseCase;
use App\UseCases\Dashboard\GetProductRatingDataUseCase;
use App\UseCases\Dashboard\GetTasksUseCase;
use App\UseCases\Dashboard\GetTransactionsUseCase;
use App\DTOs\Dashboard\VisitorsFilterDTO;
use App\DTOs\Dashboard\TransactionFilterDTO;
use App\DTOs\Dashboard\TaskFilterDTO;
use App\DTOs\Dashboard\RevenueFilterDTO;
use App\DTOs\Dashboard\ProductRatingFilterDTO;
use App\DTOs\Dashboard\SalesFilterDashboardDTO;
use App\DTOs\Dashboard\SalesFilterDTO;
use App\Http\Requests\Dashboard\SalesDashboardRequest;
use App\Http\Requests\Dashboard\SalesRequest;
use App\UseCases\Dashboard\GetSaleDetailUseCase;
use App\UseCases\Dashboard\GetSalesDashboardUseCase;
use App\UseCases\Dashboard\GetSalesUseCase;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Obtém as estatísticas principais do topo
     *
     * @param StatisticsRequest $request
     * @return JsonResponse
     */
    public function getStatistics(StatisticsRequest $request)
    {
        $result = (new GetStatisticsUseCase())->execute();

        return response()->json($result->toArray());
    }

    /**
     * Obtém os dados de vendas
     *
     * @param SalesRequest $request
     * @return JsonResponse
     */
    public function getSales(SalesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = SalesFilterDTO::fromArray($validated);

        $result = (new GetSalesUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Obtém os dados do dashboard de vendas
     *
     * @param SalesDashboardRequest $request
     * @return JsonResponse
     */
    public function getSalesDashboard(SalesDashboardRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = SalesFilterDashboardDTO::fromArray($validated);

        $result = (new GetSalesDashboardUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Dados para o gráfico de visitas ao site
     *
     * @param VisitorsRequest $request
     * @return JsonResponse
     */
    public function getVisitorsData(VisitorsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = VisitorsFilterDTO::fromArray($validated);

        $result = (new GetVisitorsDataUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Dados para o gráfico de receita mensal
     *
     * @param RevenueRequest $request
     * @return JsonResponse
     */
    public function getRevenueData(RevenueRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = RevenueFilterDTO::fromArray($validated);

        $result = (new GetRevenueDataUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Obtém dados de vendas por categoria
     *
     * @param SalesByCategoryRequest $request
     * @return JsonResponse
     */
    public function getSalesByCategoryData(SalesByCategoryRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = (new GetSalesByCategoryDataUseCase())->execute($validated['period']);

        return response()->json($result->toArray());
    }

    /**
     * Obtém dados de distribuição geográfica de usuários
     *
     * @param UserDistributionRequest $request
     */
    public function getUserDistributionData(UserDistributionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = (new GetUserDistributionDataUseCase())->execute($validated['period']);

        return response()->json($result->toArray());
    }

    /**
     * Obtém dados sobre fontes de tráfego
     *
     * @param TrafficSourcesRequest $request
     * @return JsonResponse
     */
    public function getTrafficSourcesData(TrafficSourcesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $result = (new GetTrafficSourcesDataUseCase())->execute($validated['period']);

        return response()->json($result->toArray());
    }

    /**
     * Obtém dados de avaliação de produto
     *
     * @param ProductRatingRequest $request
     * @return JsonResponse
     */
    public function getProductRatingData(ProductRatingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = ProductRatingFilterDTO::fromArray($validated);

        $result = (new GetProductRatingDataUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Obtém lista de tarefas pendentes
     *
     * @param TasksRequest $request
     * @return JsonResponse
     */
    public function getTasks(TasksRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = TaskFilterDTO::fromArray($validated);

        $result = (new GetTasksUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Obtém transações recentes
     *
     * @param TransactionsRequest $request
     * @return JsonResponse
     */
    public function getTransactions(TransactionsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $filter = TransactionFilterDTO::fromArray($validated);

        $result = (new GetTransactionsUseCase())->execute($filter);

        return response()->json($result->toArray());
    }

    /**
     * Obtém detalhes de uma venda
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getSaleDetails(int $id): JsonResponse
    {
        $result = (new GetSaleDetailUseCase())->execute($id);

        return response()->json($result);
    }
}
