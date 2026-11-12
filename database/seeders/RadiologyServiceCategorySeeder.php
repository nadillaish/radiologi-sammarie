<?php

namespace Database\Seeders;

use App\Models\Examination;
use App\Models\RadiologyServiceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class RadiologyServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Kategori Layanan Radiologi
        $categoriesData = [
            [
                'name'        => 'Rontgen / X-Ray',
                'description' => 'Pemeriksaan radiografi konvensional (Thorax, Abdomen, Extremitas, Tulang/Bone Survey, dll.)',
                'keywords'    => 'Rontgen, X-Ray, Foto Thorax, Thorax, Extremitas, BNO, Bone Survey, Cranium, Pelvis, Tulang',
            ],
            [
                'name'        => 'USG Abdomen',
                'description' => 'Ultrasonografi organ abdomen/rongga perut (hepar, vesika felea, ginjal, pankreas, lien)',
                'keywords'    => 'USG Abdomen, Abdomen, Hepar, Gallbladder, Ginjal, Upper Abdomen, Lower Abdomen, Perut',
            ],
            [
                'name'        => 'USG Obstetri/Ginekologi',
                'description' => 'Pemeriksaan USG kehamilan, fetomaternal, pelvis wanita, dan organ reproduksi/kandungan',
                'keywords'    => 'USG Fetomaternal, USG Kandungan, USG Kehamilan, USG Pelvis, USG Ginekologi, Obstetri, Ginekologi, Fetus, Hamil',
            ],
            [
                'name'        => 'USG Mamae',
                'description' => 'Pemeriksaan ultrasonografi payudara dan skrining jaringan payudara / mammografi',
                'keywords'    => 'USG Mammae, USG Mamae, Mammografi, Payudara, Mammae, Breast',
            ],
            [
                'name'        => 'USG Soft Tissue',
                'description' => 'Ultrasonografi jaringan lunak, leher, tiroid, kelenjar getah bening, dan massa superfisial',
                'keywords'    => 'USG Soft Tissue, Jaringan Lunak, USG Leher, USG Thyroid, Kelenjar, Tiroid, Massa',
            ],
            [
                'name'        => 'HSG',
                'description' => 'Histerosalpingografi untuk evaluasi patensi tuba fallopi dan kavum uteri',
                'keywords'    => 'HSG, Histerosalpingografi, Tuba, Uterus, Salpingografi',
            ],
            [
                'name'        => 'Pemeriksaan Radiologi Umum',
                'description' => 'Layanan radiologi umum diagnostik, CT Scan, MRI, dan evaluasi radiologis komprehensif',
                'keywords'    => 'Radiologi Umum, CT Scan, MRI, Kepala, Spine, Kontras, Non Kontras, General Radiology',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $data) {
            $categories[$data['name']] = RadiologyServiceCategory::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        // 2. Pemetaan Dokter Radiologi ke Kategori Layanan
        // dr. Suhermi Ismail: khusus pemeriksaan area penyakit dalam (USG Abdomen & Radiologi Umum terkait penyakit dalam)
        $suhermi = User::where('email', 'suhermi.ismail@sammarie.test')->first();
        if ($suhermi) {
            $suhermi->serviceCategories()->syncWithoutDetaching([
                $categories['USG Abdomen']->id,
                $categories['Pemeriksaan Radiologi Umum']->id,
            ]);
        }

        // Dokter Radiologi Lain (Data Demo Prototype)
        $andi = User::where('email', 'andi.wijaya@sammarie.test')->first();
        if ($andi) {
            $andi->serviceCategories()->syncWithoutDetaching([
                $categories['Rontgen / X-Ray']->id,
                $categories['USG Abdomen']->id,
                $categories['Pemeriksaan Radiologi Umum']->id,
            ]);
        }

        $farhan = User::where('email', 'farhan.maulana@sammarie.test')->first();
        if ($farhan) {
            $farhan->serviceCategories()->syncWithoutDetaching([
                $categories['Rontgen / X-Ray']->id,
                $categories['USG Soft Tissue']->id,
                $categories['Pemeriksaan Radiologi Umum']->id,
            ]);
        }

        $nadia = User::where('email', 'nadia.pratiwi@sammarie.test')->first();
        if ($nadia) {
            $nadia->serviceCategories()->syncWithoutDetaching([
                $categories['USG Obstetri/Ginekologi']->id,
                $categories['USG Mamae']->id,
                $categories['HSG']->id,
            ]);
        }

        $dimas = User::where('email', 'dimas.setiawan@sammarie.test')->first();
        if ($dimas) {
            $dimas->serviceCategories()->syncWithoutDetaching([
                $categories['Rontgen / X-Ray']->id,
                $categories['Pemeriksaan Radiologi Umum']->id,
            ]);
        }

        $ratna = User::where('email', 'ratna.sari@sammarie.test')->first();
        if ($ratna) {
            $ratna->serviceCategories()->syncWithoutDetaching([
                $categories['USG Obstetri/Ginekologi']->id,
                $categories['USG Mamae']->id,
                $categories['Pemeriksaan Radiologi Umum']->id,
            ]);
        }

        $sammarie = User::where('email', 'dokter.radiologi@sammarie.test')->first();
        if ($sammarie) {
            $sammarie->serviceCategories()->syncWithoutDetaching([
                $categories['Rontgen / X-Ray']->id,
                $categories['USG Abdomen']->id,
                $categories['Pemeriksaan Radiologi Umum']->id,
            ]);
        }

        // 3. Update existing demo examinations without radiologist_id
        if ($suhermi) {
            Examination::whereNull('radiologist_id')
                ->where('examination_type', 'like', '%USG Abdomen%')
                ->update(['radiologist_id' => $suhermi->id]);
        }

        if ($andi) {
            Examination::whereNull('radiologist_id')
                ->update(['radiologist_id' => $andi->id]);
        }
    }
}

