USE customer_management;
ALTER TABLE sales ADD FOREIGN KEY (customer_id) REFERENCES customers(id);
ALTER TABLE sales ADD FOREIGN KEY (product_id) REFERENCES products(id);
ALTER TABLE customers ADD FOREIGN KEY (prefecture_id) REFERENCES prefectures(id);