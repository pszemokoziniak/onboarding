SHOW TABLES LIKE 'realestate_price_history';
SHOW FULL TABLES WHERE Table_type='VIEW' AND Tables_in_onboard='vw_realestate_price_history';

SET @re_id := (SELECT id FROM realestate ORDER BY id LIMIT 1);
SELECT @re_id AS picked_realestate;

SELECT price, mkw_price INTO @old_price, @old_mkw FROM realestate WHERE id=@re_id;

UPDATE realestate SET price = price + 1234 WHERE id=@re_id;

SELECT realestate_id, old_price, new_price, old_mkw_price, new_mkw_price
FROM realestate_price_history
WHERE realestate_id=@re_id
ORDER BY changed_at DESC, id DESC
    LIMIT 1;

UPDATE realestate SET price = @old_price WHERE id=@re_id;

SELECT COUNT(*) AS history_entries_for_tested_id
FROM realestate_price_history
WHERE realestate_id=@re_id
  AND changed_at >= NOW() - INTERVAL 5 MINUTE;