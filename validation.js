document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById("bookForm");
    form.addEventListener("submit", function(event) {
        var bookId = document.getElementById("bid").value;
        var bookName = document.getElementById("bname").value;
        var category = document.getElementById("category").value;

        // Validate Book ID format (starts with 'B' followed by 3 digits)
        if (!/^B\d{3}$/.test(bookId)) {
            alert("Invalid Book ID format. Please use the format B<3-digit number> (e.g., B001).");
            event.preventDefault(); // Prevent form submission
        }
    });
});
