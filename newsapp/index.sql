-- create state table

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `coutry_id` varchar(255) DEFAULT NULL,
  `capital` varchar(255) DEFAULT NULL
);



INSERT INTO `state` (`id`, `name`, `coutry_id`, `capital`) VALUES
(1, 'U.P.', '1', 'Lucknow'),
(2, 'Bihar', '1', 'Patna'),
(3, 'A.P.', '1', 'Vizag'),
(4, 'Jharkhand', '1', 'Ranchi'),
(5, 'W.B.', '1', 'Kolkata'),
(6, 'Telangana', '1', 'Hyderabad');


ALTER TABLE `state`
  ADD PRIMARY KEY (`id`);


 


-- end create state table





















--create country table

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
);



INSERT INTO `country` (`id`, `name`) VALUES
(1, 'India');


ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);




-- end create country table






























--  adding column in home news


ALTER TABLE `homenews` ADD `state_id` INT(50) NULL AFTER `main_or_not`, ADD `sequence` INT(50) NULL AFTER `state_id`;





































-- --  adding column in home news

ALTER TABLE `sb_location_master` ADD `state_id` TEXT NULL AFTER `location_country`, ADD `country_id` INT(50) NULL AFTER `state_id`; 