<?php

namespace App;

use DB;
use Carbon\Carbon;

class Cancer 
{

	public $table_name = 'd_cervical_cancer';

	public static function cancer_ages()
	{
		$ages = ['Unknown', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', 'Above 50'];

        DB::statement("ALTER TABLE `surge_ages` ADD COLUMN `for_cervical_cancer` tinyint(1) UNSIGNED DEFAULT 0 after `for_gbv`;");

        DB::table('surge_ages')->whereIn('age_name', $ages)->update(['for_cervical_cancer' => 1]);
	}

	public static function cancer_modalities()
	{
        // DB::statement("ALTER TABLE `surge_modalities` ADD COLUMN `parent_modality_id` tinyint(3) UNSIGNED DEFAULT 0 after `id`;");
        // DB::statement("ALTER TABLE `surge_modalities` CHANGE `modality` `modality` varchar(255) DEFAULT NULL;");
        // DB::statement("ALTER TABLE `surge_modalities` CHANGE `modality_name` `modality_name` varchar(255) DEFAULT NULL;");
		$table_name = 'd_cervical_cancer';

        $submodalities = [
        	['modality' => 'first_time_screening', 'modality_name' => 'First Time screening'],
        	['modality' => 'rescreened', 'modality_name' => 'Rescreened after previous negative results'],
        	['modality' => 'post_treatment_screening', 'modality_name' => 'Post-treatment follow-up screening'],
        ];

        $parent_modalities = [
        	['modality' => 'women_screened', 'modality_name' => 'Number of women screened (CXCA_SCRN)'],
        	['modality' => 'suspected_lesions', 'modality_name' => 'Number identified with suspicious lesions (CXCA_SCRN_POS)'],
        	['modality' => 'suspected_cancer', 'modality_name' => 'Number identified with suspected cancer'],
        	['modality' => 'women_treated', 'modality_name' => 'Number referred'],
        	['modality' => 'number_treated', 'modality_name' => 'Number treated (CXCA_TX)'],
        ];

        $other_data = ['tbl_name' => $table_name, 'male' => 0, 'female' => 1, 'unknown' => 0, 'hts' => 0, 'target' => 0];


        foreach ($parent_modalities as $mod) {
        	$data = array_merge($mod, $other_data);
        	$mod_id = DB::table('surge_modalities')->insertGetId($data);
        	foreach ($submodalities as $sub_mod) {
	        	$data = array_merge($sub_mod, $other_data);
	        	$data['parent_modality_id'] = $mod_id;
	        	DB::table('surge_modalities')->insertGetId($data);
        	}
        }
	}

	public static function cancer_columns()
	{
        $table_name = 'd_cervical_cancer';
        $sql = "CREATE TABLE `{$table_name}` (
                    id int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                    facility int(10) UNSIGNED DEFAULT 0,
                    period_id smallint(5) UNSIGNED DEFAULT 0, ";


        /*$modalities = SurgeModality::where(['tbl_name' => $table_name, 'parent_modality_id' => 0])->get();
        $ages = SurgeAge::cervicalCancer()->get();
        $gender = SurgeGender::where('gender', 'female')->first();


        foreach ($modalities as $modality) {
        	$submodalities = $modality->submodalities;

        	foreach ($submodalities as $submodality) {
        		foreach ($ages as $age) {
		            $col = $modality->modality . '_' . $submodality->modality . '_' . $age->age;
		            $alias = $modality->modality_name . ' ' . $submodality->modality_name . ' ' . $age->age_name;

		            $column = new SurgeColumn;

					$ex = str_replace(' ', '_', strtolower($alias));
					$ex = str_replace('-', '_', strtolower($ex));
		            $ex = str_replace('/', '', strtolower($ex));
		            $ex = str_replace('__', '_', strtolower($ex));
		            $ex = str_replace('__', '_', strtolower($ex));

					$sql .= " `{$col}` smallint(5) UNSIGNED DEFAULT 0, ";

					$column->fill([
						'column_name' => $col,
						'alias_name' => $alias,
						'excel_name' => $ex,
						'age_id' => $age->id,
						'gender_id' => $gender->id,
						'modality_id' => $submodality->id,
					]);
		            $column->save();

        		}
        	}
        }*/

        $columns = SurgeColumnView::where('tbl_name', $table_name)->get();
        foreach ($columns as $key => $column) {
			$sql .= " `{$column->column_name}` smallint(5) UNSIGNED DEFAULT 0, ";
        }

        $sql .= "        
	        		dateupdated date DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `facility` (`facility`),
                    KEY `period_id` (`period_id`),
                    KEY `specific` (`facility`, `period_id`)
        )";
        DB::statement("DROP TABLE IF EXISTS `{$table_name}`;");
        DB::statement($sql);
	}

    public static function fix_columns()
    {
        $table_name = 'd_cervical_cancer';
        $modalities = SurgeModality::where(['tbl_name' => $table_name, 'parent_modality_id' => 0])->get();
        foreach ($modalities as $key => $modality) {
            $submodalities = $modality->submodalities;

            foreach ($submodalities as $submodality) {
                $modality->surge_column()->where('column_name', 'like', "%{$submodality->modality}%")->update(['modality_id' => $submodality->id]);
            }
        }
    }

    public static function fix_column_names()
    {
        $table_name = 'd_cervical_cancer';
        $columns = \App\SurgeColumnView::where(['tbl_name' => $table_name])->where('excel_name', 'like', '%(%')->get();

        foreach ($columns as $key => $column) {
            $excel_name = str_replace('(', '', strtolower($column->excel_name));
            $excel_name = str_replace(')', '', strtolower($excel_name));

            \App\SurgeColumn::where(['id' => $column->id])->update(['excel_name' => $excel_name]);
        }

    }


}
