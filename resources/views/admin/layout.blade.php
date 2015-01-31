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
	   <li><a href="{{ route('admin.weapons.create') }}">
	           Weapon Create
	       </a></li>
	</nav>
	<main> @yield('content') </main>


<body></body>
</body>
</html>

