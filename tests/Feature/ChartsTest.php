<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChartsTest extends TestCase
{
    public function testCharts()
    {
        $routes = [
            'testing' => ['testing_outcomes', 'testing_age', 'testing_gender', 'pos_age', 'pos_gender', 'positivity', 'discordancy', 'testing_summary', 'summary'],

            'pmtct' => ['haart', 'testing', 'starting_point', 'discovery_positivity', 'eid', 'male_testing'],

            'art' => ['current_age_breakdown', 'new_age_breakdown', 'enrolled_age_breakdown', 'new_art', 'current_art', 'current_suppression', 'treatment', 'reporting'],

            'vmmc' => ['testing', 'summary', 'adverse'],

            'tb' => ['known_status', 'newly_tested', 'tb_screening', 'ipt'],

            'keypop' => ['testing', 'summary', 'current_tx'],

            'non_mer' => ['facilities_count', 'clinics', 'achievement', 'breakdown', 'clinic_setup', 'otz_breakdown', 'dsd_impact', 'mens_impact'],

            'regimen' => ['reporting', 'summary'],

            'indicators' => ['testing', 'positivity', 'summary', 'currenttx', 'newtx'],

            'pns' => ['summary_chart', 'pns_contribution', 'summary_table', 'get_table/screened'],

            'surge' => ['testing', 'linkage', 'modality_yield', 'gender_yield', 'age_yield', 'pns', 'tx_sv', 'tx_btc', 'targets'],

        ];

        foreach ($routes as $base => $endpoints) {
            foreach ($endpoints as $endpoint) {
                $response = $this->withSession(
                    [
                        'filter_financial_year' => date('Y'),
                        'filter_groupby' => 1,
                    ]
                )->get('/' . $base . '/' . $endpoint);
                $response->assertStatus(200); 
            }
        }

        $this->assertEquals('testing', env('APP_ENV'));
    }
}
