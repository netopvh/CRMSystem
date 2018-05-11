<?php

use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Создаем новости дял сайта
    	DB::table('news')->insert([
    		[
    			'name' => 'Гарантийный срок',
    			'site_id' => 2,
    			'title' => 'Увеличиваем гарантийный срок',
    			'preview' => 'Осенняя акция: гарантийный срок на качество гаражных ворот мы смело умножаем на два.',
    			'content' => '<p>Производственный холдинг Doorhan предоставляет гарантию качества на изготовленные им секционные ворота один год. Воротная компания «Марс» добавляет еще один год гарантии!</p><p>Под акцию попадают ворота приобретенные этой осенью: с 20 сентября по 31 декабря 2016 года. Не откладывайте приобретение в долгий ящик - пригласите специалиста и сделайте заказ сегодня.</p><a class="button" href="/measurement">Запланировать замер!</a><p>Продление гарантийного срока подчеркивает высокую надежность и качество ворот установленных Воротной компанией «Марс».</p><p>Будьте уверены в качестве и надежности ваших ворот, и конечно, соблюдайте правила эксплуатации.</p>',
          'date_publish_begin' => '2018-04-25',
          'date_publish_end' => '2018-06-25',
    			'alias' => 'garant',
    			'company_id' => 1,
    			'system_item' => null,
    			'author_id' => 4,
          'display' => 1,
    		],
    		[
    			'name' => 'Откатка скидка',
    			'site_id' => 2,
    			'title' => 'Откатные ворота из сэндвич-панелей Doorhan со скидкой 30%',
    			'preview' => 'Хорошие новости для тех, кто в ближайший месяц планирует приобретение автоматических уличных ворот.<br>Предлагаем готовое решение: производство и монтаж откатных ворот из сэндвич-панелей Doorhan для проема шириной до 4 метров.',
    			'content' => '<p>Хорошие новости для тех, кто в ближайший месяц планирует приобретение автоматических уличных ворот.</p><p>Предлагаем готовое решение: производство и монтаж откатных ворот из сэндвич-панелей Doorhan в алюминиевой раме для проема шириной до 4 метров по цене 79 999 рублей. В стоимость уже включены работы по монтажу и по подготовке основания.</p><p>При оформлении заказа на ворота предлагаем привод Sliding-1300 по цене: 15 999 рублей. Это очень выгодно!</p><p>На ваши вопросы ответим по телефону: 8(3952) 71-77-75</p>',
          'date_publish_begin' => '2018-04-25',
          'date_publish_end' => '2018-06-25',
    			'alias' => 'skidka',
    			'company_id' => 1,
    			'system_item' => null,
    			'author_id' => 4,
          'display' => 1,
    		],
        [
          'name' => 'Аэропорт',
          'site_id' => 2,
          'title' => 'Аэропорт в безопасности!',
          'preview' => 'Реконструкция периметрового ограждения в Международном аэропорту города Иркутска. Оснащение его средствами охраны и контроля доступа. Более 12 километров забора, восемь месяцев напряженной и интересной работы, десятки ворот и противотаранных шлагбаумов, СКУД.',
          'content' => null,
          'date_publish_begin' => '2018-04-25',
          'date_publish_end' => '2018-06-25',
          'alias' => 'airport',
          'company_id' => 1,
          'system_item' => null,
          'author_id' => 4,
          'display' => 1,
        ],
        [
          'name' => 'Комплект откатки',
          'site_id' => 2,
          'title' => 'Комплектация для откатных ворот',
          'preview' => 'Планируете изготовить откатные ворота самостоятельно? Предлагаем готовое решение по комплектации откатных ворот весом до 400 кг.',
          'content' => '<h2 itemprop="name">Комплектация для откатных ворот</h2>
        <p>Планируете изготовить откатные ворота самостоятельно?</p>
        <p itemprop="description">Предлагаем готовое решение по комплектации откатных ворот весом до 400 кг. и с шириной проема до 4,3 метра. Вам останется только изготовить полотно ворот и подготовить основание.</p>
        <section class="media-object stack-for-small" itemscope itemtype="http://schema.org/NewsArticle">
          <div class="media-object-section">
            <div class="thumbnail">
                <img itemprop="image" src= "../news/balka.jpg" alt="">
            </div>
            </div>
            <div class="media-object-section right-part-news">
            <table class="table_strict">
            <caption>Состав комплекта "Балка"</caption>
              <tbody>
                <tr><td>Балка (6 метров)</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Роликовые опоры</td><td>2&nbsp;шт.</td></tr>
                <tr><td>Ролик для балки с крышкой</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Крышка задняя для балки</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Ловитель нижний</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Ловитель верхний</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Удерживающее устройство</td><td>2&nbsp;шт.</td></tr>
              </tbody>
              <tfoot>
                <tr><td colspan="2"><span class="price">11 500 руб.</span></td></tr>
              </tfoot>
            </table>    
          </div>
        </section>
        <section class="media-object stack-for-small" itemscope itemtype="http://schema.org/NewsArticle">
          <div class="media-object-section">
            <div class="thumbnail">
                <img itemprop="image" src= "../news/privod.jpg" alt="">
            </div>
            </div>
            <div class="media-object-section right-part-news">
            <table class="table_strict">
            <caption>Состав комплекта "Автоматика"</caption>
              <tbody>
                <tr><td>Привод для откатных ворот до 1300 кг. в маслянной ванне</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Зубчатая рейка</td><td>5&nbsp;метров</td></tr>
                <tr><td>Комплект крепежа</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Профиль для крепления зубчатой рейки к полотну</td><td>1&nbsp;шт.</td></tr>
                <tr><td>Пульт</td><td>2&nbsp;шт.</td></tr>
              </tbody>
              <tfoot>
                <tr><td colspan="2"><span class="price">18 000 руб.</span></td></tr>
              </tfoot>
            </table>
          </div>
        </section>',
        'date_publish_begin' => '2018-04-25',
          'date_publish_end' => '2018-06-25',
          'alias' => 'complect',
          'company_id' => 1,
          'system_item' => null,
          'author_id' => 4,
          'display' => 1,
        ],
        [
          'name' => '80 ворот',
          'site_id' => 2,
          'title' => '80 ворот - легко!',
          'preview' => 'Совместно с компанией "МонолитСтрой - Иркутск" возведен парковочный комплекс на 80 гаражей. Все боксы оснащены современными автоматическими воротами Doorhan с дистанционным управлением. Также на трех въездах в гаражный кооператив были установлены откатные ворота. Весь комплекс работ был выполнен в течении одного месяца.',
          'content' => null,
          'date_publish_begin' => '2018-04-25',
          'date_publish_end' => '2018-06-25',
          'alias' => '80-gates',
          'company_id' => 1,
          'system_item' => null,
          'author_id' => 4,
          'display' => 1,
        ],
        [
          'name' => 'Успешное завершение проекта',
          'site_id' => 2,
          'title' => 'Успешное завершение проекта',
          'preview' => 'Закончены работы по установке уличных ворот на территории Иркутского Авиационного завода перекрывающие проезд шириной 25 метров. Это самые большие автоматические откатные ворота в Сибири выполненные по проекту воротной компании "Марс". Конструкция изготавливалась непосредственно на объекте. В работах было задействовано 4 инженера и 4 специалиста среди которых сварщики и монтажники металоконструкций. Для выполнения задач также превликались 6 единиц спецтехники. Работа выполнялась в течении 3 недель и была сдана в срок.',
          'content' => null,
          'date_publish_begin' => '2018-04-25',
          'date_publish_end' => '2018-06-25',
          'alias' => 'success-project',
          'company_id' => 1,
          'system_item' => null,
          'author_id' => 4,
          'display' => 1,
        ],
    	]);
    }
  }