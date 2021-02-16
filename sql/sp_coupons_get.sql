-- DELIMITER $$
CREATE PROCEDURE `coupons_get`
()
BEGIN
    select *, 'success' as result, 'ok' as msg, 'coupons' as dataset
    from coupons;
END;
