<?php


use App\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $companies = ['Maximum', 'PCComponentes', 'Radioshack', 'Clockrow'];
        $arrayCompany = [];
        foreach ($companies as $company)
        {
            array_push($arrayCompany, [
                'name' => $company,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                ]);
        }

        Company::insert($arrayCompany);
    }
}
