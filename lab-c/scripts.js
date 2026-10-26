const searchBtn = document.getElementById("search-btn");
const cityInput = document.getElementById("city-input");
const currentWeather = document.getElementById("current-weather");
const forecast = document.getElementById("forecast");
const API_KEY = "e31609d4f6053c076fbe4c3b32cd759e";

searchBtn.addEventListener("click", function() {
  let city = cityInput.value;
  if (city === "") return;
  currentWeather.innerHTML = "";
  forecast.innerHTML = "";

  let xhr = new XMLHttpRequest();
  xhr.open("GET", "https://api.openweathermap.org/data/2.5/weather?q=" + city + "&appid=" + API_KEY + "&units=metric&lang=pl", true);
  xhr.onload = function() {
    if (xhr.status === 200) {
      let data = JSON.parse(xhr.responseText);
      console.log("Bieżąca pogoda:", data);
      currentWeather.style.display = "block";
      currentWeather.innerHTML =
        "<h2>" + data.name + "</h2>" +
        "<p>Temperatura: <b>" + data.main.temp + " °C</b></p>" +
        "<p>Odczuwalna: " + data.main.feels_like + " °C</p>" +
        "<p>Opis: " + data.weather[0].description + "</p>";
    }
  };
  xhr.send();

  fetch("https://api.openweathermap.org/data/2.5/forecast?q=" + city + "&appid=" + API_KEY + "&units=metric&lang=pl")
    .then(function(response) {
      return response.json();
    })
    .then(function(data) {
      console.log("Prognoza:", data);
      let list = data.list;
      for (let i = 0; i < list.length; i++) {
        let item = list[i];
        let iconUrl = "http://openweathermap.org/img/wn/" + item.weather[0].icon + "@2x.png";

        forecast.innerHTML +=
          "<div class='weather-card'>" +
          "<h4>" + item.dt_txt + "</h4>" +
          "<img src='" + iconUrl + "'>" +
          "<p><b>" + item.main.temp + " °C</b></p>" +
          "<p>Odczuwalna: " + item.main.feels_like + " °C</p>" +
          "<p>" + item.weather[0].description + "</p>" +
          "</div>";
      }
    });
});


