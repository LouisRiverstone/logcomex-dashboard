<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Adicionar coluna region e last_active_at na tabela users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'region')) {
                $table->string('region')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'last_active_at')) {
                $table->timestamp('last_active_at')->nullable()->after('remember_token');
            }
        });

        // Categorias
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Produtos
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Vendas
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['success', 'warning', 'danger']);
            $table->timestamps();
        });

        // Itens das vendas
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // Transações
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['sale', 'refund', 'payment']);
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['success', 'warning', 'danger']);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Tarefas
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('due_date');
            $table->enum('priority', ['danger', 'warning', 'info', 'success']);
            $table->boolean('completed')->default(false);
            $table->timestamps();
        });

        // Visitantes
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('source');
            $table->string('country')->nullable();
            $table->timestamp('visited_at');
            $table->timestamps();
        });

        // Receitas
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['service', 'product', 'subscription']);
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->timestamps();
        });

        // Avaliações de produto
        Schema::create('product_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('quality', 3, 1);
            $table->decimal('price', 3, 1);
            $table->decimal('usability', 3, 1);
            $table->decimal('design', 3, 1);
            $table->decimal('support', 3, 1);
            $table->decimal('features', 3, 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ratings');
        Schema::dropIfExists('revenues');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'region')) {
                $table->dropColumn('region');
            }
            if (Schema::hasColumn('users', 'last_active_at')) {
                $table->dropColumn('last_active_at');
            }
        });
    }
};
