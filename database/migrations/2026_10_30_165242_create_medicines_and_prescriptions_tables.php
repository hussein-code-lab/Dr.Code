<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // كتالوج الأدوية المعتاد للطبيب
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('trade_name')->index();
            $table->string('active_ingredient')->nullable();
            $table->string('dosage_form')->default('قرص'); // قرص، شراب، حقن، مرهم...
            $table->string('default_dosage')->nullable();
            $table->string('default_instructions')->nullable();
            $table->timestamps();
        });

        // الروشتة الرئيسية
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->text('general_advice')->nullable();
            $table->timestamps();
        });

        // بنود الروشتة (يتم تثبيت اسم الدواء نصياً لحفظ تاريخ الروشتة حتى لو تغير الكتالوج)
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->cascadeOnDelete();
            $table->string('medicine_name');
            $table->string('dosage');
            $table->string('frequency');
            $table->string('duration');
            $table->string('instructions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
        Schema::dropIfExists('prescriptions');
        Schema::dropIfExists('medicines');
    }
};
