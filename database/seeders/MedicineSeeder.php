<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'trade_name' => 'Panadol Extra',
                'active_ingredient' => 'Paracetamol 500mg + Caffeine 65mg',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرصين عند اللزوم',
                'default_instructions' => 'بعد الأكل مع كوب ماء كبير (بحد أقصى مرتين يومياً)',
            ],
            [
                'trade_name' => 'Augmentin 1g',
                'active_ingredient' => 'Amoxicillin + Clavulanic Acid',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد كل 12 ساعة',
                'default_instructions' => 'في بداية الوجبة بانتظام لمدة 7 أيام',
            ],
            [
                'trade_name' => 'Cataflam 50mg',
                'active_ingredient' => 'Diclofenac Potassium',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد كل 8 ساعات',
                'default_instructions' => 'بعد الأكل مباشرة (ممنوع على معدة فارغة)',
            ],
            [
                'trade_name' => 'Controloc 40mg',
                'active_ingredient' => 'Pantoprazole',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد يومياً',
                'default_instructions' => 'صباحاً قبل الإفطار بنصف ساعة',
            ],
            [
                'trade_name' => 'Zyrtec 10mg',
                'active_ingredient' => 'Cetirizine Dihydrochloride',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد يومياً',
                'default_instructions' => 'مساءً قبل النوم مباشرة',
            ],
            [
                'trade_name' => 'Flagyl 500mg',
                'active_ingredient' => 'Metronidazole',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد كل 8 ساعات',
                'default_instructions' => 'أثناء أو بعد الأكل لمدة 5 إلى 7 أيام',
            ],
            [
                'trade_name' => 'Voltaren 75mg/3ml',
                'active_ingredient' => 'Diclofenac Sodium',
                'dosage_form' => 'حقن عضل',
                'default_dosage' => 'أمبول واحد عضل عند اللزوم',
                'default_instructions' => 'حقن عضل عميق ببطء',
            ],
            [
                'trade_name' => 'Motilium 10mg',
                'active_ingredient' => 'Domperidone',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد قبل الوجبات',
                'default_instructions' => 'قبل الأكل بربع ساعة',
            ],
            [
                'trade_name' => 'Congestal',
                'active_ingredient' => 'Paracetamol + Pseudoephedrine + Chlorpheniramine',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرص واحد 3 مرات يومياً',
                'default_instructions' => 'بعد الأكل، وتجنب القيادة بعد تناوله',
            ],
            [
                'trade_name' => 'Alphintern',
                'active_ingredient' => 'Chymotrypsin + Trypsin',
                'dosage_form' => 'قرص',
                'default_dosage' => 'قرصين 3 مرات يومياً',
                'default_instructions' => 'قبل الأكل بساعة أو بعده بساعتين',
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::updateOrCreate(
                ['trade_name' => $medicine['trade_name']],
                $medicine
            );
        }
    }
}
