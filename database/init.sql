USE grievance_system;

-- Insert default admin user (password should be hashed before inserting)
-- Let's assume the password hash is for: admin123
INSERT INTO users (username, password, email) VALUES
('admin', '$2y$10$aB9r0L7NV0D0y0C1u5m19OL/AdZYdIQX0V.7/NxGZ7sHTbV3rfSSK', 'admin@example.com');

-- Insert some sample complaints
INSERT INTO complaints (citizen_name, citizen_email, district_id, complaint_text, status)
VALUES
('John Doe', 'john@example.com', 1, 'Pothole issue in the main road.', 'Pending'),
('Jane Smith', 'jane@example.com', 2, 'Broken streetlights in locality.', 'Resolved'),
('Rahul Gupta', 'rahul@example.com', 3, 'Overflowing garbage bins.', 'Pending'),
('Mita Roy', 'mita@example.com', 4, 'Water supply disruption.', 'In Progress');

-- Optionally log an action
INSERT INTO complaint_actions (complaint_id, action_taken, updated_by)
VALUES
(2, 'Streetlights replaced by municipal workers.', 1);
