<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weather Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Weather Dashboard</h1>
    <div id="weather-cards" class="row"></div>
</div>

<script>
    const cities = ["San Pablo", "Alaminos", "Batangas", "Manila"];

    async function fetchWeather(city) {
        try {
            const response = await fetch(`/weather?city=${encodeURIComponent(city)}`);
            if (!response.ok) throw new Error('City not found');
            return await response.json();
        } catch (e) {
            return { error: e.message };
        }
    }

    async function loadWeather() {
        const container = document.getElementById("weather-cards");
        container.innerHTML = "";

        for (let city of cities) {
            const data = await fetchWeather(city);

            if (data.error) {
                container.innerHTML += `
                    <div class="col-md-3">
                        <div class="card shadow-sm mb-3 border-danger">
                            <div class="card-body text-center">
                                <h5 class="card-title">${city}</h5>
                                <p class="text-danger">Error loading weather</p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML += `
                    <div class="col-md-3">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body text-center">
                                <h5 class="card-title">${data.name}</h5>
                                <p class="card-text fs-4">${data.main.temp} °C</p>
                                <p class="text-muted">${data.weather[0].description}</p>
                            </div>
                        </div>
                    </div>
                `;
            }
        }
    }

    loadWeather();
</script>
</body>
</html>
