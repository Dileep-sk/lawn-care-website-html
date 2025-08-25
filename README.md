Node - 20
PHP-8.3
Laravel 12
php artisan l5-swagger:generate
# Swati_Textile_Erp


API DOCS

========================= 

http://127.0.0.1:8000/api/documentation#/Users


php artisan db:seed --class=UserSeeder

php artisan db:seed --class=StockSeeder

php artisan db:seed --class=OrderSeeder

php artisan db:seed --class=JobsSeeder

php artisan passport:client --personal

php artisan passport:keys



Stock Demo Entry  ======================================

| Mark No | Design No | Item Name         | Quantity | Action |
| ------- | --------- | ----------------- | -------- | ------ |
| M001    | D1001     | Cotton Shirt      | 150.00   | Add    |
| M002    | D1002     | Silk Saree        | 80.00    | Add    |
| M003    | D1003     | Denim Jeans       | 120.00   | Add    |
| M004    | D1004     | Wool Sweater      | 60.00    | Add    |
| M005    | D1005     | Polyester T-Shirt | 200.00   | Add    |




Order Demo Entry  ======================================



| Customer Name       | Order No  | Date       | Broker Name             | Transport Company     | Mark No | Design No | Item Name         | Quantity | Rate    | Status  |
| ------------------- | --------- | ---------- | ----------------------- | --------------------- | ------- | --------- | ----------------- | -------- | ------- | ------- |
| Aarav Traders       | ORD-00001 | 25-08-2025 | Global Textiles Brokers | SpeedLine Logistics   | M001    | D1001     | Cotton Shirt      | 100.00   | 450.00  | Pending |
| Neelkanth Fabrics   | ORD-00002 | 25-08-2025 | Bright Deals Agency     | SwiftCargo Pvt. Ltd.  | M002    | D1002     | Silk Saree        | 80.00    | 1200.00 | Pending |
| Radhe Exports       | ORD-00003 | 25-08-2025 | FabricLink Associates   | Shree Transport Co.   | M003    | D1003     | Denim Jeans       | 150.00   | 750.00  | Pending |
| Riya Collections    | ORD-00004 | 25-08-2025 | Apex Textile Brokers    | QuickFreight Services | M004    | D1004     | Wool Sweater      | 60.00    | 900.00  | Pending |
| Mahadev Wholesalers | ORD-00005 | 25-08-2025 | YarnMart Solutions      | FastTrack Movers      | M005    | D1005     | Polyester T-Shirt | 200.00   | 320.00  | Pending |



===========================================================================================================================

Always Check This Rule Before Removing Stock:

Available Stock ≥ Order Quantity

===========================================================================================================================


Yes — this screenshot shows a perfect implementation example of the rule:

✅ Always check if stock is available before removing it.

🔍 Let's analyze your current stock flow:
id	mark_no_id	item_id	design_no_id	quantity	message	stock_manage	status	Meaning
1	1	1	1	150	Stock Added	1	1	Stock increased by 150 units
2	2	1	1	100	(NULL)	0	1	Removed 100 units (maybe order)
3	3	1	1	100	Order Canceled	1	1	Returned 100 units to stock
4	4	1	1	152	Order Created	0	1	Removed 152 units (new order)
📉 Let's Calculate Final Stock:
