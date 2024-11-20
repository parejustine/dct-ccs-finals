<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <!-- Link to the custom CSS file -->
    <link rel="stylesheet" href="../admin/partials/custom-dashboard.css"> <!-- Adjust path if necessary -->
</head>
<body>

    <!-- Template Files here -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">    
        <h1 class="h2">Dashboard</h1>        
        
        <div class="row mt-5">
            <!-- Card for Number of Subjects -->
            <div class="col-12 col-xl-3">
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary text-white border-primary">Number of Subjects:</div>
                    <div class="card-body text-primary">
                        <h5 class="card-title">0</h5>
                    </div>
                </div>
            </div>

            <!-- Card for Number of Students -->
            <div class="col-12 col-xl-3">
                <div class="card border-primary mb-3">
                    <div class="card-header bg-primary text-white border-primary">Number of Students:</div>
                    <div class="card-body text-success">
                        <h5 class="card-title">0</h5>
                    </div>
                </div>
            </div>

            <!-- Card for Number of Failed Students -->
            <div class="col-12 col-xl-3">
                <div class="card border-danger mb-3">
                    <div class="card-header bg-danger text-white border-danger">Number of Failed Students:</div>
                    <div class="card-body text-danger">
                        <h5 class="card-title">0</h5>
                    </div>
                </div>
            </div>

            <!-- Card for Number of Passed Students -->
            <div class="col-12 col-xl-3">
                <div class="card border-success mb-3">
                    <div class="card-header bg-success text-white border-success">Number of Passed Students:</div>
                    <div class="card-body text-success">
                        <h5 class="card-title">0</h5> <!-- Fix the stray ">" in your code -->
                    </div>
                </div>
            </div>
        </div>    
    </main>
    <!-- Template Files here -->

</body>
</html>
