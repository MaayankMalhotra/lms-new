<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f0f8ff; /* Light blue background */
        }

        .sidebar {
            width: 250px;
            background-color: #87CEEB; /* Light blue sidebar */
            color: white;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar h2 {
            margin-top: 0;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #ADD8E6; /* Light blue navbar */
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            margin: 0;
            font-size: 24px;
            color: #333; /* Darker text for contrast */
        }

        .navbar .user-info {
            display: flex;
            align-items: center;
        }

        .navbar .user-info span {
            margin-right: 10px;
            font-size: 16px;
            color: #333; /* Darker text for contrast */
        }

        .navbar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .content {
            padding: 20px;
            flex: 1;
            background-color: #e6f7ff; /* Very light blue content background */
        }

        .content h2 {
            margin-top: 0;
            color: #333; /* Darker text for contrast */
        }

        .content p {
            line-height: 1.6;
            color: #555; /* Slightly darker text for readability */
        }

        .graph-placeholder {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
    <!-- Include Plotly.js -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Messages</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="navbar">
            <h1>Welcome, Admin User</h1>
            <div class="user-info">
                <span>John Doe</span>
                <!-- Public URL for user profile picture -->
                <img src="https://i.pravatar.cc/40" alt="User Avatar">
            </div>
        </div>

        <div class="content">
            <h2>Overview</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.</p>
            
            <div class="graph-placeholder">
                <h3>Monthly Sales Report</h3>
                <!-- Plotly Graph Container -->
                <div id="plotly-graph" style="width: 100%; height: 400px;"></div>
            </div>
        </div>
    </div>

    <script>
        // Data for the Plotly graph
        const data = [
            {
                x: ['January', 'February', 'March', 'April', 'May', 'June'],
                y: [2000, 3000, 2500, 4000, 3500, 5000],
                type: 'bar', // Bar chart
                marker: {
                    color: '#87CEEB', // Light blue bars
                }
            }
        ];

        // Layout for the Plotly graph
        const layout = {
            title: 'Monthly Sales',
            xaxis: {
                title: 'Month'
            },
            yaxis: {
                title: 'Sales ($)'
            },
            plot_bgcolor: '#fff', // White background
            paper_bgcolor: '#fff', // White background
        };

        // Render the Plotly graph
        Plotly.newPlot('plotly-graph', data, layout);
    </script>
</body>
</html>