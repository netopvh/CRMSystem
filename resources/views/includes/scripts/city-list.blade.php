<script type="text/javascript">
  function checkCity() {
    // Получаем фрагмент текста
    var city = $('#city-name-field-add').val();
    // Смотрим сколько символов
    var lenCity = city.length;
    // Если символов больше 3 - делаем запрос
    if (lenCity > 3) {
      // Сам ajax запрос
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/city_list",
        type: "POST",
        data: {city_name: city},
        beforeSend: function () {
          $('.icon-load').removeClass('load');
        },
        success: function(date){
          $('.icon-load').addClass('load');
          // Удаляем все значения чтобы вписать новые
          $('.table-over').remove();
          var result = $.parseJSON(date);
          var data = '';
          if (result.error_status == 0) {
            // Перебираем циклом
            data = "<table class=\"table-content-search table-over\"><tbody>";
            for (var i = 0; i < result.count; i++) {
              data = data + "<tr data-tr=\"" + i + "\"><td><a class=\"city-add\" data-city-id=\"" + result.cities.city_id[i] + "\">" + result.cities.city_name[i] + "</a></td><td><a class=\"city-add\">" + result.cities.area_name[i] + "</a></td><td><a class=\"city-add\">" + result.cities.region_name[i] + "</a></td></tr>";
            };
            data = data + "</tbody><table>";
          };
          if (result.error_status == 1) {
            data = "<table class=\"table-content-search table-over\"><tbody><tr><td>Населенный пункт не существует в нашей базе данных, добавьте его!</td></tr></tbody><table>";
          };
          // Выводим пришедшие данные на страницу
          $('.input-icon').after(data);
        }
      });
    };
    if (lenCity <= 3) {
      // Удаляем все значения, если символов меньше 3х
      $('.table-over').remove();
      $('.item-error').remove();
      // $('#city-name-field').val('');
    };
  };
</script>