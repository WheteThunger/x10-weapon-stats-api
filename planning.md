


# Plan #

I'm trying to get together a full list of functionality here.

## Backend Functionality ##

- Admins can CRUD "people" entity
  - Basic properties (name, tf2items_id)
- Admins can CRUD "weapon" entity
  - Basic properites on weapon table are simple to create/update
  - Can CRUD 0 to many attributes with the ability to set the stat_value for the attribute on the weapon
  - Can CRUD 0 to many classes the weapon can be used with
- Admins can generate a tf2items.weapons.txt file and download it
- Admins can generate a tf2items.weapons.txt file and push it to all applicable servers. This will overwrite the existing tf2items.weapons.txt file.
- A script to do the one time import of stats from the tf2items.weapons.txt file. Hopefully its one time. This script will overwrite any existing data in the DB.



## Questions ##

- When we do this "push config command" are we able to get a copy of the tf2items.weapons.txt file easily? Or do we have to do something special in order to get a backup of the file. (I was thinking initially we keep a copy of the tf2items.weapons.txt file everytime we do a push. 

