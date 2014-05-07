		<table width="100%" class="table">
			<thead class="main-thead">
				<tr class="titles">
					<td width="40%">Название группы</td>
					<td width="20%" align="center">Пользователи</td>
					<td width="15%" colspan="2" class="border-right">
						Действия
					</td>
				</tr>
			</thead>
			<tbody>
				<tr class="break preData">
					<td colspan="5"></td>
				</tr>
				{foreach $GROUPS as $GROUP}
					{include file="../../parts/group.row.tpl"}
				{/foreach}
				<tr class="break afterData">
					<td colspan="5"></td>
				</tr>
			</tbody>
			<tfoot>
				{if $CAN_ADD}
				<tr>
					<td colspan="5" align="center">
						<a href="javascript:groupAdd();" class='sky'><img src='./img/icons/add.png' style='width: 32px; height: 32px;' class='icon' />Добавить новую группу</a>
					</td>
				</tr>
				{/if}
				<tr>
					<td align="left" style="vertical-align: middle;">
						{include file="ajax_pagination.tpl"}
					</td>
					<td colspan="4" style='text-align: right; vertical-align: middle;'>
						На странице: <strong id='groups-count' class='onPage'>{count($GROUPS)}</strong>
					</td>
				</tr>
			</tfoot>
		</table>