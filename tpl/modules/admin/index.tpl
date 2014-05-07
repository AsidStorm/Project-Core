		Фильтры: Модуль: [...], Пользователь [...], IP [...], Время [... -> ...]<br /><br />
		
		<table width="100%" class="table">
			<thead>
				<tr class="titles">
					<td width="40%">Действие</td>
					<td width="20%" align="center">Пользователь</td>
					<td width="20%" align="center">IP</td>
					<td width="15%" colspan="2">
						Действия
					</td>
				</tr>
			</thead>
			<tbody>
				<tr class="break preData">
					<td colspan="5">
					</td>
				</tr>
				{foreach $LOGS as $LOG}
					{include file="../../parts/log.row.tpl"}
				{/foreach}
				<tr class="break afterData">
					<td colspan="5">
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td align="left" id='pagination'>
						{include file="ajax_pagination.tpl"}
						
					</td>
					<td colspan="4" align='right' style='vertical-align: middle;'>
						На странице: <strong id='groups-count' class='onPage'>{count($LOGS)}</strong>
					</td>
				</tr>
			</tfoot>
		</table>