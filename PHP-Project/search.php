<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Live Search using AJAX</title>
   <!-- Include the CSS file. -->
   <link rel="stylesheet" type="text/css" href="searchbox.css">
</head>
<style>

.container-searchbox {
   max-width: 600px;
   margin: 50px auto;
   background-color: #fff;
   border-radius: 8px;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


input[type="text"] {
   width: calc(100% - 20px);
   padding: 10px;
   font-size: 16px;
   border: 1px solid #ddd;
   border-radius: 4px;
   outline: none;
}

#display {
   background-color: #fff;
   border: 1px solid #ddd;
   border-radius: 4px;
   box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
   position: absolute;
   width: calc(100% - 22px);
   max-height: 200px;
   overflow-y: auto;
   display: none;
   z-index: 999;
}

#display ul {
   list-style-type: none;
   padding: 0;
   margin: 0;
}

#display ul li {
   padding: 10px;
   cursor: pointer;
}

#display ul li:hover {
   background-color: #f2f2f2;
}

</style>
<body>
   <div class="container-searchbox">
      <input
          type="text"
          id="search"
          placeholder="Search..."
          aria-label="Search"
          name="q"
      />
      <!-- Suggestions will be displayed in the following div. -->
      <div id="display"></div>
   </div>

   <!-- jQuery library is required. -->
   <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
   <!-- Include our script file. -->
   <script type="text/javascript" src="script.js"></script>
</body>
</html>
