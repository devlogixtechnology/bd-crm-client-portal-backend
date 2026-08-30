<?php
class ContactsSeeder extends Seeder
{
    public function run(PDO $pdo)
    {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM contacts')->fetchColumn();
        if ($count > 0) {
            echo '        contacts already has data, skipping.' . PHP_EOL;
            return;
        }

        $leadIds = $pdo->query('SELECT id FROM leads ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);

        if (empty($leadIds)) {
            echo '        no leads found, run LeadsSeeder first. skipping.' . PHP_EOL;
            return;
        }

        $contactsTemplate = [
            ['Zainab Sheikh', 'zainab.sheikh@example.com', '03051234567', 'Procurement Manager'],
            ['Omar Farooq', 'omar.farooq@example.com', '03061234567', 'Operations Head'],
            ['Sana Iqbal', 'sana.iqbal@example.com', '03071234567', 'Finance Officer'],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO contacts (lead_id, name, email, phone, designation)
             VALUES (:lead_id, :name, :email, :phone, :designation)'
        );

        $inserted = 0;
        foreach ($leadIds as $index => $leadId) {
            $template = $contactsTemplate[$index % count($contactsTemplate)];

            $stmt->execute([
                'lead_id'     => $leadId,
                'name'        => $template[0],
                'email'       => $template[1],
                'phone'       => $template[2],
                'designation' => $template[3],
            ]);
            $inserted++;
        }

        echo '        inserted ' . $inserted . ' contacts.' . PHP_EOL;
    }
}
