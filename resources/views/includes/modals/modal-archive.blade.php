{{-- Модалка добавления в архив с refresh --}}
<div class="reveal rev-small" id="item-archive" data-reveal>
  <div class="grid-x">
    <div class="small-12 cell modal-title">
      <h5>Архивация</h5>
    </div>
  </div>
  <div class="grid-x align-center modal-content ">
    <div class="small-10 cell text-center">
      <p>Архивируем "<span class="title-archive"></span>", вы уверены?</p>
    </div>
  </div>
  <div class="grid-x align-center grid-padding-x">
    <div class="small-6 medium-4 cell">
      {{ Form::open(['id' => 'form-item-del']) }}
        <button data-close class="button modal-button delete-button" type="submit">В архив</button>
      {{ Form::close() }}
    </div>
    <div class="small-6 medium-4 cell">
      <button data-close class="button modal-button" id="save-button" type="submit">Отменить</button>
    </div>
  </div>
  <div data-close class="icon-close-modal sprite close-modal"></div> 
</div>
{{-- Конец модалки добавления в архив с refresh --}}