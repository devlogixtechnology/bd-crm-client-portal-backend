USE crm_pipeline;

INSERT INTO leads (name, email, phone, company, source, stage, value, notes) VALUES
('Ali Raza', 'ali@example.com', '0300-1112233', 'Tech Solutions', 'Website', 'new', 25000.00, 'Interested in CRM package.'),
('Sara Khan', 'sara@example.com', '0312-4445566', 'Khan Traders', 'Facebook', 'contacted', 40000.00, 'Follow-up call scheduled.'),
('Hamza Ahmed', 'hamza@example.com', '0333-7778899', 'Ahmed Enterprises', 'Referral', 'qualified', 65000.00, 'Budget and requirements confirmed.'),
('Ayesha Noor', 'ayesha@example.com', '0345-2223344', 'Noor Digital', 'Website', 'proposal', 90000.00, 'Proposal sent to client.'),
('Usman Tariq', 'usman@example.com', '0301-5556677', 'UT Services', 'Referral', 'won', 120000.00, 'Client accepted the proposal.'),
('Hira Malik', 'hira@example.com', '0321-8889900', 'Malik Store', 'Instagram', 'lost', 30000.00, 'Client selected another vendor.');
