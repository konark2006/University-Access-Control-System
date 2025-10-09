-- all_queries.sql -- run these after sourcing schema + sample_data
USE `database_project`;

-- Q1: All access events with user name, resource name, and outcome
SELECT e.event_id, e.time_stamp, u.full_name AS user_name, r.name AS resource_name, e.outcome
FROM Access_Event e
JOIN UserAccount u ON u.user_id = e.user_id
JOIN Resource r    ON r.resource_id = e.resource_id
ORDER BY e.time_stamp;

-- Q2: Count successful events per resource (descending)
SELECT r.name AS resource_name, COUNT(*) AS success_count
FROM Access_Event e
JOIN Resource r ON r.resource_id = e.resource_id
WHERE e.outcome = 'SUCCESS'
GROUP BY r.resource_id, r.name
ORDER BY success_count DESC, resource_name ASC;

-- Q3: Users with > 1 failed events (HAVING)
SELECT u.full_name, COUNT(*) AS failed_count
FROM Access_Event e
JOIN UserAccount u ON u.user_id = e.user_id
WHERE e.outcome = 'FAILED'
GROUP BY u.user_id, u.full_name
HAVING COUNT(*) > 1
ORDER BY failed_count DESC;

-- Q4: Rooms that have at least 2 requests
SELECT rm.building, rm.room_no, COUNT(*) AS requests_to_room
FROM Request q
JOIN Resource r ON r.resource_id = q.resource_id
JOIN Room rm    ON rm.resource_id = r.resource_id
GROUP BY rm.resource_id, rm.building, rm.room_no
HAVING COUNT(*) >= 2
ORDER BY requests_to_room DESC;

-- Q5: For each building, number of failed events
SELECT rm.building, COUNT(*) AS failed_events
FROM Access_Event e
JOIN Resource r ON r.resource_id = e.resource_id
JOIN Room rm    ON rm.resource_id = r.resource_id
WHERE e.outcome = 'FAILED'
GROUP BY rm.building
ORDER BY failed_events DESC, rm.building;

-- Q6: Systems with no successful events (anti-join)
SELECT s.hostname
FROM SystemRes s
JOIN Resource r ON r.resource_id = s.resource_id
LEFT JOIN Access_Event e
  ON e.resource_id = r.resource_id AND e.outcome = 'SUCCESS'
WHERE e.event_id IS NULL
ORDER BY s.hostname;

-- Q7: Success rate per user (successes / total)
SELECT u.full_name,
       SUM(e.outcome = 'SUCCESS') / COUNT(*) AS success_rate
FROM Access_Event e
JOIN UserAccount u ON u.user_id = e.user_id
GROUP BY u.user_id, u.full_name
ORDER BY success_rate DESC, u.full_name;

-- Q8: Top 3 resources by total events
SELECT r.name, COUNT(*) AS total_events
FROM Access_Event e
JOIN Resource r ON r.resource_id = e.resource_id
GROUP BY r.resource_id, r.name
ORDER BY total_events DESC, r.name
LIMIT 3;

-- Q9: Average capacity of rooms with at least one request
SELECT AVG(rm.capacity) AS avg_capacity_requested_rooms
FROM Room rm
JOIN Resource r ON r.resource_id = rm.resource_id
JOIN Request q ON q.resource_id = r.resource_id;

-- Q10: Latest event per user (window function, MySQL 8)
WITH ranked AS (
  SELECT e.*,
         ROW_NUMBER() OVER (PARTITION BY e.user_id ORDER BY e.time_stamp DESC) AS rn
  FROM Access_Event e
)
SELECT rnk.event_id, rnk.time_stamp, u.full_name, r.name AS resource_name, rnk.outcome
FROM ranked rnk
JOIN UserAccount u ON u.user_id = rnk.user_id
JOIN Resource r    ON r.resource_id = rnk.resource_id
WHERE rnk.rn = 1
ORDER BY u.full_name;

-- Q11: Extension requests with non-positive duration (data quality check)
SELECT er.request_id, er.extend_from, er.extend_until,
       TIMESTAMPDIFF(MINUTE, er.extend_from, er.extend_until) AS minutes_diff
FROM Extension_Request er
WHERE er.extend_until <= er.extend_from;

-- Q12: Security users who triggered any failed events
SELECT DISTINCT u.full_name
FROM SecurityUser su
JOIN UserAccount u ON u.user_id = su.user_id
JOIN Access_Event e ON e.user_id = su.user_id
WHERE e.outcome = 'FAILED'
ORDER BY u.full_name;