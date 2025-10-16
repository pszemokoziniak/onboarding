CREATE TABLE IF NOT EXISTS realestate_price_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    realestate_id INT NOT NULL,
    changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    old_price DOUBLE NULL,
    new_price DOUBLE NULL,
    old_mkw_price DOUBLE NULL,
    new_mkw_price DOUBLE NULL,
    CONSTRAINT fk_hist_re FOREIGN KEY (realestate_id) REFERENCES realestate(id)
    ) ENGINE=InnoDB;

DELIMITER //
CREATE TRIGGER trg_realestate_price_history
    AFTER UPDATE ON realestate
    FOR EACH ROW
BEGIN
    IF (OLD.price <> NEW.price) OR (OLD.mkw_price <> NEW.mkw_price) THEN
    INSERT INTO realestate_price_history
      (realestate_id, old_price, new_price, old_mkw_price, new_mkw_price, changed_at)
    VALUES
      (OLD.id, OLD.price, NEW.price, OLD.mkw_price, NEW.mkw_price, NOW());
END IF;
END;
//
DELIMITER ;

CREATE OR REPLACE VIEW vw_realestate_price_history AS
SELECT
    h.id             AS history_id,
    h.changed_at     AS changed_at,
    r.id             AS realestate_id,
    r.number         AS realestate_number,
    i.name           AS investment_name,
    t.name           AS type_name,
    s.name           AS status_name,
    h.old_price,
    h.new_price,
    h.old_mkw_price,
    h.new_mkw_price
FROM realestate_price_history h
     JOIN realestate r        ON r.id = h.realestate_id
     LEFT JOIN investment i   ON i.id = r.investment_id
     LEFT JOIN realestate_type t   ON t.id = r.type_id
     LEFT JOIN realestate_status s ON s.id = r.status_id;