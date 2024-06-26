function fill(Value) {
    $('#search').val(Value);
    $('#display').hide();
}

$(document).ready(function() {
    // When a key is pressed in the "Search box", this function will be called.
    $("#search").keyup(function() {
        // Assign the search box value to a JavaScript variable named "name".
        var name = $('#search').val();
        // Validate if "name" is empty.
        if (name == "") {
            // Assign an empty value to the "display" div.
            $("#display").html("");
        }
        // If the name is not empty.
        else {
            // Initiate an AJAX request.
            $.ajax({
                // Set the AJAX type as "POST".
                type: "POST",
                // Send data to "ajax.php".
                url: "ajax.php",
                // Pass the value of "name" into the "data" object.
                data: {
                    search: name
                },
                // If a result is found, this function will be called.
                success: function(html) {
                    // Assign the result to the "display" div.
                    $("#display").html(html).show();
                }
            });
        }
    });


    // Add a click event listener to the search results.
    $(document).on('click', '#display li', function() {
        // Get the text of the clicked item.
        var clickedItem = $(this).text().trim();
        // Redirect to the page for the clicked item.
        window.location.href = 'urun-detay.php?name=' + encodeURIComponent(clickedItem);
    });
});
