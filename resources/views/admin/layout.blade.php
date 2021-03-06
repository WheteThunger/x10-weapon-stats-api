<!DOCTYPE html>
<html>
<head>
    <style>
    nav {
        position: fixed;
        height: 100%;
        width: 140px;
        background-color: rgb(220, 220, 220);
        border: solid 1px rgb(200, 200, 200);
        border-radius: 0.5em;
        padding: 0.5em;
    }
    
    nav li {
        list-style-type: none;
    }
    
    main {
        margin-left: 180px;
    }
    </style>
</head>
<body>
	<header></header>
	<nav>
	   <li>
	       <a href="{{ route('admin.weapons.index') }}">
	           Weapon List
	       </a>
	   </li>
	   <li>
	       <a href="{{ route('admin.weapons.create') }}">
	           Weapon Create
	       </a>
      </li>
      <li>
	       <a href="{{ route('admin.attributes.index') }}">
	           Attribute List
	       </a>
      </li>
      <li>
	       <a href="{{ route('admin.attributes.create') }}">
	           Attribute Create
	       </a>
      </li>
      <li>
	       <a href="{{ route('admin.people.index') }}">
	           Person List
	       </a>
      </li>
      <li>
	       <a href="{{ route('admin.people.create') }}">
	           Person Create
	       </a>
      </li>
        <li>
            <a href="{{ route('admin.configs.index') }}">
                Config List
            </a>
        </li>
        <li>
            <a href="{{ route('admin.configs.create') }}">
                Config Create
            </a>
        </li>
	</nav>
	<main> @yield('content') </main>


<body></body>
</body>
</html>

