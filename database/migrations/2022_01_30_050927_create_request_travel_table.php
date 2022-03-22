<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTravelTable extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('request_travel', function (Blueprint $table) {
            $table->id();
            $table->Integer('emp_id')->foreign('emp_id')->reference('employees')->on('id')->onDelete('cascade');
            $table->Integer('recommenedBy_id')->foreign('recommenedBy_id')->reference('employees')->on('id')->onDelete('cascade');
            $table->enum('request_type', ['Planned','Ad hoc','Annual Leave']);
            $table->enum('travel_type', ['In-Country', 'International']);
            $table->string('po')->unique();
            $table->text('travel_purpose');
            $table->text('contact_address');
            $table->enum('government_invitation', ['Yes', 'No']);
            $table->enum('prev_travel_report', ['Yes', 'No']);
            $table->enum('security_clearance_obtained', ['Yes', 'No', 'in_process']);
            $table->string('attachment')->nullable();;
            $table->text('follow_up_action')->nullable();;
            $table->enum('travel_advance_requested', ['Yes', 'No']);
            $table->enum('mode_of_travel', ['by_road', 'by_air']);
            $table->enum('official_vehicle_requied', ['Yes', 'No']);
            $table->float('travel_advance_amount')->nullable();;
            $table->text('hired_vehicle_request')->nullable();
            $table->text('comment')->nullable();
            $table->enum('status', ['Pending','Approved','Rejected']);
            $table->enum('invoice_status', ['Pending','Complete']);
            $table->enum('payment_status', ['Pending','Complete']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('request_travel');
    }
}
