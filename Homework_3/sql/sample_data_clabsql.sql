-- sample_data.sql -- populate UACS with meaningful rows

USE `db_kkonark`;

-- ---- Users (supertype) ----
INSERT INTO UserAccount (user_id, full_name, email) VALUES
  (1, 'Alice Student',  'alice@student.edu'),
  (2, 'Bob Staff',      'bob@staff.edu'),
  (3, 'Carol Admin',    'carol@admin.edu'),
  (4, 'Dave Security',  'dave@sec.edu'),
  (5, 'Eve Visitor',    'eve@visitor.com'),
  (6, 'Frank Student',  'frank@student.edu');

-- ---- User subtypes ----
INSERT INTO Student(user_id) VALUES (1), (6);
INSERT INTO Staff(user_id) VALUES (2);
INSERT INTO AdminUser(user_id) VALUES (3);
INSERT INTO SecurityUser(user_id) VALUES (4);
INSERT INTO Visitor(user_id) VALUES (5);

-- ---- Resources (supertype) ----
INSERT INTO Resource (resource_id, name, relation) VALUES
  (101, 'Room A-101', 'Building A'),
  (102, 'Room A-102', 'Building A'),
  (201, 'Room B-201', 'Building B'),
  (301, 'Lab PC-01',  'CS Lab'),
  (302, 'Srv-Auth',   'Data Center'),
  (303, 'Printer-01', 'Admin Wing');

-- ---- Resource subtypes ----
INSERT INTO Room (resource_id, building, room_no, capacity) VALUES
  (101, 'A', '101', 30),
  (102, 'A', '102', 10),
  (201, 'B', '201', 50);

INSERT INTO SystemRes (resource_id, hostname) VALUES
  (301, 'labpc-01'),
  (302, 'srv-auth'),
  (303, 'printer-01');

-- ---- Requests (supertype) ----
-- request_id, status, made_by_user_id, resource_id
INSERT INTO Request VALUES
  (1001, 'PENDING',  1, 101),
  (1002, 'APPROVED', 1, 301),
  (1003, 'DENIED',   5, 102),
  (1004, 'APPROVED', 2, 201),
  (1005, 'PENDING',  6, 101),
  (1006, 'APPROVED', 3, 302),
  (1007, 'DENIED',   5, 303),
  (1008, 'APPROVED', 4, 302);

-- ---- Request subtypes ----
INSERT INTO Access_Request(request_id) VALUES
  (1001), (1002), (1004), (1005), (1006), (1008);

INSERT INTO Extension_Request(request_id, extend_from, extend_until) VALUES
  (1003, '2025-09-01 09:00:00', '2025-09-01 12:00:00'),
  (1007, '2025-09-10 10:00:00', '2025-09-10 10:00:00'); -- zero-length to show a bad case

-- ---- Access Events (supertype) ----
-- event_id, time_stamp, outcome, user_id, resource_id
INSERT INTO Access_Event VALUES
  (5001, '2025-09-01 10:00:00', 'SUCCESS', 1, 101),
  (5002, '2025-09-01 10:05:00', 'FAILED',  1, 101),
  (5003, '2025-09-01 11:00:00', 'SUCCESS', 2, 201),
  (5004, '2025-09-01 11:15:00', 'FAILED',  5, 102),
  (5005, '2025-09-02 09:30:00', 'SUCCESS', 4, 302),
  (5006, '2025-09-02 09:31:00', 'FAILED',  4, 302),
  (5007, '2025-09-03 08:00:00', 'SUCCESS', 1, 301),
  (5008, '2025-09-03 08:10:00', 'FAILED',  6, 101),
  (5009, '2025-09-03 08:12:00', 'FAILED',  6, 101),
  (5010, '2025-09-03 08:20:00', 'SUCCESS', 3, 302),
  (5011, '2025-09-04 14:00:00', 'FAILED',  5, 303),
  (5012, '2025-09-05 16:45:00', 'SUCCESS', 2, 201),
  (5013, '2025-09-05 17:00:00', 'SUCCESS', 1, 101);

-- ---- Access Event subtypes ----
INSERT INTO Success_Event(event_id) VALUES
  (5001),(5003),(5005),(5007),(5010),(5012),(5013);

INSERT INTO Failed_Event(event_id, fail_reason) VALUES
  (5002, 'Wrong PIN'),
  (5004, 'No access rights'),
  (5006, 'Card expired'),
  (5008, 'Late hours'),
  (5009, 'Multiple failed PIN'),
  (5011, 'Printer offline');