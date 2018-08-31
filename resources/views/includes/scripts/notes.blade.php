<script type="text/javascript">

	var id = '{{ $id }}';
	var model = 'App\\{{ $model }}';

	// alert(model);
	// Функция добавленяи комментария
	function addNote (id, model) {
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '/admin/notes',
			type: "POST",
			data: {id: id, model: model, body: $('textarea[name=add_body]').val()},
			success: function(html){
				$('#tr-add-note').after(html);
				$('textarea[name=add_body]').val('');
				$('textarea[name=add_body]').blur();
				// alert($('input[name=add-body]').val());
			}
		}); 
	};

	// Функция редактирования комментария
	function editNote (parent) {
		// Находим описание сущности, id и название удаляемого элемента в родителе
		
		var id = parent.attr('id').split('-')[1];
		var name = parent.data('name');

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '/admin/notes/' + id,
			type: "PATCH",
			data: {body: $('input[name=edit-body]').val()},
			success: function(html){
				$('#notes-' + id).replaceWith(html);
				// alert($('input[name=add-body]').val());
			}
		}); 
	};

	// -------------------------- Добавление ----------------------------------
	// Добавление комментария
	$(document).on('click', '#add-note', function(event) {
		event.preventDefault();
		addNote (id, model);
	});

	// При создании коммента ловим enter

	$(document).on('keydown', 'textarea[name=add_body]', function(event) {

		if ((event.keyCode == 13) && (event.shiftKey == false)) { //если нажали Enter, то true
			event.preventDefault();
			// event.stopPropagation();
			addNote (id, model);
			// alert($('input[name=add-body]').val());
		}
	});

	// -------------------------- Редактирование ----------------------------------

	// Редактирование комментария
	$(document).on('click', '[data-open="note-edit"]', function(event) {
		event.preventDefault();

		// Находим описание сущности, id и название удаляемого элемента в родителе
		var parent = $(this).closest('.item');
		var id = parent.attr('id').split('-')[1];
		var name = parent.data('name');

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '/admin/notes/' + id + '/edit',
			type: "GET",
			success: function(html){
				$('#notes-' + id).replaceWith(html);
				$('#notes-' + id + ' input[name=edit-body]').focus();
				// alert($('input[name=add-body]').val());
			}
		}); 
	});

	// Редактирование комментария
	$(document).on('click', '#edit-note', function(event) {
		event.preventDefault();

		var parent = $(this).closest('.item');
		editNote(parent);
	});

	// При редактировании коммента ловим enter
	$(document).on('keydown', 'input[name=edit-body]', function(event) {
		if (event.keyCode == 13) { //если нажали Enter, то true
			event.preventDefault();
			// event.stopPropagation();
			parent = $('input[name=edit-body]').closest('.item');
			editNote(parent);
			// alert($('input[name=add-body]').val());
		}
	});

	// При потере фокуса при редактировании возвращаем обратно
	$(document).on('focusout', 'input[name=edit-body]', function(event) {
		event.preventDefault();

		// Находим описание сущности, id и название удаляемого элемента в родителе
		var parent = $(this).closest('.item');
		var id = parent.attr('id').split('-')[1];
		var name = parent.data('name');

		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '/admin/notes/' + id + '/edit',
			type: "GET",
			data: {type: 'back'},
			success: function(html){
				$('#notes-' + id).replaceWith(html);
				// alert($('input[name=add-body]').val());
			}
		}); 
	});

	// -------------------------- Удаление ----------------------------------
	// Модалка удаления ajax
	$(document).on('click', '[data-open="item-delete-ajax"]', function() {

  		// Находим описание сущности, id и название удаляемого элемента в родителе
  		var parent = $(this).closest('.item');
  		var id = parent.attr('id').split('-')[1];
  		var name = parent.data('name');

  		$('.title-delete').text(name);
  		$('.delete-button-ajax').attr('id', 'notes-' + id);
  	});

	// Подтверждение удаления и само удаление
	$(document).on('click', '.delete-button-ajax', function(event) {

  		// Блочим отправку формы
  		event.preventDefault();

  		var id = $(this).attr('id').split('-')[1];

  		$.ajax({
  			headers: {
  				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  			},
  			url: '/admin/notes/' + id,
  			type: "DELETE",
  			success: function (data) {
  				var result = $.parseJSON(data);
          		// alert(result);

          		if (result['error_status'] == 0) {
          			$('#notes-' + id).remove();
          		} else {
          			alert(result['error_message']);
          		};
          	}
          });
  	});

  </script>