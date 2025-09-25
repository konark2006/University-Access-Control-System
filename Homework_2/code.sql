-- UACS schema (MySQL 8+), safe to re-run

-- Create/select database
CREATE DATABASE IF NOT EXISTS `database_project`;
USE `database_project`;

-- Allow dropping in FK order safely
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS
  Failed_Event,
  Success_Event,
  SystemRes,
  Room,
  Extension_Request,
  Access_Request,
  SecurityUser,
  AdminUser,
  Visitor,
  Staff,
  Student,
  Access_Event,
  Request,
  Resource,
  UserAccount;
SET FOREIGN_KEY_CHECKS = 1;

-- ===== Superclasses (create parents first) =====
-- ERD entity: User (attributes: user_id, name, email)
CREATE TABLE UserAccount (
  user_id   BIGINT PRIMARY KEY AUTO_INCREMENT,
  full_name VARCHAR(100) NOT NULL,
  email     VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ERD entity: Resource (attributes: resource_id, name, relation)
CREATE TABLE Resource (
  resource_id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(120) NOT NULL,
  relation    VARCHAR(120)      -- shown as an attribute on the ERD
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ERD entity: Request (attributes: request_id, status)
-- Relationships folded as FKs per ERD:
--   Makes (User 1:N Request)  -> made_by_user_id
--   Targets (Request N:1 Resource) -> resource_id
CREATE TABLE Request (
  request_id       BIGINT PRIMARY KEY AUTO_INCREMENT,
  status           ENUM('PENDING','APPROVED','DENIED') NOT NULL DEFAULT 'PENDING',
  made_by_user_id  BIGINT NOT NULL,
  resource_id      BIGINT NOT NULL,
  CONSTRAINT fk_req_user     FOREIGN KEY (made_by_user_id) REFERENCES UserAccount(user_id),
  CONSTRAINT fk_req_resource FOREIGN KEY (resource_id)     REFERENCES Resource(resource_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ERD entity: Access Event (attributes: event_id, time stamp, outcome)
-- Relationships folded as FKs per ERD:
--   Trigger (User 1:N Access Event) -> user_id
--   ON (Resource 1:N Access Event)  -> resource_id
CREATE TABLE Access_Event (
  event_id    BIGINT PRIMARY KEY AUTO_INCREMENT,
  time_stamp  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  outcome     ENUM('SUCCESS','FAILED') NOT NULL,
  user_id     BIGINT NOT NULL,
  resource_id BIGINT NOT NULL,
  CONSTRAINT fk_event_user     FOREIGN KEY (user_id)     REFERENCES UserAccount(user_id),
  CONSTRAINT fk_event_resource FOREIGN KEY (resource_id) REFERENCES Resource(resource_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== User subclasses (ISA: class-table, shared PK) =====
CREATE TABLE Student (
  user_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_student_user FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Staff (
  user_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_staff_user FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE AdminUser (
  user_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_admin_user FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SecurityUser (
  user_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_sec_user FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Visitor (
  user_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_visitor_user FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Request subclasses (ISA) =====
CREATE TABLE Access_Request (
  request_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_access_req FOREIGN KEY (request_id) REFERENCES Request(request_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Extension_Request (
  request_id   BIGINT PRIMARY KEY,
  extend_from  DATETIME,
  extend_until DATETIME,
  CONSTRAINT fk_ext_req FOREIGN KEY (request_id) REFERENCES Request(request_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Resource subclasses (ISA) =====
CREATE TABLE Room (
  resource_id BIGINT PRIMARY KEY,
  building    VARCHAR(64),
  room_no     VARCHAR(16),
  capacity    INT,
  CONSTRAINT fk_room_res FOREIGN KEY (resource_id) REFERENCES Resource(resource_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SystemRes (
  resource_id BIGINT PRIMARY KEY,
  hostname    VARCHAR(120),
  CONSTRAINT fk_sys_res FOREIGN KEY (resource_id) REFERENCES Resource(resource_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Access_Event subclasses (ISA) =====
CREATE TABLE Success_Event (
  event_id BIGINT PRIMARY KEY,
  CONSTRAINT fk_success_event FOREIGN KEY (event_id) REFERENCES Access_Event(event_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Failed_Event (
  event_id    BIGINT PRIMARY KEY,
  fail_reason VARCHAR(255),
  CONSTRAINT fk_failed_event FOREIGN KEY (event_id) REFERENCES Access_Event(event_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;