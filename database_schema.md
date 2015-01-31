# Database schema #



## Tables 

### configs

- id
- name
- parent_id
- created_at
- updated_at

### people

- id
- name
- tf2items_id
- created_at
- updated_at

### weapons

- defindex
- item_class
- item_type_name
- item_name
- item_description
- proper_name
- item_slot
- item_quality
- image_url
- image_url_large
- min_ilevel
- max_ilevel
- created_at
- updated_at

### attributes

- defindex
- name
- attribute_class
- description_string
- description_format
- effect_type
- hidden
- stored_as_integer
- created_at
- updated_at

### classes

- id
- name

### class_weapon

- class_id
- weapon_defindex


### weapon_instance

- config_id
- person_id
- weapon_defindex
- attribute_defindex
- attribute_value
- created_at
- updated_at

## Explanation

The following tables are just boring basic entities:
- configs
- people
- weapons
- attributes
- classes

### config

This table defines a weapon config. The system will support an arbitrary number of configs. In order to avoid having to redefine all the weapon_instances that are common to a bunch of configs a config can have a "parent" config. The parent config is used as a base with any weapon_instances defined in the "child" overriding the parent. Technically this can be used to allow an arbitrary number of "levels" of configs but the intended usage is to just have 2 levels. A master config and a override for each server.


### people

This table is for who a weapon_instance gets assigned to. In this sense the concept of  "Everyone" counts as a "person". So normally weapons would only be assigned to the "Everyone" person but if someone wanted to test a gun they could create a new weapon_instance and assign it to a different person. The "tf2items_id" column is the text that gets printed into the tf2items.weapons.txt file. So for "everyone" this will be "*", for an individual person this will be their steam id. The "name" column is just so we can tell them apart.

### weapons

Nothing to fancy here. This is just a weapon type with a bunch of properties taken from tf2's item schema.

### attributes

Literally a list of attribute types taken from tf2's item schema

### classes and class_weapon

These exist just so people can filter by class on the front end

### weapon_instance

This is a 4 way join table between weapons, attributes, people, and configs. Most of the time the person will be "everyone" and the config will be the "master" config, so most of the time this ends up basically just joining 2 tables.








