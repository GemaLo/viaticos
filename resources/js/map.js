<script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&libraries=places"></script>
<script>
  let map;
  let marker;

  function initMap() {
    const defaultPosition = { lat: 19.4326, lng: -99.1332 }; // CDMX

    map = new google.maps.Map(document.getElementById("map"), {
      center: defaultPosition,
      zoom: 14,
    });

    marker = new google.maps.Marker({
      map,
      position: defaultPosition
    });

    const input = document.getElementById("direccion");
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", () => {
      const place = autocomplete.getPlace();

      if (!place.geometry || !place.geometry.location) {
        alert("No se encontró la ubicación seleccionada.");
        return;
      }

      map.setCenter(place.geometry.location);
      map.setZoom(16);
      marker.setPosition(place.geometry.location);
    });
  }

  // Esperar a que el DOM cargue
  window.onload = initMap;
</script>
