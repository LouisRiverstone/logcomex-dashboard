<?php

namespace App\DTOs\Dashboard;

class ProductRatingFilterDTO
{
    /**
     * Create a new ProductRatingFilterDTO
     *
     * @param null|int $productId
     * @param null|int $competitorId
     * @param null|array<string,mixed> $attributes
     */
    public function __construct(
        public readonly ?int $productId = 1,
        public readonly ?int $competitorId = 2,
        public readonly ?array $attributes = null
    ) {
        // Construtor com propriedades definidas
    }

    /**
     * Create a new ProductRatingFilterDTO from an array
     *
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['productId'] ?? 1,
            $data['competitorId'] ?? 2,
            $data['attributes'] ?? null
        );
    }

    /**
     * Convert the ProductRatingFilterDTO to an array
     *
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'competitorId' => $this->competitorId,
            'attributes' => $this->attributes,
        ];
    }
}
