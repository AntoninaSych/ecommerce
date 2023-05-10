<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    // Deferred foreign key constraints to run after the structure is created.
    private array $tableConstraints;

    /*
     * Checklist
     *  -   All foreign keys have the correct type (usually bigint) and are unsigned
     *  -   All foreign keys have the correct delete action (consider: 'restrict' or 'set null' when appropriate)
     *  -   created_at, updated_at, and deleted_at are present where needed. Don't use timestamps https://stackoverflow.com/q/2012589/306474
     *  -   Seeding doesn't consist of sensitive real-world (including company and partner) information.
     *  -   Table and column comments explain the purpose of the data for the uninitiated developer
     *  -   Seeding is for test data. If application code depends on data to exist in the database, it should be inserted
     *          through a migration (e.g. a 'manager' role and a 'order.edit' permission)
     */

    public function up()
    {

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->datetime('failed_at')->useCurrent();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->datetime('created_at')->nullable();
        });

//        Schema::create('personal_access_tokens', function (Blueprint $table) {
//            $table->id();
//            $table->morphs('tokenable');
//            $table->string('name');
//            $table->string('token', 64)->unique();
//            $table->text('abilities')->nullable();
//            $table->timestamp('last_used_at')->nullable();
//            $table->timestamps();
//        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            // $table->string('api_token', 80)->unique()->nullable()->default(null);
            $table->rememberToken();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });


        /*
         * Deferring fk constraints helps keep tables organized (alphabetically) instead of thrown around to appease
         * the timing of constraints vs table creation. This is the same strategy employed by many database clients when
         * backing up databases.
         */
        $this->runForeignKeyConstraints();

    }

    /**
     * Reverse the migrations (Not necessary for the very first initial migration)
     */
    public function down(): void
    {
        // Schema::dropIfExists('some_table');
    }

    /*
     * Keep track of foreign keys, group by table (to run multiple keys per table efficiently in one query).
     */
    private function pushConstraint(string $localTable, string $localColumn, string $referencingTable, string $deleteAction, string $updateAction = 'cascade', string $referenceTableId = 'id')
    {
        $this->tableConstraints[$localTable][] = compact('localColumn', 'referencingTable', 'deleteAction', 'updateAction', 'referenceTableId');
    }

    private function runForeignKeyConstraints()
    {
        if (!empty($this->tableConstraints)) {
            foreach ($this->tableConstraints as $tableName => $constraints) {
                Schema::table($tableName, function (Blueprint $table) use ($constraints) {
                    foreach ($constraints as $constraint) {
                        $table->foreign($constraint['localColumn'])->references($constraint['referenceTableId'])->on($constraint['referencingTable'])->onUpdate($constraint['updateAction'])->onDelete($constraint['deleteAction']);
                    }
                });
            }
        }
    }

};
