<?php
class LeadsSeeder extends Seeder
{
    public function run(PDO $pdo)
    {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn();
        if ($count > 0) {
            echo '        leads already has data, skipping.' . PHP_EOL;
            return;
        }

        $leads = [
            ['Ahmed Khan', 'ahmed.khan@example.com', '03001234567', 'Khan Traders', 'Website', 'new', 'Sara Ali'],
            ['Bilal Hussain', 'bilal.h@example.com', '03011234567', 'Hussain Textiles', 'Referral', 'contacted', 'Sara Ali'],
            ['Ayesha Malik', 'ayesha.malik@example.com', '03021234567', 'Malik Foods', 'Facebook Ads', 'qualified', 'Usman Tariq'],
            ['Fatima Noor', 'fatima.noor@example.com', '03031234567', 'Noor Enterprises', 'Cold Call', 'lost', 'Usman Tariq'],
            ['Hassan Raza', 'hassan.raza@example.com', '03041234567', 'Raza Industries', 'Website', 'converted', 'Sara Ali'],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO leads (name, email, phone, company, source, status, assigned_to)
             VALUES (:name, :email, :phone, :company, :source, :status, :assigned_to)'
        );

        foreach ($leads as $lead) {
            $stmt->execute([
                'name'        => $lead[0],
                'email'       => $lead[1],
                'phone'       => $lead[2],
                'company'     => $lead[3],
                'source'      => $lead[4],
                'status'      => $lead[5],
                'assigned_to' => $lead[6],
            ]);
        }

        echo '        inserted ' . count($leads) . ' leads.' . PHP_EOL;
    }
}
