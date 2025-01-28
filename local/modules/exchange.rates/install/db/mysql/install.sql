create table if not exists exchange_rates
(
	id int(11) not null auto_increment,
	code varchar(255),
	date datetime,
	course float,
	primary key (id)
);