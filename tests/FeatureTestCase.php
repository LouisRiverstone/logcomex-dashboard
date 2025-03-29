<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\Exception\InvalidCountException;

abstract class FeatureTestCase extends BaseTestCase
{
    use RefreshDatabase;

    /**
     * Indica se deve semear automaticamente o banco de dados.
     *
     * @var bool
     */
    protected $autoSeed = false;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Executar migrações para garantir que temos um banco limpo
        $this->artisan('migrate');

        // Se autoSeed estiver habilitado, executar seed automaticamente
        if ($this->autoSeed) {
            $this->seedDatabase();
        }
    }

    /**
     * Use este método quando precisar de dados do seed para seus testes
     *
     * @param string|null $seeder Classe seeder específica para executar
     * @return void
     */
    protected function seedDatabase(?string $seeder = null): void
    {
        $command = 'db:seed';

        if ($seeder) {
            $command .= ' --class=' . $seeder;
        }

        $this->artisan($command);
    }

    /**
     * Este método é chamado após cada teste.
     * @return void
     * @throws InvalidCountException
     */
    public function tearDown(): void
    {
        // Limpar o Mockery após cada teste
        if (class_exists('Mockery')) {
            \Mockery::close();
        }

        parent::tearDown();
    }
}
