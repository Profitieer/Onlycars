-- Select the existing OnlyCars database in phpMyAdmin before running this.
-- Existing posts keep their data. Posts without an owner remain read-only.
-- Running this script again is safe: it only adds user_id if it is missing.

SET @ownership_sql = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE posts ADD COLUMN user_id BIGINT NULL DEFAULT NULL, ADD INDEX idx_posts_owner (user_id)',
        'SELECT ''posts.user_id already exists; no change needed'' AS message'
    )
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'posts'
      AND COLUMN_NAME = 'user_id'
);

PREPARE ownership_statement FROM @ownership_sql;
EXECUTE ownership_statement;
DEALLOCATE PREPARE ownership_statement;
