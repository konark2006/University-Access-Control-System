-- UACS schema (MySQL 8+), safe to re-run

-- Create/select database
CREATE DATABASE IF NOT EXISTS `DATABASE PROJECT`;
USE `DATABASE PROJECT`;

-- Allow dropping in FK order safely
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS Event_Triggers_Request,
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
CREATE TABLE UserAccount (
  user_id   BIGINT PRIMARY KEY AUTO_INCREMENT,
  full_name VARCHAR(100) NOT NULL,
  email     VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Resource (
  resource_id BIGINT PRIMARY KEY AUTO_INCREMENT,
  name        VARCHAR(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Request (
  request_id     BIGINT PRIMARY KEY AUTO_INCREMENT,
  status         ENUM('PENDING','APPROVED','DENIED') NOT NULL DEFAULT 'PENDING',
  requested_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  comment        VARCHAR(255),
  -- folded relationships:
  made_by_user_id BIGINT NOT NULL,        -- (Makes)
  resource_id     BIGINT NOT NULL,        -- (Targets: one resource per request)
  FOREIGN KEY (made_by_user_id) REFERENCES UserAccount(user_id),
  FOREIGN KEY (resource_id)     REFERENCES Resource(resource_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Access_Event (
  event_id     BIGINT PRIMARY KEY AUTO_INCREMENT,
  occurred_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  user_id      BIGINT NOT NULL,
  resource_id  BIGINT NOT NULL,  -- (ON)
  FOREIGN KEY (user_id)     REFERENCES UserAccount(user_id),
  FOREIGN KEY (resource_id) REFERENCES Resource(resource_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== User subclasses (ISA: class-table, shared PK) =====
CREATE TABLE Student      (
  user_id BIGINT PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Staff        (
  user_id BIGINT PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE AdminUser    (
  user_id BIGINT PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SecurityUser (
  user_id BIGINT PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Visitor      (
  user_id BIGINT PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES UserAccount(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Request subclasses =====
CREATE TABLE Access_Request (
  request_id BIGINT PRIMARY KEY,
  reason     VARCHAR(255),
  FOREIGN KEY (request_id) REFERENCES Request(request_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Extension_Request (
  request_id   BIGINT PRIMARY KEY,
  extend_from  DATETIME,
  extend_until DATETIME,
  FOREIGN KEY (request_id) REFERENCES Request(request_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Resource subclasses =====
CREATE TABLE Room (
  resource_id BIGINT PRIMARY KEY,
  building    VARCHAR(64),
  room_no     VARCHAR(16),
  capacity    INT,
  FOREIGN KEY (resource_id) REFERENCES Resource(resource_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE SystemRes (
  resource_id BIGINT PRIMARY KEY,
  hostname    VARCHAR(120),
  FOREIGN KEY (resource_id) REFERENCES Resource(resource_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Access_Event subclasses =====
CREATE TABLE Success_Event (
  event_id BIGINT PRIMARY KEY,
  FOREIGN KEY (event_id) REFERENCES Access_Event(event_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Failed_Event (
  event_id    BIGINT PRIMARY KEY,
  fail_reason VARCHAR(255),
  FOREIGN KEY (event_id) REFERENCES Access_Event(event_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Trigger (M:N: event ↔ request) =====
CREATE TABLE Event_Triggers_Request (
  event_id   BIGINT NOT NULL,
  request_id BIGINT NOT NULL,
  PRIMARY KEY (event_id, request_id),
  FOREIGN KEY (event_id)   REFERENCES Access_Event(event_id) ON DELETE CASCADE,
  FOREIGN KEY (request_id) REFERENCES Request(request_id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;