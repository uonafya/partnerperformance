<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityEtlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::connection('mysql_etl')->hasTable('facilitys'))  return;
        
        Schema::connection('mysql_etl')->create('facilitys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('old_id')->unsigned();;
            $table->integer('facilitycode')->nullable(); 
            $table->tinyInteger('district')->nullable();
            $table->tinyInteger('subcounty_id')->nullable();
            $table->tinyInteger('ward_id')->nullable();
            $table->text('name')->nullable(); 
            $table->text('new_name')->nullable();
            $table->tinyInteger('lab')->nullable(); 
            $table->bigInteger('partner')->unsigned();

            $table->text('ftype')->nullable();
            $table->text('DHIScode')->nullable();
            $table->text('facility_uid')->nullable(); 
            $table->tinyInteger('community')->nullable(); 
            $table->tinyInteger('is_pns')->nullable(); 
            $table->tinyInteger('is_viremia')->nullable(); 
            $table->tinyInteger('is_dsd')->nullable(); 
            $table->tinyInteger('is_otz')->nullable(); 
            $table->tinyInteger('is_men_clinic')->nullable(); 
            $table->tinyInteger('is_surge')->nullable(); 
            $table->text('longitude')->nullable();
            $table->text('latitude')->nullable();
            $table->text('burden')->nullable();
            $table->integer('artpatients')->nullable(); 
            $table->integer('pmtctnos')->nullable(); 
            $table->integer('Mless15')->nullable(); 
            $table->integer('Mmore15')->nullable(); 
            $table->integer('Fless15')->nullable(); 
            $table->integer('Fmore15')->nullable(); 
            $table->integer('totalartmar')->nullable(); 
            $table->integer('totalartsep17')->nullable(); 
            $table->integer('totalartsep15')->nullable(); 
            $table->text('asofdate')->nullable();
            $table->integer('partnerold')->nullable(); 
            $table->integer('partner2')->nullable();  
            $table->integer('partner3')->nullable();
            $table->integer('partner4')->nullable();
            $table->integer('partner5')->nullable();  
            $table->integer('partner6')->nullable();  
            $table->text('telephone')->nullable(); 
            $table->text('telephone2')->nullable(); 
            $table->text('telephone3')->nullable(); 
            $table->text('fax')->nullable(); 
            $table->text('email')->nullable();
            $table->text('PostalAddress')->nullable(); 
            $table->text('contactperson')->nullable();
            $table->text('contacttelephone')->nullable(); 
            $table->text('contacttelephone2')->nullable(); 
            $table->text('contacttelephone3')->nullable(); 
            $table->text('physicaladdress')->nullable(); 
            $table->text('ContactEmail')->nullable(); 
            $table->text('ContactEmail2')->nullable(); 
            $table->text('ContactEmail3')->nullable(); 
            $table->text('ContactEmail4')->nullable(); 
            $table->text('ContactEmail5')->nullable(); 
            $table->text('ContactEmail6')->nullable(); 
            $table->text('subcountyemail')->nullable(); 
            $table->text('countyemail')->nullable(); 
            $table->text('partneremail')->nullable(); 
            $table->integer('originalID')->nullable(); 
            $table->text('partnerlabmail')->nullable(); 
            $table->text('partnerpointmail')->nullable(); 
            $table->text('dmltemail')->nullable(); 
            $table->text('dtlcemail')->nullable(); 
            $table->text('serviceprovider')->nullable(); 
            $table->integer('smsprinterphoneno')->nullable(); 
            $table->text('smssecondarycontact')->nullable(); 
            $table->text('smsprimarycontact')->nullable(); 
            $table->text('smscontactperson')->nullable(); 
            $table->text('smsprinter')->nullable(); 
            $table->text('G4Slocation')->nullable(); 
            $table->text('G4Sphone1')->nullable(); 
            $table->text('G4Sphone2')->nullable(); 
            $table->text('G4Sphone3')->nullable(); 
            $table->text('G4Sfax')->nullable(); 
            $table->text('PMTCT')->nullable(); 
            $table->text('ART')->nullable(); 
            $table->text('Flag')->nullable(); 
            $table->integer('sent')->nullable();
            $table->tinyInteger('synched')->nullable(); 
            $table->text('invalid_dhis')->nullable(); 

            $table->timestamps();

            //f-keys
            // $table->foreign('partner')->references('old_id')->on('partners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility_etl');
    }
}
