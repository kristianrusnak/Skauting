<!DOCTYPE html>
<html>
<body>

<script>
    const data = {
        101: [3, 5],  // Example: user_id => [position_id, group_id]
        102: [2, 7]
    };

    fetch('script.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)  // Convert the array to JSON
    })
        .then(response => response.text())
        .then(data => console.log(data)) // Handle PHP response
        .catch(error => console.error('Error:', error));

</script>

</body>
</html>