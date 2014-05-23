<?
$date = date('d.m.Y', $arPar['info']['time_start']);
$time = date('H:i', $arPar['info']['time_start']);
$time_end = date('H:i d.m.Y', $arPar['info']['time_start'] + $arPar['info']['project_time']);
?>
<div class="row">
	<div class="row span9 offset2">
		<h3 class="text-center">ДОГОВІР ПРО НАДАННЯ ПОСЛУГ ПРОКАТУ ВЕЛОСИПЕДА</h3>
		<ol>
			<li>ФОП Хникін Павло Вікторович, м.Вінниця,вул.Соборна,99 тел.(0432)693323 ВФ ЗАТ КБ "ПРИВАТ БАНК" МФО 302689 свідоцтво №373192 (далі Наймодавець)</li>
			<li>
				<table class="table table-bordered">
					<tr>
						<th>№ велос</th>
						<th>Назва, модель велосипеда</th>
						<th>№ рами</th>
						<th>Вартість велосипеду (грн.)</th>
						<th>Вартість прокату (грн.)</th>
					</tr>
					<?$summ = 0?>
					<?foreach($arPar['info']['bikes'] as $num=>$bike):?>
					<tr>
						<td><?=$bike['bike_id']?></td>
						<td><?=$bike['model']?></td>
						<td><?=$bike['serial_id']?></td>
						<td class="text-right"><?=(isset($bike['bike_prop']['cost']) ? number_format($bike['bike_prop']['cost'] / 100, 2, '.', ' ') : '')?></td>
						<?$amount = BIKE::getRentAmount($bike['project_time'])?>
						<td class="text-right"><i><?=number_format($amount, 2, '.', ' ')?></i></td>
					</tr>
					<?$summ += $amount?>
					<?endforeach?>
					<tr>
						<th colspan="3">Всього</th>
						<td></td>
						<th class="text-right"><i><?=number_format($summ, 2, '.', ' ')?></i></th>
					</tr>
				</table>
			</li>
			<li>
				Термін дії договору:
				<p>- Договір вступає в силу з моменту його підписання і припиняється після виконання сторонами зобов"язань за даним договором</p>
			</li>
			<li>
				Плата за надання послуг сплачується згідно пейскуранту на надання послуг
			</li>
			<li>
				Обов"язки та відповідальність сторін:
				<p>- Наймодавець зобов"язується передати велосипед вказаний в таблиці 2. клієнту</p>
				<p>- Наймодавець не несе відповідальності за стан  здоров"я Клієнта під час катання та після нього, а також в разі вімови від засобів безпеки. Клієнт самостійно несе за це повну віповідальність</p>
				<p>- Клієнт зобов"язується оплатити послуги прокату згідно прейскуранту на послуги прокату велосипеду</p>
				<p>- У випадку знищення або втрати отриманого на прокат велосипеда під час використання його в момент дії договору клієнт зобов"язується відшкодувати втрати наймодавцю згідно прейскуранту на запасні частини або вартість велосипеда вказану у цьому договорі.</p>
				<p>- За перевищення часу прокату, зазначеному в цьому договорі, сплачується 10 грн. за кожну наступну годину</p>
				<p>- Клієнт зобов"язується відшкодувати погіршення технічного стану велосипеда, що сталося з його вини</p>
			</li>
			<li>
				Клієнт дає згоду на занесення його персональних данних в клієнтську базу, для полегшення в подальшому видачі йому велосипеда.
				<p>- Наймодавець зобов"язується не використовувати данні клієнта в особистих та рекламних цілях.</p>
			</li>
			<li>
				Підписи сторін:
			</li>
			<p class="lead">Я, <u><i><?=$arPar['info']['patronymic'].' '.$arPar['info']['name'].' '.$arPar['info']['surname']?></i></u></p>
			<p class="lead">Адреса проживання/прописки: <u><i><?=@$arPar['info']['properties']['live_place']?></i></u></p>
			<p class="lead">підпис:<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
			<p class="lead">дата: <u><i><?=$date?></i></u> час: з <u><i><?=$time?></i></u> до: <u><i><?=$time_end?></i></u> тел.: <u><i><?=$arPar['info']['phone']?></i></u></p>
			<div class="row"><p class="pull-left">Велосипед видав: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p>
			<p class="pull-right">Велосипед прийняв: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></p></div>
			<br><br>
			<div class="row"><div class="span2">штамп "Олімпія"</div><div class="span2 offset3">штамп "Олімпія"</div></div>
			<?if(BIKE_ACTION && $arPar['action_ofert']):?>
			<br><br>
			<p>Даю згоду для участі в акції "Велосипед за 1 грн.":___________________');</p>
			<?endif?>
			<br><br>
			<p>- Заволодіння чужим майном або придбання права на майно шляхом обману чи зловживанням довірою(шахрайство)-карається штрафом до п"ятидесяти неоподаткованих мінімумів доходів громадян або виправними роботами на строк до двох років або обмеженням волі на строк до трьох років.</p>
			<p>Ознайомлений/на_________________________________</p>
		</ol>
	</div>
</div>