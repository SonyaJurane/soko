CREATE TABLE `user` (
    `user_id` int UNIQUE NOT NULL auto_increment,
    `user_name` varchar(30) NOT NULL,
    `user_phone` varchar(10) NOT NULL,
    `user_email` varchar(30) NOT NULL,
    `user_address` varchar(50) UNIQUE NOT NULL,
    `user_password` varchar(50) NOT NULL,
    PRIMARY KEY (`user_id`)
);

CREATE TABLE `item` (
    `item_id` int UNIQUE NOT NULL auto_increment,
    `item_name` varchar(30) NOT NULL,
    `item_price` varchar(10) NOT NULL,
    `department_name` varchar(10) NOT NULL,
    `item_image` varchar(50) NOT NULL,
    PRIMARY KEY (`item_id`)
);

CREATE TABLE `truck` (
    `truck_id` int UNIQUE NOT NULL auto_increment,
    `truck_status` int NOT NULL,
    PRIMARY KEY (`truck_id`)
);

CREATE TABLE `truck_unavailable` (
    `date_unavailable` DATETIME(0) NOT NULL,
    `truck_id` int NOT NULL,
    FOREIGN KEY (`truck_id`) REFERENCES Truck(`truck_id`)
);

CREATE TABLE `trip` (
    `trip_id` int UNIQUE NOT NULL auto_increment,
    `trip_origin` varchar(50) NOT NULL,
    `trip_destination` varchar(50) NOT NULL,
    `truck_id` int NOT NULL,
    FOREIGN KEY (`truck_id`) REFERENCES truck(`truck_id`),
    FOREIGN KEY (`trip_destination`) REFERENCES user(`user_address`),
    PRIMARY KEY (`trip_id`)
);

CREATE TABLE `order` (
    `order_id` int UNIQUE NOT NULL auto_increment,
    `date_issued` DATETIME(0) NOT NULL,
    `date_scheduled` DATETIME(0) NOT NULL,
    `order_price` REAL NOT NULL,
    `user_id` int NOT NULL,
    `trip_id` int NOT NULL,
    `order_status` int NOT NULL,
    FOREIGN KEY (`user_id`) REFERENCES user(`user_id`),
    FOREIGN KEY (`trip_id`) REFERENCES trip(`trip_id`),
    PRIMARY KEY (`order_id`)
);

CREATE TABLE `order_components` (
    `order_id` int NOT NULL,
    `item_id` int NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `order`(`order_id`),
    FOREIGN KEY (`item_id`) REFERENCES `item`(`item_id`)
);

INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('Oversized Coat','259.00','Clothing','oversized_coat.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('Cotton Shirt','59.90','Clothing','cotton_shirt.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('White Hoodie','49.90','Clothing','white_hoodie.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('High Collar Sweater','49.90','Clothing','high_collar_sweater.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('Basic Sweatshirt','45.90','Clothing','basic_sweatshirt.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('Chino Pants','59.90','Clothing','chino_pants.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('Textured Shirt','45.90','Clothing','textured_shirt.jpg');
INSERT INTO `item`(`item_name`, `item_price`, `department_name`, `item_image`) VALUES ('Basic Shirt','19.90','Clothing','basic_tshirt.jpg');

INSERT INTO `truck`(`truck_id`, `truck_status`) VALUES (1, 1);
INSERT INTO `truck`(`truck_id`, `truck_status`) VALUES (2, 1);
INSERT INTO `truck`(`truck_id`, `truck_status`) VALUES (3, 1);