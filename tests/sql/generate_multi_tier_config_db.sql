

select * from configs;
select * from people;
select * from weapons;
select * from weapon_instance;
select * from weapon_instance_attributes;

select c.*, c.id as config_id, c.parent_id as config_parent, p.id as person_id, p.tf2items_id as person_tf2, 
wi.id as weapon_inst_id, wi.weapon_defindex,
wia.id as weap_inst_attr_id, wia.attribute_defindex, wia.attribute_value
from weapon_instance_attributes wia
left join weapon_instance wi on wia.weapon_instance_id = wi.id
left join people p on wi.person_id = p.id
left join configs c on p.config_id = c.id
;

-- -- --

truncate configs;
truncate people;
truncate weapon_instance;
truncate weapon_instance_attributes;

insert into configs (name, parent_id) values('master', null);
SET @master_id = (select id from configs where name = 'master');
insert into configs (name, parent_id) values ('mayhem', @master_id);
SET @mayhem_id = (select id from configs where name = 'mayhem');
insert into configs (name, parent_id) values ('mayhem-freak', @mayhem_id);
SET @mayhem_freak_id = (select id from configs where name = 'mayhem-freak');

insert into people (config_id, name, tf2items_id) values (@master_id, 'bob', 'bob');
insert into people (config_id, name, tf2items_id) values (@mayhem_id, 'bob', 'bob');
insert into people (config_id, name, tf2items_id) values (@mayhem_freak_id, 'bob', 'bob');

SET @master_bob = (select id from people where config_id = @master_id);
SET @mayhem_bob = (select id from people where config_id = @mayhem_id);
SET @freak_bob = (select id from people where config_id = @mayhem_freak_id);

insert into weapon_instance (person_id, weapon_defindex) values (@master_bob, 0);
insert into weapon_instance (person_id, weapon_defindex) values (@master_bob, 1);
insert into weapon_instance (person_id, weapon_defindex) values (@mayhem_bob, 0);
insert into weapon_instance (person_id, weapon_defindex) values (@freak_bob, 0);
insert into weapon_instance (person_id, weapon_defindex) values (@mayhem_bob, 1);



SET @master_bat = (select id from weapon_instance where person_id = @master_bob AND weapon_defindex = 0);
SET @mayhem_bat = (select id from weapon_instance where person_id = @mayhem_bob AND weapon_defindex = 0);
SET @freak_bat = (select id from weapon_instance where person_id = @freak_bob AND weapon_defindex = 0);
SET @mayhem_bottle = (select id from weapon_instance where person_id = @mayhem_bob AND weapon_defindex = 1);

insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@master_bat, 22, 33);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@master_bat, 23, 44);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@master_bat, 24, 55);

insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@mayhem_bat, 321, 333);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@mayhem_bat, 123, 555);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@mayhem_bottle, 123, 555);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@mayhem_bottle, 5, 3);

insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@freak_bat, 4444, 4444);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@freak_bat, 5555, 5555);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@freak_bat, 6666, 6666);
insert into weapon_instance_attributes (weapon_instance_id, attribute_defindex, attribute_value) values (@freak_bat, 7777, 7777);


