<?php
class ActivityLogsSeeder extends Seeder
{
    public function run(PDO $pdo)
    {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM activity_logs')->fetchColumn();
        if ($count > 0) {
            echo '        activity_logs already has data, skipping.' . PHP_EOL;
            return;
        }

        $leads = $pdo->query('SELECT id FROM leads ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
        $contactsByLead = [];
        $contactRows = $pdo->query('SELECT id, lead_id FROM contacts')->fetchAll();
        foreach ($contactRows as $row) {
            $contactsByLead[$row['lead_id']] = $row['id'];
        }

        if (empty($leads)) {
            echo '        no leads found, run LeadsSeeder first. skipping.' . PHP_EOL;
            return;
        }

        $activityTemplate = [
            ['call', 'Introductory call made to discuss requirements.', 'Sara Ali'],
            ['email', 'Sent proposal document via email.', 'Usman Tariq'],
            ['note', 'Client requested revised pricing.', 'Sara Ali'],
            ['meeting', 'Scheduled a follow-up meeting next week.', 'Usman Tariq'],
            ['status_change', 'Lead status updated after review.', 'Sara Ali'],
        ];

        $stmt = $pdo->prepare(
            'INSERT INTO activity_logs (lead_id, contact_id, activity_type, description, performed_by)
             VALUES (:lead_id, :contact_id, :activity_type, :description, :performed_by)'
        );

        $inserted = 0;
        foreach ($leads as $index => $leadId) {
            $template = $activityTemplate[$index % count($activityTemplate)];
            $contactId = isset($contactsByLead[$leadId]) ? $contactsByLead[$leadId] : null;

            $stmt->execute([
                'lead_id'       => $leadId,
                'contact_id'    => $contactId,
                'activity_type' => $template[0],
                'description'   => $template[1],
                'performed_by'  => $template[2],
            ]);
            $inserted++;
        }

        echo '        inserted ' . $inserted . ' activity log records.' . PHP_EOL;
    }
}
