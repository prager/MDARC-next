-- MDARC stored procedures
-- Cleaned from stored-procs.txt on 2026-07-04.
-- Import after the table schema/data dump.

DROP PROCEDURE IF EXISTS GetMembers;
DELIMITER $$
CREATE PROCEDURE GetMembers(IN p_year INT)
BEGIN
    SELECT
        m.*,
        mt.*
    FROM tMembers AS m
    JOIN tMemTypes AS mt
        ON mt.id_mem_types = m.id_mem_types
    WHERE (p_year IS NULL) OR (m.cur_year >= p_year)
    ORDER BY m.lname;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS CountMembers;
DELIMITER $$
CREATE PROCEDURE CountMembers(IN p_min_year INT)
BEGIN
  SELECT COUNT(*) AS total
  FROM tMembers AS m
  WHERE m.cur_year >= p_min_year;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetMembersPaged;
DELIMITER $$
CREATE PROCEDURE GetMembersPaged(
  IN p_min_year INT,
  IN p_sort     VARCHAR(32),
  IN p_dir      VARCHAR(4),
  IN p_limit    INT,
  IN p_offset   INT
)
BEGIN
  DECLARE v_min_year INT;
  SET v_min_year = CASE WHEN MONTH(CURDATE()) = 1 THEN p_min_year - 1 ELSE p_min_year END;

  SET @sort_column = CASE
    WHEN p_sort = 'lname'      THEN 'm.lname'
    WHEN p_sort = 'fname'      THEN 'm.fname'
    WHEN p_sort = 'id_members' THEN 'm.id_members'
    WHEN p_sort = 'email'      THEN 'm.email'
    WHEN p_sort = 'callsign'   THEN 'm.callsign'
    ELSE 'm.id_members'
  END;

  SET @dir = IF(UPPER(p_dir) = 'DESC', 'DESC', 'ASC');

  SET @sql = CONCAT(
    'SELECT ',
      'm.*, ',
      'COALESCE(m.parent_primary, 0) AS parent_primary_eff, ',
      't.description ',
    'FROM tMembers AS m ',
    'LEFT JOIN tMemTypes AS t ON t.id_mem_types = m.id_mem_types ',
    'WHERE m.cur_year >= ? AND m.cur_year != 99 AND m.silent_date = 0 ',
    'ORDER BY ', @sort_column, ' ', @dir, ' ',
    'LIMIT ? OFFSET ?'
  );

  PREPARE stmt FROM @sql;

  SET @p1 = v_min_year;
  SET @p2 = p_limit;
  SET @p3 = p_offset;

  EXECUTE stmt USING @p1, @p2, @p3;
  DEALLOCATE PREPARE stmt;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetMemberById;
DELIMITER $$
CREATE PROCEDURE GetMemberById(IN p_id INT)
BEGIN
  SELECT *
  FROM tMembers
  WHERE id_members = p_id
  LIMIT 1;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS Get_Mem_Types;
DELIMITER $$
CREATE PROCEDURE Get_Mem_Types()
BEGIN
  SELECT
    id_mem_types,
    description
  FROM tMemTypes
  ORDER BY id_mem_types;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS Search_Members;
DELIMITER $$
CREATE PROCEDURE Search_Members(IN p_query VARCHAR(255))
BEGIN
  DECLARE v_q        VARCHAR(255);
  DECLARE v_q_digits VARCHAR(255);

  SET v_q = TRIM(p_query);

  IF v_q IS NULL OR v_q = '' THEN
    SELECT * FROM tMembers WHERE 1 = 0;
  ELSE
    SET v_q_digits =
      REPLACE(
        REPLACE(
          REPLACE(
            REPLACE(
              REPLACE(
                REPLACE(v_q, ' ', ''),
                '-', ''),
              '(', ''),
            ')', ''),
          '+', ''),
        '.', '');

    SELECT
      m.*,
      t.description AS type_description
    FROM tMembers AS m
    LEFT JOIN tMemTypes AS t
      ON t.id_mem_types = m.id_mem_types
    WHERE
         CAST(m.id_members AS CHAR) LIKE CONCAT('%', v_q, '%')
      OR m.callsign LIKE CONCAT('%', v_q, '%')
      OR m.fname    LIKE CONCAT('%', v_q, '%')
      OR m.lname    LIKE CONCAT('%', v_q, '%')
      OR m.email    LIKE CONCAT('%', v_q, '%')
      OR m.comment  LIKE CONCAT('%', v_q, '%')
      OR REPLACE(
           REPLACE(
             REPLACE(
               REPLACE(
                 REPLACE(
                   REPLACE(COALESCE(m.w_phone, ''), ' ', ''),
                   '-', ''),
                 '(', ''),
               ')', ''),
             '+', ''),
           '.', '')
         LIKE CONCAT('%', v_q_digits, '%')
      OR REPLACE(
           REPLACE(
             REPLACE(
               REPLACE(
                 REPLACE(
                   REPLACE(COALESCE(m.h_phone, ''), ' ', ''),
                   '-', ''),
                 '(', ''),
               ')', ''),
             '+', ''),
           '.', '')
         LIKE CONCAT('%', v_q_digits, '%')
    LIMIT 100;
  END IF;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS Search_Members_for_Mem;
DELIMITER $$
CREATE PROCEDURE Search_Members_for_Mem(IN p_query VARCHAR(255))
BEGIN
  DECLARE v_q        VARCHAR(255);
  DECLARE v_q_digits VARCHAR(255);

  SET v_q = TRIM(p_query);

  IF v_q IS NULL OR v_q = '' THEN
    SELECT * FROM tMembers WHERE 1 = 0;
  ELSE
    SET v_q_digits = REPLACE(
                       REPLACE(
                         REPLACE(
                           REPLACE(
                             REPLACE(
                               REPLACE(v_q, ' ', ''),
                               '-', ''),
                             '(', ''),
                           ')', ''),
                         '+', ''),
                       '.', '');

    SELECT
      m.*,
      CASE
        WHEN m.email_unlisted = 'True' THEN ''
        ELSE m.email
      END AS email,
      CASE
        WHEN m.cell_unlisted = 'True' THEN ''
        ELSE m.cell
      END AS cell,
      CASE
        WHEN m.w_phone_unlisted = 'True' THEN ''
        ELSE m.w_phone
      END AS w_phone,
      CASE
        WHEN m.h_phone_unlisted = 'True' THEN ''
        ELSE m.h_phone
      END AS h_phone,
      t.description AS type_description
    FROM tMembers AS m
    LEFT JOIN tMemTypes AS t
      ON t.id_mem_types = m.id_mem_types
    WHERE
      (LOWER(COALESCE(m.ok_mem_dir, '')) = 'true')
      AND (
           CAST(m.id_members AS CHAR) LIKE CONCAT('%', v_q, '%')
        OR m.callsign LIKE CONCAT('%', v_q, '%')
        OR m.fname    LIKE CONCAT('%', v_q, '%')
        OR m.lname    LIKE CONCAT('%', v_q, '%')
        OR m.email    LIKE CONCAT('%', v_q, '%')
        OR m.comment  LIKE CONCAT('%', v_q, '%')
        OR REPLACE(
             REPLACE(
               REPLACE(
                 REPLACE(
                   REPLACE(
                     REPLACE(COALESCE(m.w_phone, ''), ' ', ''),
                     '-', ''),
                   '(', ''),
                 ')', ''),
               '+', ''),
             '.', '')
           LIKE CONCAT('%', v_q_digits, '%')
        OR REPLACE(
             REPLACE(
               REPLACE(
                 REPLACE(
                   REPLACE(
                     REPLACE(COALESCE(m.h_phone, ''), ' ', ''),
                     '-', ''),
                   '(', ''),
                 ')', ''),
               '+', ''),
             '.', '')
           LIKE CONCAT('%', v_q_digits, '%')
      )
    LIMIT 100;
  END IF;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetChildMembers;
DELIMITER $$
CREATE PROCEDURE GetChildMembers(IN p_parent_id INT)
BEGIN
  SELECT
    *
  FROM tMembers
  WHERE parent_primary = p_parent_id
  ORDER BY lname;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetMembers99;
DELIMITER $$
CREATE PROCEDURE GetMembers99()
BEGIN
    SELECT *
    FROM tMembers
    WHERE cur_year = 99;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetHardNewsMembers;
DELIMITER $$
CREATE PROCEDURE GetHardNewsMembers()
BEGIN
    SELECT *
    FROM tMembers
    WHERE cur_year >= YEAR(CURDATE())
      AND hard_news = 'TRUE';
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS SetSilent;
DELIMITER $$
CREATE PROCEDURE SetSilent(
    IN p_id_members INT,
    IN p_silent_date DATE
)
BEGIN
  UPDATE tMembers
  SET silent_date = p_silent_date
  WHERE id_members = p_id_members;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetSilentKeys;
DELIMITER $$
CREATE PROCEDURE GetSilentKeys()
BEGIN
  SELECT *
  FROM tMembers
  WHERE silent_date > 0
  ORDER BY lname, fname ASC;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetAllMemData;
DELIMITER $$
CREATE PROCEDURE GetAllMemData()
BEGIN
  SELECT
    m.*,
    t.description AS mem_type_name
  FROM tMembers AS m
  LEFT JOIN tMemTypes AS t
    ON t.id_mem_types = m.id_mem_types
  ORDER BY m.id_members;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetCurEmails;
DELIMITER $$
CREATE PROCEDURE GetCurEmails()
BEGIN
  SELECT DISTINCT email
  FROM tMembers
  WHERE cur_year >= YEAR(CURDATE())
    AND cur_year != 99
    AND (silent_date IS NULL OR silent_date = 0)
    AND email IS NOT NULL
    AND TRIM(email) != ''
  ORDER BY email;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetDueEmails;
DELIMITER $$
CREATE PROCEDURE GetDueEmails()
BEGIN
  SELECT DISTINCT email
  FROM tMembers
  WHERE cur_year < YEAR(CURDATE())
    AND cur_year != 99
    AND (silent_date IS NULL OR silent_date = 0)
    AND email IS NOT NULL
    AND TRIM(email) != ''
  ORDER BY email;
END $$
DELIMITER ;

DROP PROCEDURE IF EXISTS GetDirectory;
DELIMITER $$
CREATE PROCEDURE GetDirectory(IN order_by_last_name TINYINT)
BEGIN
  SELECT
      rs.id_members,
      rs.parent_primary,
      rs.id_mem_types,
      rs.fname,
      rs.lname,
      rs.callsign,
      rs.cell,
      rs.email,
      rs.w_phone,
      rs.h_phone,
      rs.address,
      rs.city,
      rs.state,
      rs.zip,
      rs.ok_mem_dir,
      rs.cell_unlisted,
      rs.email_unlisted,
      rs.w_phone_unlisted,
      rs.h_phone_unlisted,
      rs.cur_year,
      rs.silent_date,
      rs.row_type
  FROM (
      SELECT
          m.id_members,
          m.parent_primary,
          m.id_mem_types,
          m.fname,
          m.lname,
          m.callsign,
          CASE WHEN LOWER(COALESCE(m.cell_unlisted, '')) = 'true' THEN '' ELSE m.cell END AS cell,
          CASE WHEN LOWER(COALESCE(m.email_unlisted, '')) = 'true' THEN '' ELSE m.email END AS email,
          CASE WHEN LOWER(COALESCE(m.w_phone_unlisted, '')) = 'true' THEN '' ELSE m.w_phone END AS w_phone,
          CASE WHEN LOWER(COALESCE(m.h_phone_unlisted, '')) = 'true' THEN '' ELSE m.h_phone END AS h_phone,
          m.address,
          m.city,
          m.state,
          m.zip,
          m.ok_mem_dir,
          m.cell_unlisted,
          m.email_unlisted,
          m.w_phone_unlisted,
          m.h_phone_unlisted,
          m.cur_year,
          m.silent_date,
          CASE WHEN order_by_last_name = 1 THEN m.lname ELSE NULL END AS ord_lname,
          CASE WHEN order_by_last_name = 1 THEN m.fname ELSE NULL END AS ord_fname,
          CASE WHEN order_by_last_name = 0 THEN m.callsign ELSE NULL END AS ord_callsign,
          m.id_members AS group_key,
          0 AS seq_in_group,
          NULL AS child_sort,
          'member' AS row_type
      FROM tMembers AS m
      WHERE
          (m.cur_year >= YEAR(CURDATE()))
          AND (m.silent_date IS NULL OR m.silent_date = 0)
          AND (LOWER(COALESCE(m.ok_mem_dir, '')) = 'true')

      UNION ALL

      SELECT
          c.id_members,
          c.parent_primary,
          c.id_mem_types,
          c.fname,
          c.lname,
          c.callsign,
          NULL AS cell,
          NULL AS email,
          NULL AS w_phone,
          NULL AS h_phone,
          c.address,
          c.city,
          c.state,
          c.zip,
          NULL AS ok_mem_dir,
          NULL AS cell_unlisted,
          NULL AS email_unlisted,
          NULL AS w_phone_unlisted,
          NULL AS h_phone_unlisted,
          c.cur_year,
          c.silent_date,
          CASE WHEN order_by_last_name = 1 THEN p.lname ELSE NULL END AS ord_lname,
          CASE WHEN order_by_last_name = 1 THEN p.fname ELSE NULL END AS ord_fname,
          CASE WHEN order_by_last_name = 0 THEN p.callsign ELSE NULL END AS ord_callsign,
          p.id_members AS group_key,
          1 AS seq_in_group,
          CASE
            WHEN order_by_last_name = 1 THEN CONCAT(c.lname, ' ', c.fname)
            ELSE c.callsign
          END AS child_sort,
          'family' AS row_type
      FROM tMembers AS p
      JOIN tMembers AS c
        ON c.parent_primary = p.id_members
      WHERE
          p.id_mem_types = 2
          AND (p.cur_year >= YEAR(CURDATE()))
          AND (p.silent_date IS NULL OR p.silent_date = 0)
          AND (LOWER(COALESCE(p.ok_mem_dir, '')) = 'true')
          AND (c.cur_year >= YEAR(CURDATE()))
          AND (c.silent_date IS NULL OR c.silent_date = 0)
          AND (LOWER(COALESCE(c.ok_mem_dir, '')) = 'true')
  ) AS rs
  ORDER BY
      CASE WHEN order_by_last_name = 1 THEN rs.ord_lname ELSE NULL END,
      CASE WHEN order_by_last_name = 1 THEN rs.ord_fname ELSE NULL END,
      CASE WHEN order_by_last_name = 0 THEN rs.ord_callsign ELSE NULL END,
      rs.group_key,
      rs.seq_in_group,
      rs.child_sort;
END $$
DELIMITER ;
