<?php
class PipelinesSeeder extends Seeder
{
    public function run(PDO $pdo)
    {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM pipelines')->fetchColumn();
        if ($count > 0) {
            echo '        pipelines already has data, skipping.' . PHP_EOL;
            return;
        }

        $leadIds = $pdo->query('SELECT id FROM leads ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);

        if (empty($leadIds)) {
            echo '        no leads found, run LeadsSeeder first. skipping.' . PHP_EOL;
            return;
        }

        $stagesTemplate = [
            ['prospecting', 50000.00, 20, '+15 days'],
            ['proposal', 120000.00, 40, '+10 days'],
            ['negotiation', 250000.00, 65, '+7 days'],
            ['won', 300000.00, 100, '+0 days'],
            ['lost', 80000.00, 0, '-5 days'],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO pipelines (lead_id, stage, amount, probability, expected_close_date)
             VALUES (:lead_id, :stage, :amount, :probability, :expected_close_date)'
        );

        $inserted = 0;
        foreach ($leadIds as $index => $leadId) {
            $template = $stagesTemplate[$index % count($stagesTemplate)];

            $stmt->execute([
                'lead_id'              => $leadId,
                'stage'                => $template[0],
                'amount'               => $template[1],
                'probability'          => $template[2],
                'expected_close_date'  => date('Y-m-d', strtotime($template[3])),
            ]);
            $inserted++;
        }

        echo '        inserted ' . $inserted . ' pipeline records.' . PHP_EOL;
    }
}
