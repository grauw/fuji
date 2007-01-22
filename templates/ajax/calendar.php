<?php
	$months = array($this->l10n->january, $this->l10n->february, $this->l10n->march,
			$this->l10n->april, $this->l10n->may, $this->l10n->june,
			$this->l10n->july, $this->l10n->august, $this->l10n->september,
			$this->l10n->october, $this->l10n->november, $this->l10n->december);
	
	$thismonth = date('n', $this->date);
	$thisyear  = date('Y', $this->date);
	
	$lastmonthiso = date('Y-m', strtotime('last month', $this->date));
	$nextmonthiso = date('Y-m', strtotime('next month', $this->date));
?>
<?php echo $this->getPrefix() ?>
<fuji:menu xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="calendarDiv">
	<fuji:content>
		<h2><?php echo $this->l10n->calendar ?></h2>
		<div class="content">
			<table class="calendar">
				<caption><?php echo $months[$thismonth-1] . ' ' . $thisyear ?></caption>
				<col/>
				<col/>
				<col/>
				<col/>
				<col/>
				<col/>
				<col/>
				<thead>
					<tr>
						<th><?php echo $this->l10n->short_monday    ?></th>
						<th><?php echo $this->l10n->short_tuesday   ?></th>
						<th><?php echo $this->l10n->short_wednesday ?></th>
						<th><?php echo $this->l10n->short_thursday  ?></th>
						<th><?php echo $this->l10n->short_friday    ?></th>
						<th class="weekend"><?php echo $this->l10n->short_saturday  ?></th>
						<th class="weekend"><?php echo $this->l10n->short_sunday    ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
					
					$date = strtotime('next day', strtotime('last sunday', $this->date));
					do {
						echo '<tr>';
						for ($j=1; $j <= 7; $j++) {
							$day = date('j', $date);
							$month = date('n', $date);
							$classes = array();
							if ($event)
								$classes[] = 'event';
							if ($j == 6 || $j == 7)
								$classes[] = 'weekend';
							if ($month != $thismonth)
								$classes[] = 'othermonth';
							if (date('Y-m-d', $date) == date('Y-m-d', $this->today))
								$classes[] = 'today';
							$isodate = date('Y-m-d', $date);
							$class = count($classes) ? ' class="'.implode($classes, ' ').'"' : '';
							echo "<td$class><a href=\"#calendar/$isodate\">$day</a></td>";
							$date = strtotime('next day', $date);
						}
						echo '</tr>';
					} while (date('n', $date) == $thismonth);
				?>
				</tbody>
			</table>
			<ul class="prevnext">
				<li class="previous"><a href="#calendar/<?php echo $lastmonthiso ?>">&#x2190; <?php echo $this->l10n->previous ?></a></li>
				<li class="next"><a href="#calendar/<?php echo $nextmonthiso ?>"><?php echo $this->l10n->next ?> &#x2192;</a></li>
			</ul>
		</div>
	</fuji:content>
</fuji:menu>
