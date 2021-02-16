-- DELIMITER $$
CREATE PROCEDURE `coupons_add`
(in `app_name_1` varchar
(255), in `client_name_1` varchar
(255), in `title_en_1` varchar
(255), in `title_ar_1` varchar
(255), in `code_1` varchar
(255), in `status_1` varchar
(255), in `tyoe_1` varchar
(255), in `discount_1` varchar
(255), in `category_1` varchar
(255), in `tag_1` varchar
(255), in `row_num` varchar
(255) )
BEGIN

    if( row_num = 2 ) then
    truncate table coupons;
end
if;

insert into `coupons`(`
app_name`,
`client_name
`, `title_en`, `title_ar`, `code`, `status`, `tyoe`, `discount`, `category`, `tag`)
SELECT app_name_1, client_name_1 , title_en_1, title_ar_1, code_1, status_1, tyoe_1, discount_1, category_1, tag_1;


END;
