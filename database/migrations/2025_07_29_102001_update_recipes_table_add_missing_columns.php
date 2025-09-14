<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('recipes', 'difficulty')) {
                $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy')->after('servings');
            }

            if (!Schema::hasColumn('recipes', 'category')) {
                $table->enum('category', ['sarapan', 'makan_siang', 'makan_malam', 'cemilan', 'minuman', 'dessert'])->default('cemilan')->after('difficulty');
            }

            if (!Schema::hasColumn('recipes', 'nutrition_info')) {
                $table->json('nutrition_info')->nullable()->after('image');
            }

            if (!Schema::hasColumn('recipes', 'published_at')) {
                $table->datetime('published_at')->nullable()->after('is_published');
            }
        });

        // Rename column if it exists and target doesn't exist
        if (Schema::hasColumn('recipes', 'image') && !Schema::hasColumn('recipes', 'featured_image')) {
            Schema::table('recipes', function (Blueprint $table) {
                $table->renameColumn('image', 'featured_image');
            });
        }

        // Make columns nullable if they aren't already
        Schema::table('recipes', function (Blueprint $table) {
            $table->integer('prep_time')->nullable()->change();
            $table->integer('cook_time')->nullable()->change();
            $table->integer('servings')->nullable()->change();
        });

        // Add indexes only if they don't exist
        $this->addIndexIfNotExists('recipes', ['is_published', 'published_at'], 'recipes_is_published_published_at_index');
        $this->addIndexIfNotExists('recipes', ['author_id', 'is_published'], 'recipes_author_id_is_published_index');
        $this->addIndexIfNotExists('recipes', ['category', 'is_published'], 'recipes_category_is_published_index');
        $this->addIndexIfNotExists('recipes', ['difficulty', 'is_published'], 'recipes_difficulty_is_published_index');
        $this->addIndexIfNotExists('recipes', 'slug', 'recipes_slug_index');

        // Add unique constraint only if it doesn't exist
        $this->addUniqueIfNotExists('recipes', 'slug', 'recipes_slug_unique');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            // Remove added columns if they exist
            if (Schema::hasColumn('recipes', 'difficulty')) {
                $table->dropColumn('difficulty');
            }

            if (Schema::hasColumn('recipes', 'category')) {
                $table->dropColumn('category');
            }

            if (Schema::hasColumn('recipes', 'nutrition_info')) {
                $table->dropColumn('nutrition_info');
            }

            if (Schema::hasColumn('recipes', 'published_at')) {
                $table->dropColumn('published_at');
            }
        });

        // Rename back if featured_image exists and image doesn't
        if (Schema::hasColumn('recipes', 'featured_image') && !Schema::hasColumn('recipes', 'image')) {
            Schema::table('recipes', function (Blueprint $table) {
                $table->renameColumn('featured_image', 'image');
            });
        }

        // Drop indexes if they exist
        $this->dropIndexIfExists('recipes', 'recipes_is_published_published_at_index');
        $this->dropIndexIfExists('recipes', 'recipes_author_id_is_published_index');
        $this->dropIndexIfExists('recipes', 'recipes_category_is_published_index');
        $this->dropIndexIfExists('recipes', 'recipes_difficulty_is_published_index');
        $this->dropIndexIfExists('recipes', 'recipes_slug_index');
        $this->dropUniqueIfExists('recipes', 'recipes_slug_unique');
    }

    /**
     * Helper method to add index if it doesn't exist
     */
    private function addIndexIfNotExists($table, $columns, $indexName)
    {
        $indexes = $this->getTableIndexes($table);

        if (!in_array($indexName, $indexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($columns, $indexName) {
                if (is_array($columns)) {
                    $tableBlueprint->index($columns, $indexName);
                } else {
                    $tableBlueprint->index($columns, $indexName);
                }
            });
        }
    }

    /**
     * Helper method to add unique constraint if it doesn't exist
     */
    private function addUniqueIfNotExists($table, $column, $constraintName)
    {
        $indexes = $this->getTableIndexes($table);

        if (!in_array($constraintName, $indexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($column, $constraintName) {
                $tableBlueprint->unique($column, $constraintName);
            });
        }
    }

    /**
     * Helper method to drop index if it exists
     */
    private function dropIndexIfExists($table, $indexName)
    {
        $indexes = $this->getTableIndexes($table);

        if (in_array($indexName, $indexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($indexName) {
                $tableBlueprint->dropIndex($indexName);
            });
        }
    }

    /**
     * Helper method to drop unique constraint if it exists
     */
    private function dropUniqueIfExists($table, $constraintName)
    {
        $indexes = $this->getTableIndexes($table);

        if (in_array($constraintName, $indexes)) {
            Schema::table($table, function (Blueprint $tableBlueprint) use ($constraintName) {
                $tableBlueprint->dropUnique($constraintName);
            });
        }
    }

    /**
     * Get all indexes for a table
     */
    private function getTableIndexes($table)
    {
        $indexes = [];

        try {
            $results = DB::select("SHOW INDEX FROM {$table}");
            foreach ($results as $result) {
                $indexes[] = $result->Key_name;
            }
        } catch (\Exception $e) {
            // If error occurred, assume no indexes exist
        }

        return array_unique($indexes);
    }
};
