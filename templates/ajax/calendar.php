<?xml version="1.0" encoding="UTF-8"?>
<fuji:menu xmlns="http://www.w3.org/1999/xhtml"
	xmlns:fuji="http://fuji.sourceforge.net/ns"
	xml:lang="<?php echo $this->l10n->language ?>"
	loadcontext="calendarDiv">
	<fuji:content>
		<h2><?php echo $this->l10n->calendar ?></h2>
		<div class="content">
			<table class="calendar">
				<caption><?php echo $this->l10n->january ?> 2006</caption>
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
					<tr>
						<td class="event"><a href="javascript://">1</a></td>
						<td class="event"><a href="javascript://">2</a></td>
						<td>3</td>
						<td>4</td>
						<td>5</td>
						<td class="weekend">6</td>
						<td class="weekend">7</td>
					</tr>
					<tr>
						<td>8</td>
						<td>9</td>
						<td>10</td>
						<td class="event"><a href="javascript://">11</a></td>
						<td>12</td>
						<td class="weekend">13</td>
						<td class="weekend">14</td>
					</tr>
					<tr>
						<td>15</td>
						<td>16</td>
						<td>17</td>
						<td>18</td>
						<td>19</td>
						<td class="weekend">20</td>
						<td class="weekend">21</td>
					</tr>
					<tr>
						<td>22</td>
						<td>23</td>
						<td>24</td>
						<td class="event"><a href="javascript://">25</a></td>
						<td class="event"><a href="javascript://">26</a></td>
						<td class="weekend">27</td>
						<td class="weekend event"><a href="javascript://">28</a></td>
					</tr>
					<tr>
						<td>29</td>
						<td>30</td>
						<td>31</td>
						<td class="othermonth">1</td>
						<td class="othermonth">2</td>
						<td class="weekend othermonth">3</td>
						<td class="weekend othermonth">4</td>
					</tr>
				</tbody>
			</table>
			<ul class="prevnext">
				<li class="previous"><a href="javascript://">&#x2190; <?php echo $this->l10n->previous ?></a></li>
				<li class="next"><a href="javascript://"><?php echo $this->l10n->next ?> &#x2192;</a></li>
			</ul>
		</div>
	</fuji:content>
</fuji:menu>
